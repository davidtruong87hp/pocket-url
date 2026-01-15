import { CacheEventType } from '../enums/cache-event-type.enum';

export interface CacheInvalidationEvent {
  type: CacheEventType;
  shortcode?: string;
  timestamp: Date;
  metadata?: Record<string, string>;
}
