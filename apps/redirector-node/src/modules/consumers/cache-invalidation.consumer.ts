/* eslint-disable @typescript-eslint/no-unsafe-assignment, @typescript-eslint/no-unsafe-call, @typescript-eslint/no-unsafe-member-access */

import { Controller, Logger } from '@nestjs/common';
import { CacheService } from '../cache/cache.service';
import { Ctx, EventPattern, Payload, RmqContext } from '@nestjs/microservices';
import type { CacheInvalidationEvent } from 'src/common/interfaces/cache-event.interface';
import { CacheEventType } from 'src/common/enums/cache-event-type.enum';

@Controller()
export class CacheInvalidationConsumer {
  private readonly logger = new Logger(CacheInvalidationConsumer.name);

  constructor(private readonly cacheService: CacheService) {}

  @EventPattern('cache.invalidation')
  async handleCacheInvalidation(
    @Payload() event: CacheInvalidationEvent,
    @Ctx() context: RmqContext,
  ) {
    this.logger.log(
      `Received cache invalidation event: ${JSON.stringify(event)}`,
    );

    try {
      switch (event.type) {
        case CacheEventType.SHORTENED_URL_CREATED:
        case CacheEventType.SHORTENED_URL_UPDATED:
          await this.handleShortenedUrlUpdated(event);
          break;
        case CacheEventType.SHORTENED_URL_DELETED:
          await this.handleShortenedUrlDeleted(event);
          break;
      }

      this.acknowledgeMessage(context);
    } catch (error) {
      this.logger.error(
        `Error processing cache invalidation event: ${error instanceof Error ? error.message : 'Unknown error'}`,
      );
      this.rejectMessage(context);
    }
  }

  private async handleShortenedUrlDeleted(event: CacheInvalidationEvent) {
    if (event.shortcode) {
      await this.cacheService.delete(event.shortcode);
    }
  }

  private async handleShortenedUrlUpdated(event: CacheInvalidationEvent) {
    if (event.shortcode) {
      await this.cacheService.set(event.shortcode, {
        destinationUrl: event.metadata?.original_url as string,
        metadata: {
          shortCode: event.shortcode,
          shortUrl: event.metadata?.short_url || '',
          title: event.metadata?.title || '',
          createdAt: event.metadata?.created_at || '',
        },
      });
    }
  }

  private acknowledgeMessage(context: RmqContext) {
    const channel = context.getChannelRef();
    const originalMsg = context.getMessage();
    channel.ack(originalMsg);
  }

  private rejectMessage(context: RmqContext) {
    const channel = context.getChannelRef();
    const originalMsg = context.getMessage();
    channel.nack(originalMsg, false, false);
  }
}
