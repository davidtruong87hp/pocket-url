import { Injectable, OnModuleInit } from '@nestjs/common';
import { Counter, Histogram, Meter } from '@opentelemetry/api';
import { MeterCategory, getMeter } from './meter-registry';

@Injectable()
export class RabbitMQMetricsService implements OnModuleInit {
  private meter: Meter;

  private publishCounter: Counter;
  private errorCounter: Counter;

  private publishDuration: Histogram;

  onModuleInit() {
    this.meter = getMeter(MeterCategory.RABBITMQ);

    this.publishCounter = this.meter.createCounter(
      'rabbitmq.messages.published',
      {
        description: 'Total messages published to RabbitMQ',
      },
    );

    this.errorCounter = this.meter.createCounter('rabbitmq.errors.total', {
      description: 'Total RabbitMQ publish errors',
    });

    this.publishDuration = this.meter.createHistogram(
      'rabbitmq.publish.duration',
      {
        description: 'Message publish duration in milliseconds',
        unit: 'ms',
      },
    );
  }

  recordPublish(
    exchange: string,
    routingKey: string,
    status: 'success' | 'error',
    durationMs: number,
  ) {
    this.publishCounter.add(1, { exchange, routing_key: routingKey, status });
    this.publishDuration.record(durationMs, {
      exchange,
      routing_key: routingKey,
      status,
    });
  }

  recordError(
    exchange: string,
    routingKey: string,
    errorType: string,
    errorMessage?: string,
  ) {
    const labels: Record<string, string> = {
      exchange,
      routing_key: routingKey,
      error_type: errorType,
    };

    if (errorMessage) {
      labels.error_message = errorMessage;
    }

    this.errorCounter.add(1, labels);
  }

  async trackPublish<T>(
    exchange: string,
    routingKey: string,
    fn: () => Promise<T>,
  ): Promise<T> {
    const startTime = Date.now();

    try {
      const result = await fn();

      this.recordPublish(
        exchange,
        routingKey,
        'success',
        Date.now() - startTime,
      );

      return result;
    } catch (error) {
      this.recordPublish(exchange, routingKey, 'error', Date.now() - startTime);

      const errorType = error instanceof Error ? error.name : 'PublishError';
      const errorMessage = error instanceof Error ? error.message : undefined;
      this.recordError(exchange, routingKey, errorType, errorMessage);

      throw error;
    }
  }

  async trackQueuePublish<T>(queue: string, fn: () => Promise<T>): Promise<T> {
    const startTime = Date.now();

    try {
      const result = await fn();

      this.publishCounter.add(1, { queue, status: 'success' });
      this.publishDuration.record(Date.now() - startTime, {
        queue,
        status: 'success',
      });

      return result;
    } catch (error) {
      this.publishCounter.add(1, { queue, status: 'error' });
      this.publishDuration.record(Date.now() - startTime, {
        queue,
        status: 'error',
      });

      const errorType = error instanceof Error ? error.name : 'PublishError';
      this.errorCounter.add(1, { queue, error_type: errorType });

      throw error;
    }
  }
}
