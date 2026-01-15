import { Module } from '@nestjs/common';
import { AppController } from './app.controller';
import { CacheRedisModule } from './modules/cache/cache.module';
import { ConfigModule } from '@nestjs/config';
import { RabbitMQModule } from './modules/rabbitmq/rabbitmq.module';
import { ConsumersModule } from './modules/consumers/consumers.module';
import { HealthModule } from './modules/health/health.module';
import { ShortenerModule } from './modules/shortener/shortener.module';

@Module({
  imports: [
    ConfigModule.forRoot({ isGlobal: true }),
    CacheRedisModule.forRootAsync(),
    RabbitMQModule,
    ConsumersModule,
    HealthModule,
    ShortenerModule,
  ],
  controllers: [AppController],
})
export class AppModule {}
