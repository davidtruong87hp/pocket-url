import { Injectable, OnModuleInit } from '@nestjs/common';
import { Counter, Histogram, Meter } from '@opentelemetry/api';
import { MeterCategory, getMeter } from './meter-registry';

@Injectable()
export class GrpcMetricsService implements OnModuleInit {
  private meter: Meter;

  private callCounter: Counter;
  private errorCounter: Counter;

  private callDuration: Histogram;

  onModuleInit() {
    this.meter = getMeter(MeterCategory.GRPC);

    this.callCounter = this.meter.createCounter('grpc.calls.total', {
      description: 'Total number of gRPC calls',
    });

    this.errorCounter = this.meter.createCounter('grpc.errors.total', {
      description: 'Total number of gRPC errors',
    });

    this.callDuration = this.meter.createHistogram('grpc.call.duration', {
      description: 'gRPC call duration in milliseconds',
      unit: 'ms',
    });
  }

  recordCall(
    method: string,
    status: 'success' | 'error',
    durationMs: number,
    statusCode?: string,
  ) {
    const labels: Record<string, string> = { method, status };

    if (statusCode) {
      labels.status_code = statusCode;
    }

    this.callCounter.add(1, labels);
    this.callDuration.record(durationMs, labels);
  }

  recordError(method: string, errorCode: string, errorMessage?: string) {
    const labels: Record<string, string> = { method, error_code: errorCode };

    if (errorMessage) {
      labels.error_message = errorMessage;
    }

    this.errorCounter.add(1, labels);
  }

  async trackCall<T>(method: string, fn: () => Promise<T>): Promise<T> {
    const startTime = Date.now();

    try {
      const result = await fn();

      this.recordCall(method, 'success', Date.now() - startTime);

      return result;
    } catch (error) {
      this.recordCall(method, 'error', Date.now() - startTime);

      // Extract gRPC error code if available
      const errorCode = error instanceof Error ? error.name : 'UNKNOWN';
      const errorMessage = error instanceof Error ? error.message : undefined;
      this.recordError(method, errorCode, errorMessage);

      throw error;
    }
  }
}
