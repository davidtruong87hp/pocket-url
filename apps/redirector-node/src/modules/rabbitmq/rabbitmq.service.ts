import {
  Injectable,
  Logger,
  OnModuleDestroy,
  OnModuleInit,
} from '@nestjs/common';
import { ConfigService } from '@nestjs/config';
import * as amqp from 'amqplib';

@Injectable()
export class RabbitMQService implements OnModuleInit, OnModuleDestroy {
  private readonly logger = new Logger(RabbitMQService.name);
  private connection: amqp.ChannelModel;
  private channel: amqp.Channel;
  private isConnected = false;

  constructor(private readonly configService: ConfigService) {}

  async onModuleInit() {
    await this.connect();
  }

  async onModuleDestroy() {
    await this.disconnect();
  }

  private async connect() {
    try {
      const rabbitUrl = this.configService.get<string>('RABBITMQ_URL') || '';
      this.connection = await amqp.connect(rabbitUrl);
      this.channel = await this.connection.createChannel();

      this.isConnected = true;
      this.logger.log('Connected to RabbitMQ');

      this.connection.on('error', (error) => {
        this.logger.error('RabbitMQ connection error', error);
        this.isConnected = false;
      });

      this.connection.on('close', () => {
        this.logger.warn('RabbitMQ connection closed, reconnecting...');
        this.isConnected = false;

        setTimeout(() => {
          this.connect();
        }, 5000);
      });
    } catch (error) {
      this.logger.error('Failed to connect to RabbitMQ', error);
      this.isConnected = false;

      setTimeout(() => {
        this.connect();
      }, 5000);
    }
  }

  private async disconnect() {
    try {
      await this.channel?.close();
      await this.connection?.close();
      this.logger.log('RabbitMQ connection closed');
    } catch (error) {
      this.logger.error('Failed to close RabbitMQ connection', error);
    }
  }

  async publish(
    exchange: string,
    routingKey: string,
    message: object,
    options?: amqp.Options.Publish,
  ): Promise<boolean> {
    if (!this.isConnected || !this.channel) {
      this.logger.warn('RabbitMQ connection is not established');
      return false;
    }

    try {
      await this.channel.assertExchange(exchange, 'topic', {
        durable: true,
      });

      const messageBuffer = Buffer.from(JSON.stringify(message));

      this.channel.publish(exchange, routingKey, messageBuffer, {
        persistent: true,
        contentType: 'application/json',
        ...options,
      });

      this.logger.debug(`Message published to ${exchange}/${routingKey}`);
      return true;
    } catch (error) {
      this.logger.error('Failed to publish message', error);
      return false;
    }
  }

  getChannel(): amqp.Channel {
    return this.channel;
  }

  isHealthy(): boolean {
    return this.isConnected;
  }
}
