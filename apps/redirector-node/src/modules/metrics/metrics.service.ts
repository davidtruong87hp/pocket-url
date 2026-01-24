import { Injectable, OnModuleInit } from '@nestjs/common';
import { Counter, Histogram, Meter } from '@opentelemetry/api';
import { getMeter, MeterCategory } from './meter-registry';

@Injectable()
export class MetricsService implements OnModuleInit {
  private meter: Meter;

  // Counters
  private redirectCounter: Counter;
  private cacheHitCounter: Counter;
  private cacheMissCounter: Counter;
  private clickEventPublishedCounter: Counter;
  private clickEventFailedCounter: Counter;
  private notFoundCounter: Counter;

  // Histograms
  private redirectDuration: Histogram;
  private cacheResolveDuration: Histogram;

  // Observable Gauges
  private activeRedirectsCount = 0;

  onModuleInit() {
    this.meter = getMeter(MeterCategory.APPLICATION);

    // Initialize counters
    this.redirectCounter = this.meter.createCounter('redirects.total', {
      description: 'Total number of redirects',
    });
    this.cacheHitCounter = this.meter.createCounter('cache.hits', {
      description: 'Number of cache hits',
    });
    this.cacheMissCounter = this.meter.createCounter('cache.misses', {
      description: 'Number of cache misses',
    });
    this.clickEventPublishedCounter = this.meter.createCounter(
      'click_events.published',
      {
        description: 'Number of click events successfully published',
      },
    );
    this.clickEventFailedCounter = this.meter.createCounter(
      'click_events.failed',
      {
        description: 'Number of click events failed to publish',
      },
    );
    this.notFoundCounter = this.meter.createCounter('shortcodes.not_found', {
      description: 'Shortcode not found errors',
    });

    // Initialize histograms
    this.redirectDuration = this.meter.createHistogram('redirect.duration', {
      description: 'Duration of redirect operations',
    });
    this.cacheResolveDuration = this.meter.createHistogram(
      'cache.resolve.duration',
      {
        description: 'Duration of cache resolve operations',
      },
    );

    // Initialize observable gauge
    const activeRedirectsGauge = this.meter.createObservableGauge(
      'redirects.active',
      {
        description: 'Currently active redirect requests',
      },
    );

    activeRedirectsGauge.addCallback((observableResult) => {
      observableResult.observe(this.activeRedirectsCount);
    });
  }

  incrementRedirects(status: 'success' | 'error') {
    this.redirectCounter.add(1, { status });
  }

  incrementCacheHits() {
    this.cacheHitCounter.add(1);
  }

  incrementCacheMisses() {
    this.cacheMissCounter.add(1);
  }

  incrementClickEventPublished() {
    this.clickEventPublishedCounter.add(1);
  }

  incrementClickEventFailed() {
    this.clickEventFailedCounter.add(1);
  }

  incrementNotFound() {
    this.notFoundCounter.add(1);
  }

  recordRedirectDuration(durationMs: number, status: 'success' | 'error') {
    this.redirectDuration.record(durationMs, { status });
  }

  recordCacheResolveDuration(durationMs: number) {
    this.cacheResolveDuration.record(durationMs);
  }

  startRedirectTracking() {
    this.activeRedirectsCount++;
  }

  stopRedirectTracking() {
    this.activeRedirectsCount--;
  }
}
