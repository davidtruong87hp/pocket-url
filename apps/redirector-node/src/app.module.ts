import { Module } from '@nestjs/common';
import { AppController } from './app.controller';
import { ShortenerClient } from './shortener/shortener.service';

@Module({
  imports: [],
  controllers: [AppController],
  providers: [ShortenerClient],
})
export class AppModule {}
