import { Injectable, Logger, OnModuleInit } from '@nestjs/common';
import { Client, type ClientGrpc } from '@nestjs/microservices';
import { firstValueFrom } from 'rxjs';
import { grpcClientOptions } from './grpc-client.options';
import { ShortenerService } from './shortener.interface';
import { CachedShortCode, CacheService } from 'src/modules/cache/cache.service';
import { MetricsService } from '../metrics/metrics.service';
import { GrpcMetricsService } from '../metrics/grpc-metrics.service';

@Injectable()
export class ShortenerClient implements OnModuleInit {
  @Client(grpcClientOptions)
  private readonly client: ClientGrpc;

  private readonly logger = new Logger(ShortenerClient.name);

  private shortenerService: ShortenerService;

  constructor(
    private readonly cacheService: CacheService,
    private readonly metricsService: MetricsService,
    private readonly grpcService: GrpcMetricsService,
  ) {}

  onModuleInit() {
    this.shortenerService =
      this.client.getService<ShortenerService>('ShortenerService');
  }

  async resolve(shortcode: string): Promise<CachedShortCode | null> {
    const startTime = Date.now();

    try {
      const cached = await this.cacheService.get(shortcode);

      if (cached) {
        this.metricsService.incrementCacheHits();
        const duration = Date.now() - startTime;
        this.metricsService.recordCacheResolveDuration(duration);

        return cached;
      }

      this.metricsService.incrementCacheMisses();

      const result = await this.grpcService.trackCall(
        'resolveShortcode',
        async () => {
          return firstValueFrom(
            this.shortenerService.resolveShortcode({ shortcode }),
          );
        },
      );

      if (!result.success || !result.destinationUrl) {
        return null;
      }

      const cacheData: CachedShortCode = {
        destinationUrl: result.destinationUrl,
        metadata: {
          shortCode: result.metadata.shortCode,
          shortUrl: result.metadata.shortUrl,
          title: result.metadata.title,
          createdAt: result.metadata.createdAt,
          shortDomain: result.metadata.shortDomain,
          ownerId: result.metadata.ownerId,
        },
      };

      await this.cacheService.set(shortcode, cacheData);

      const duration = Date.now() - startTime;
      this.metricsService.recordCacheResolveDuration(duration);

      return cacheData;
    } catch (error) {
      this.logger.error('gRPC error: ', error);

      return null;
    }
  }
}
