import { Injectable, Logger } from '@nestjs/common';
import { RabbitMQService } from 'src/modules/rabbitmq/rabbitmq.service';
import { ClickEventDto } from '../dto/click-event.dto';

@Injectable()
export class ClickPublisher {
  private readonly logger = new Logger(ClickPublisher.name);
  private readonly exchange = 'analytics.exchange';
  private readonly routingKey = 'click.tracked';

  constructor(private readonly rabbitmqService: RabbitMQService) {}

  async publishClickEvent(event: ClickEventDto): Promise<void> {
    const success = await this.rabbitmqService.publish(
      this.exchange,
      this.routingKey,
      {
        ...event,
        publishedAt: new Date().toISOString(),
      },
    );

    if (success) {
      this.logger.debug('Click event published: ' + event.shortcode);
    } else {
      this.logger.error('Failed to publish click event: ' + event.shortcode);
    }
  }
}
