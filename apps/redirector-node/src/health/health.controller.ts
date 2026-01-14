import { Controller, Get } from '@nestjs/common';
import { CacheService } from 'src/cache/cache.service';

@Controller('api/health')
export class HealthController {
  constructor(private readonly cacheService: CacheService) {}

  @Get()
  async getHealth() {
    try {
      const cacheStats = await this.cacheService.getStats();

      return {
        status: 'ok',
        timestamp: new Date().toISOString(),
        service: 'pocket-url-redirector',
        cache: {
          hits: cacheStats.hits,
          misses: cacheStats.misses,
          hitRate: cacheStats.hitRate,
          connected: !!cacheStats,
        },
      };
    } catch (error) {
      return {
        status: 'error',
        timestamp: new Date().toISOString(),
        service: 'pocket-url-redirector',
        error: error instanceof Error ? error.message : 'Unknown error',
      };
    }
  }
}
