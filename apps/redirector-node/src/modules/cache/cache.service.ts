import { Inject, Injectable, Logger } from '@nestjs/common';
import { CACHE_CLIENT } from './cache.module';
import Keyv from 'keyv';
import { RedisMetricsService } from '../metrics/redis-metrics.service';

export interface CachedShortCode {
  destinationUrl: string;
  metadata: {
    shortCode: string;
    shortUrl: string;
    title: string;
    createdAt: string;
    shortDomain?: string;
    ownerId?: string;
  };
}

@Injectable()
export class CacheService {
  private readonly logger = new Logger(CacheService.name);

  constructor(
    @Inject(CACHE_CLIENT) private readonly cache: Keyv,
    private readonly redisMetricsService: RedisMetricsService,
  ) {}

  async get(shortcode: string): Promise<CachedShortCode | null> {
    return this.redisMetricsService.trackOperation('get', async () => {
      try {
        const cacheKey = `shortcode:${shortcode}`;
        const data = await this.cache.get<CachedShortCode>(cacheKey);

        if (!data) {
          return null;
        }

        return data;
      } catch (error) {
        this.logger.error('Cache get error: ', error);

        return null;
      }
    });
  }

  async set(
    shortcode: string,
    data: CachedShortCode,
    ttl?: number,
  ): Promise<void> {
    await this.redisMetricsService.trackOperation('set', async () => {
      try {
        const cacheKey = `shortcode:${shortcode}`;
        const cacheTtl = ttl || 10 * 60 * 1000;

        await this.cache.set(cacheKey, data, cacheTtl);
        this.logger.log(`Cached ${shortcode} for ${cacheTtl}ms`);
      } catch (error) {
        this.logger.error('Cache set error: ', error);
      }
    });
  }

  async delete(shortcode: string): Promise<void> {
    await this.redisMetricsService.trackOperation('delete', async () => {
      try {
        const cacheKey = `shortcode:${shortcode}`;

        await this.cache.delete(cacheKey);
        this.logger.log(`Invalidated cache for ${shortcode}`);
      } catch (error) {
        this.logger.error('Cache delete error: ', error);
      }
    });
  }
}
