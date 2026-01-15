export const RABBITMQ_CONSTANTS = {
  EXCHANGE: 'cache.events',
  QUEUE: 'cache.invalidation',
  ROUTING_KEYS: {
    SHORTENED_URL_CREATED: 'shortened_url.created',
    SHORTENED_URL_DELETED: 'shortened_url.deleted',
    SHORTENED_URL_UPDATED: 'shortened_url.updated',
  },
};
