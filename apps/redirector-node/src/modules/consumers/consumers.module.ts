import { Module } from '@nestjs/common';
import { CacheInvalidationConsumer } from './cache-invalidation.consumer';

@Module({
  controllers: [CacheInvalidationConsumer],
})
export class ConsumersModule {}
