import { Injectable, OnModuleInit } from '@nestjs/common';
import { Counter, Histogram, Meter } from '@opentelemetry/api';
import { getMeter, MeterCategory } from './meter-registry';

@Injectable()
export class RedisMetricsService implements OnModuleInit {
  private meter: Meter;

  private operationCounter: Counter;
  private errorCounter: Counter;

  private operationDuration: Histogram;

  onModuleInit() {
    this.meter = getMeter(MeterCategory.REDIS);

    this.operationCounter = this.meter.createCounter('redis.operations.total', {
      description: 'Total number of Redis operations',
    });

    this.errorCounter = this.meter.createCounter('redis.errors.total', {
      description: 'Total number of Redis errors',
    });

    this.operationDuration = this.meter.createHistogram(
      'redis.operation.duration',
      {
        description: 'Redis operation duration in milliseconds',
        unit: 'ms',
      },
    );
  }

  recordOperation(
    operation: 'get' | 'set' | 'delete' | 'exists' | 'ttl',
    status: 'success' | 'error',
    durationMs: number,
  ) {
    this.operationCounter.add(1, { operation, status });
    this.operationDuration.record(durationMs, { operation, status });
  }

  recordError(operation: string, errorType: string) {
    this.errorCounter.add(1, { operation, error_type: errorType });
  }

  async trackOperation<T>(
    operation: 'get' | 'set' | 'delete' | 'exists' | 'ttl',
    fn: () => Promise<T>,
  ): Promise<T> {
    const startTime = Date.now();

    try {
      const result = await fn();

      this.recordOperation(operation, 'success', Date.now() - startTime);

      return result;
    } catch (error) {
      this.recordOperation(operation, 'error', Date.now() - startTime);

      const errorType = error instanceof Error ? error.name : 'UnknownError';
      this.recordError(operation, errorType);

      throw error;
    }
  }
}
