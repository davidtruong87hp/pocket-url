import http from 'k6/http';
import { check, sleep } from 'k6';
import { BASE_URL, getRandomShortcode } from './config.js';

// Stress test - find the breaking point
// Gradually increases load until system starts failing
export const options = {
  stages: [
    { duration: '2m', target: 100 },    // Normal load
    { duration: '2m', target: 200 },    // Increase
    { duration: '2m', target: 300 },    // Keep increasing
    { duration: '2m', target: 400 },    // Push harder
    { duration: '2m', target: 500 },    // Find the limit
    { duration: '2m', target: 0 },      // Recovery
  ],
  thresholds: {
    // Don't fail the test - we want to see what breaks
    http_req_duration: ['p(95)<1000'],
  },
};

export default function () {
  const shortcode = getRandomShortcode()
  const response = http.get(`${BASE_URL}/${shortcode}`, {
    redirects: 0,
  });
  
  check(response, {
    'status is valid': (r) => r.status >= 200 && r.status < 600,
    'not timeout': (r) => r.status !== 0,
  });
  
  sleep(Math.random());
}

export function handleSummary(data) {
  console.log('\n💪 Stress Test Results:');
  console.log(`   Total Requests: ${data.metrics.http_reqs.values.count}`);
  console.log(`   Max RPS: ${data.metrics.http_reqs.values.rate.toFixed(2)}`);
  console.log(`   Avg Duration: ${data.metrics.http_req_duration.values.avg.toFixed(2)}ms`);
  console.log(`   P95 Duration: ${data.metrics.http_req_duration.values['p(95)'].toFixed(2)}ms`);
  console.log(`   P99 Duration: ${data.metrics.http_req_duration.values['p(99)'].toFixed(2)}ms`);
  console.log(`   Error Rate: ${(data.metrics.http_req_failed.values.rate * 100).toFixed(2)}%`);
  console.log('\n📊 Check Grafana for detailed metrics: http://localhost:9091\n');
  
  return {
    'stdout': '',
  };
}