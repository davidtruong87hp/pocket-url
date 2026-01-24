import http from 'k6/http';
import { check, sleep } from 'k6';
import { BASE_URL, getRandomShortcode } from './config.js';

// Spike test - sudden burst of traffic
// Tests how the system handles sudden traffic spikes
export const options = {
  stages: [
    { duration: '10s', target: 10 },    // Low load
    { duration: '10s', target: 500 },   // SPIKE! Jump to 500 users
    { duration: '30s', target: 500 },   // Stay at 500
    { duration: '10s', target: 10 },    // Drop back down
    { duration: '10s', target: 0 },     // Ramp down to 0
  ],
  thresholds: {
    http_req_duration: ['p(95)<500'],   // More lenient during spike
    http_req_failed: ['rate<0.1'],      // Allow up to 10% errors during spike
  },
};

export default function () {
  const shortcode = getRandomShortcode()
  
  const response = http.get(`${BASE_URL}/${shortcode}`, {
    redirects: 0,
    headers: {
      'User-Agent': 'k6-spike-test',
      'Referer': 'https://viral-tweet.example.com',
    },
  });
  
  check(response, {
    'status is 301 or 404 or 503': (r) => 
      r.status === 301 || r.status === 404 || r.status === 503,
  });
  
  // Minimal think time during spike (users clicking rapidly)
  sleep(Math.random() * 0.5);
}

export function handleSummary(data) {
  console.log('\n🔥 Spike Test Results:');
  console.log(`   Peak RPS: ${data.metrics.http_reqs.values.rate.toFixed(2)}`);
  console.log(`   P95 Latency: ${data.metrics.http_req_duration.values['p(95)'].toFixed(2)}ms`);
  console.log(`   Error Rate: ${(data.metrics.http_req_failed.values.rate * 100).toFixed(2)}%`);
  
  return {
    'stdout': '',
  };
}