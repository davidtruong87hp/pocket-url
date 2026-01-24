import http from 'k6/http'
import {check, sleep} from 'k6'
import {BASE_URL, getRandomShortcode} from './config.js'

// Test configuration
export const options = {
  stages: [
    {duration: '30s', target: 50}, // Ramp up to 50 users
    {duration: '1m', target: 50}, // Stay at 50 users
    {duration: '20s', target: 0}, // Ramp down to 0 users
  ],
  thresholds: {
    http_req_duration: ['p(95)<200'], // 95% of requests should be below 200ms
    http_req_failed: ['rate<0.01'], // Error rate should be less than 1%
  }
}

export default function () {
  const shortcode = getRandomShortcode()

  const params = {
    redirects: 0, // Don't follow redirects
  }

  const response = http.get(`${BASE_URL}/${shortcode}`, params)

  check(response, {
    'status is 301 or 404': (r) => r.status === 301 || r.status === 404,
    'response time < 200ms': (r) => r.timings.duration < 200,
    'has Location header': (r) => r.status === 301 ? r.headers['Location'] !== undefined : true
  })

  // Think time (simulate user behavior)
  sleep(Math.random() * 2)
}