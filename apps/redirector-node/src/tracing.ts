import { PrometheusExporter } from '@opentelemetry/exporter-prometheus';
import { NodeSDK } from '@opentelemetry/sdk-node';
import { getNodeAutoInstrumentations } from '@opentelemetry/auto-instrumentations-node';

const ENABLE_METRICS = process.env.ENABLE_METRICS === 'true';

if (ENABLE_METRICS) {
  console.log('🔍 Initializing OpenTelemetry...');

  const prometheusExporter = new PrometheusExporter({
    port: 9464,
    endpoint: '/metrics',
  });

  const sdk = new NodeSDK({
    serviceName: 'pocket-url-redirector',
    metricReaders: [prometheusExporter],
    instrumentations: [
      getNodeAutoInstrumentations({
        '@opentelemetry/instrumentation-http': {
          ignoreIncomingRequestHook: (req) => {
            return req.url === '/api/health' || req.url === '/metrics';
          },
        },
      }),
    ],
  });

  sdk.start();

  console.log('✅ OpenTelemetry initialized');
  console.log('📊 Metrics available at http://localhost:9464/metrics');

  // Graceful shutdown
  process.on('SIGTERM', () => {
    sdk
      .shutdown()
      .then(() => console.log('OpenTelemetry shut down'))
      .catch((error) =>
        console.error('Error shutting down OpenTelemetry', error),
      )
      .finally(() => process.exit(0));
  });
} else {
  console.log('⚠️  OpenTelemetry disabled (ENABLE_METRICS=false)');
}
