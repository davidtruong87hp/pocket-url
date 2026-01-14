import { Module } from '@nestjs/common';
import { AppController } from './app.controller';
import { ShortenerClient } from './shortener/shortener.service';
import { CacheRedisModule } from './cache/cache.module';
import { ConfigModule } from '@nestjs/config';

@Module({
  imports: [
    ConfigModule.forRoot({ isGlobal: true }),
    CacheRedisModule.forRootAsync(),
  ],
  controllers: [AppController],
  providers: [ShortenerClient],
})
export class AppModule {}
