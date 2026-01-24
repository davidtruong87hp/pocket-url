import {
  CallHandler,
  ExecutionContext,
  Injectable,
  NestInterceptor,
} from '@nestjs/common';
import { MetricsService } from './metrics.service';
import { Observable } from 'rxjs';
import { tap } from 'rxjs/operators';

@Injectable()
export class MetricsInterceptor implements NestInterceptor {
  constructor(private readonly metricsService: MetricsService) {}

  intercept(
    context: ExecutionContext,
    next: CallHandler<any>,
  ): Observable<any> | Promise<Observable<any>> {
    const startTime = Date.now();
    this.metricsService.startRedirectTracking();

    return next.handle().pipe(
      tap({
        next: () => {
          const duration = Date.now() - startTime;
          this.metricsService.recordRedirectDuration(duration, 'success');
          this.metricsService.incrementRedirects('success');
          this.metricsService.stopRedirectTracking();
        },
        error: () => {
          const duration = Date.now() - startTime;
          this.metricsService.recordRedirectDuration(duration, 'error');
          this.metricsService.incrementRedirects('error');
          this.metricsService.stopRedirectTracking();
        },
      }),
    );
  }
}
