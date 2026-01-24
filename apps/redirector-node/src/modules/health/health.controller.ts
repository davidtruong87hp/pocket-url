import { Controller, Get } from '@nestjs/common';

@Controller('api/health')
export class HealthController {
  @Get()
  getHealth() {
    try {
      return {
        status: 'ok',
        timestamp: new Date().toISOString(),
        service: 'pocket-url-redirector',
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
