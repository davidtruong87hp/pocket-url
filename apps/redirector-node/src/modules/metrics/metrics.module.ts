import { Global, Module } from '@nestjs/common';
import { MetricsService } from './metrics.service';
import { MetricsInterceptor } from './metrics.interceptor';
import { RuntimeMetricsService } from './runtime-metrics.service';

@Global()
@Module({
  providers: [MetricsService, MetricsInterceptor, RuntimeMetricsService],
  exports: [MetricsService, MetricsInterceptor],
})
export class MetricsModule {}
