import { metrics, Meter } from '@opentelemetry/api';

const SERVICE_INFO = {
  name: 'redirector',
  version: '1.0.0',
  namespace: 'pocket-url',
};

export enum MeterCategory {
  // Core business logic
  APPLICATION = 'application',

  // NodeJS runtime metrics
  RUNTIME = 'runtime',

  // External dependencies
  REDIS = 'redis',
  GRPC = 'grpc',
  RABBITMQ = 'rabbitmq',
}

interface MeterConfig {
  category: MeterCategory;
  description: string;
  enabled: boolean;
}

class MeterRegistry {
  private meters: Map<MeterCategory, Meter> = new Map();
  private configs: Map<MeterCategory, MeterConfig> = new Map();

  constructor() {
    this.registerAllMeters();
  }

  private registerAllMeters() {
    this.register({
      category: MeterCategory.APPLICATION,
      description: 'Business logic metrics (redirects, cache, clicks)',
      enabled: true,
    });

    this.register({
      category: MeterCategory.RUNTIME,
      description: 'Runtime metrics (memory, CPU, event loop)',
      enabled: true,
    });

    this.register({
      category: MeterCategory.REDIS,
      description: 'Redis/Cache operation metrics',
      enabled: true,
    });

    this.register({
      category: MeterCategory.GRPC,
      description: 'gRPC client/server metrics',
      enabled: true,
    });

    this.register({
      category: MeterCategory.RABBITMQ,
      description: 'RabbitMQ producer/consumer metrics',
      enabled: true,
    });
  }

  private register(config: MeterConfig) {
    this.configs.set(config.category, config);

    if (config.enabled) {
      const meterName = this.buildMeterName(config.category);
      const meter = metrics.getMeter(meterName, SERVICE_INFO.version);
      this.meters.set(config.category, meter);
    }
  }

  /**
   * Build consistent meter name
   * Format: {namespace}.{service}.{category}
   * Example: pocket-url.redirector.application
   */
  private buildMeterName(category: MeterCategory): string {
    return `${SERVICE_INFO.namespace}.${SERVICE_INFO.name}.${category}`;
  }

  getMeter(category: MeterCategory): Meter {
    const meter = this.meters.get(category);

    if (!meter) {
      throw new Error(
        `Meter not found: ${category}. ` +
          `Available meters: ${Array.from(this.meters.keys()).join(', ')}`,
      );
    }

    return meter;
  }

  listMeters(): Array<{
    category: MeterCategory;
    name: string;
    description: string;
    enabled: boolean;
  }> {
    return Array.from(this.configs.entries()).map(([category, config]) => ({
      category,
      name: this.buildMeterName(category),
      description: config.description,
      enabled: config.enabled,
    }));
  }

  getMeterInfo(category: MeterCategory) {
    const config = this.configs.get(category);

    return {
      category,
      name: this.buildMeterName(category),
      version: SERVICE_INFO.version,
      description: config?.description || 'Unknown',
      enabled: config?.enabled || false,
    };
  }

  /**
   * Print all meters (for debugging)
   */
  printMeters() {
    console.log('\n📊 Registered Meters:');
    console.log(
      '═══════════════════════════════════════════════════════════════',
    );

    this.listMeters().forEach(({ category, name, description, enabled }) => {
      const status = enabled ? '✅' : '❌';
      console.log(`${status} ${category.padEnd(15)} → ${name}`);
      console.log(`   ${description}`);
      console.log('');
    });

    console.log(
      '═══════════════════════════════════════════════════════════════\n',
    );
  }
}

/**
 * Singleton instance
 */
export const meterRegistry = new MeterRegistry();

export function getMeter(category: MeterCategory): Meter {
  return meterRegistry.getMeter(category);
}
