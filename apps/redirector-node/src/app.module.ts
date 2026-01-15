import { Module } from '@nestjs/common';
import { AppController } from './app.controller';
import { ShortenerClient } from './shortener/shortener.service';
import { CacheRedisModule } from './modules/cache/cache.module';
import { ConfigModule } from '@nestjs/config';
import { HealthController } from './health/health.controller';
import { RabbitMQModule } from './modules/rabbitmq/rabbitmq.module';
import { ConsumersModule } from './modules/consumers/consumers.module';

@Module({
  imports: [
    ConfigModule.forRoot({ isGlobal: true }),
    CacheRedisModule.forRootAsync(),
    RabbitMQModule,
    ConsumersModule,
  ],
  controllers: [AppController, HealthController],
  providers: [ShortenerClient],
})
export class AppModule {}
