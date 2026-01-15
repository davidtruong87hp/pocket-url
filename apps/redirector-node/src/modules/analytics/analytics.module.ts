import { Module } from '@nestjs/common';
import { ClickPublisher } from './publishers/click.publisher';

@Module({
  providers: [ClickPublisher],
  exports: [ClickPublisher],
})
export class AnalyticsModule {}
