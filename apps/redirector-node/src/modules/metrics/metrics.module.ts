import { Global, Module } from '@nestjs/common';
import { MetricsService } from './metrics.service';
import { MetricsInterceptor } from './metrics.interceptor';
import { RuntimeMetricsService } from './runtime-metrics.service';
import { RedisMetricsService } from './redis-metrics.service';
import { GrpcMetricsService } from './grpc-metrics.service';
import { RabbitMQMetricsService } from './rabbitmq-metrics.service';

@Global()
@Module({
  providers: [
    // Core metrics
    MetricsService,
    MetricsInterceptor,

    // Runtime metrics
    RuntimeMetricsService,

    // Dependency metrics
    RedisMetricsService,
    GrpcMetricsService,
    RabbitMQMetricsService,
  ],
  exports: [
    MetricsService,
    MetricsInterceptor,
    RedisMetricsService,
    GrpcMetricsService,
    RabbitMQMetricsService,
  ],
})
export class MetricsModule {}
