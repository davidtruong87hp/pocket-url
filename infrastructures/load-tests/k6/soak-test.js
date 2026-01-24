import http from 'k6/http';
import { check, sleep } from 'k6';
import { BASE_URL, getRandomShortcode } from './config.js';

// Soak test - sustained load over time
// Tests for memory leaks, degradation, etc.
export const options = {
  stages: [
    { duration: '2m', target: 50 },     // Ramp up
    { duration: '30m', target: 50 },    // Stay at 50 users for 30 minutes
    { duration: '2m', target: 0 },      // Ramp down
  ],
  thresholds: {
    http_req_duration: ['p(95)<200'],
    http_req_failed: ['rate<0.01'],
  },
};


export default function () {
  const shortcode = getRandomShortcode()
  
  const response = http.get(`${BASE_URL}/${shortcode}`, {
    redirects: 0,
    headers: {
      'User-Agent': 'k6-soak-test',
    },
  });
  
  check(response, {
    'status is 301 or 404': (r) => r.status === 301 || r.status === 404,
    'response time stable': (r) => r.timings.duration < 300,
  });
  
  sleep(2 + Math.random() * 2); // 2-4 seconds think time
}

export function handleSummary(data) {
  console.log('\n⏱️  Soak Test Results (30min sustained load):');
  console.log(`   Total Requests: ${data.metrics.http_reqs.values.count}`);
  console.log(`   Avg RPS: ${data.metrics.http_reqs.values.rate.toFixed(2)}`);
  console.log(`   Avg Duration: ${data.metrics.http_req_duration.values.avg.toFixed(2)}ms`);
  console.log(`   P95 Duration: ${data.metrics.http_req_duration.values['p(95)'].toFixed(2)}ms`);
  console.log(`   Error Rate: ${(data.metrics.http_req_failed.values.rate * 100).toFixed(2)}%`);
  console.log('\n✅ If error rate and latency stayed stable, no memory leaks detected!\n');
  
  return {
    'stdout': '',
  };
}