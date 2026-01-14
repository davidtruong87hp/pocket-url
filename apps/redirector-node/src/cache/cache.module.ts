import KeyvRedis from '@keyv/redis';
import { DynamicModule, Global, Module } from '@nestjs/common';
import Keyv from 'keyv';
import { CacheService } from './cache.service';

export const CACHE_CLIENT = 'CACHE_CLIENT';

@Global()
@Module({})
export class CacheRedisModule {
  static forRootAsync(): DynamicModule {
    return {
      module: CacheRedisModule,
      providers: [
        {
          provide: CACHE_CLIENT,
          useFactory: async () => {
            const redisUrl = process.env.REDIS_URL || 'redis://localhost:6379';

            console.log('Connecting to Redis: ', redisUrl);

            const store = new KeyvRedis(redisUrl);
            const keyv = new Keyv({
              store,
              ttl: 10 * 60 * 1000, // Default TTL: 10 minutes (in milliseconds)
            });

            // Test connection
            keyv.on('error', (error) => {
              console.error('Error connecting to Redis: ', error);
            });

            // Verify connection
            await keyv.set('test:connection', 'ok', 1000);
            const test = await keyv.get<string>('test:connection');

            if (test === 'ok') {
              console.log('Redis connected successfully');
            }

            return keyv;
          },
        },
        CacheService,
      ],
      exports: [CACHE_CLIENT, CacheService],
    };
  }
}
