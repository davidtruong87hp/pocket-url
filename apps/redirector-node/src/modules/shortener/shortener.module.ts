import { Module } from '@nestjs/common';
import { ShortenerClient } from './shortener.service';

@Module({
  providers: [ShortenerClient],
  exports: [ShortenerClient],
})
export class ShortenerModule {}
