import http from 'k6/http';
import { check, sleep } from 'k6';
import {
  BASE_URL,
  getRandomShortcode,
  getRandomReferrer,
  getRandomUserAgent
} from './config.js';

// Realistic load test with varied traffic sources
export const options = {
  stages: [
    { duration: '1m', target: 100 },   // Ramp up to 100 users
    { duration: '3m', target: 100 },   // Stay at 100 users for 3 minutes
    { duration: '1m', target: 0 },     // Ramp down
  ],
  thresholds: {
    http_req_duration: ['p(95)<200', 'p(99)<500'],
    http_req_failed: ['rate<0.05'],
  },
};

export default function () {
  // Pick random data
  const shortcode = getRandomShortcode()
  const referrer = getRandomReferrer()
  const userAgent = getRandomUserAgent()
  
  // Set headers
  const params = {
    redirects: 0,
    headers: {
      'User-Agent': userAgent,
    },
  };
  
  // Add referrer if it exists (simulate direct vs referred traffic)
  if (referrer) {
    params.headers['Referer'] = referrer;
  }
  
  // Make request
  const response = http.get(`${BASE_URL}/${shortcode}`, params);
  
  // Check response
  check(response, {
    'status is 301 or 404': (r) => r.status === 301 || r.status === 404,
    'has location header if 301': (r) => {
      if (r.status === 301) {
        return r.headers['Location'] !== undefined;
      }
      return true;
    },
  });
  
  // Realistic think time (people don't click instantly)
  sleep(Math.random() * 3 + 1); // 1-4 seconds
}

// Custom summary to show at the end
export function handleSummary(data) {
  return {
    'stdout': textSummary(data, { indent: ' ', enableColors: true }),
  };
}

function textSummary(data, options) {
  const indent = options?.indent || '';
  const enableColors = options?.enableColors || false;
  
  return `
${indent}✅ Test Summary:
${indent}  Total Requests: ${data.metrics.http_reqs.values.count}
${indent}  Requests/sec: ${data.metrics.http_reqs.values.rate.toFixed(2)}
${indent}  Avg Duration: ${data.metrics.http_req_duration.values.avg.toFixed(2)}ms
${indent}  P95 Duration: ${data.metrics.http_req_duration.values['p(95)'].toFixed(2)}ms
${indent}  P99 Duration: ${data.metrics.http_req_duration.values['p(99)'].toFixed(2)}ms
${indent}  Failed Requests: ${(data.metrics.http_req_failed.values.rate * 100).toFixed(2)}%
  `;
}