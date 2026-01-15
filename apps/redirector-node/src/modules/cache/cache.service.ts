import { Inject, Injectable } from '@nestjs/common';
import { CACHE_CLIENT } from './cache.module';
import Keyv from 'keyv';

export interface CachedShortCode {
  destinationUrl: string;
  metadata: {
    shortCode: string;
    shortUrl: string;
    title: string;
    createdAt: string;
  };
}

@Injectable()
export class CacheService {
  constructor(@Inject(CACHE_CLIENT) private readonly cache: Keyv) {}

  async get(shortcode: string): Promise<CachedShortCode | null> {
    try {
      const cacheKey = `shortcode:${shortcode}`;
      const data = await this.cache.get<CachedShortCode>(cacheKey);

      if (!data) {
        return null;
      }

      return data;
    } catch (error) {
      console.error('Cache get error: ', error);

      return null;
    }
  }

  async set(
    shortcode: string,
    data: CachedShortCode,
    ttl?: number,
  ): Promise<void> {
    try {
      const cacheKey = `shortcode:${shortcode}`;
      const cacheTtl = ttl || 10 * 60 * 1000;

      await this.cache.set(cacheKey, data, cacheTtl);
      console.log(`Cached ${shortcode} for ${cacheTtl}ms`);
    } catch (error) {
      console.error('Cache set error: ', error);
    }
  }

  async delete(shortcode: string): Promise<void> {
    try {
      const cacheKey = `shortcode:${shortcode}`;

      await this.cache.delete(cacheKey);
      console.log(`Invalidated cache for ${shortcode}`);
    } catch (error) {
      console.error('Cache delete error: ', error);
    }
  }

  async recordHit(): Promise<void> {
    const current = (await this.cache.get<number>('stats:hits')) || 0;
    await this.cache.set('stats:hits', current + 1);
  }

  async recordMiss(): Promise<void> {
    const current = (await this.cache.get<number>('stats:misses')) || 0;
    await this.cache.set('stats:misses', current + 1);
  }

  async getStats(): Promise<{ hits: number; misses: number; hitRate: number }> {
    const hits = (await this.cache.get<number>('stats:hits')) || 0;
    const misses = (await this.cache.get<number>('stats:misses')) || 0;
    const total = hits + misses;

    return {
      hits,
      misses,
      hitRate: (hits / total) * 100,
    };
  }
}
