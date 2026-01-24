// Centralized configuration for all k6 tests

import { SharedArray } from 'k6/data';

// Base URL
export const BASE_URL = __ENV.BASE_URL || 'http://localhost:3000';

// Shortcodes to test
// Priority: ENV var > config file > defaults
export const shortcodes = new SharedArray('shortcodes', function () {
  if (__ENV.SHORTCODES) {
    return __ENV.SHORTCODES.split(',');
  }
  
  // Defaults
  return [
    '0BzDeu',
    '0CT7Fp',
    '0CbTzU'
  ];
});

// Referrers for realistic traffic simulation
export const referrers = new SharedArray('referrers', function () {
  if (__ENV.REFERRERS) {
    return __ENV.REFERRERS.split(',');
  }
  
  return [
    'https://google.com/search?q=example',
    'https://twitter.com/status/123456',
    'https://www.facebook.com/',
    'https://www.reddit.com/r/programming',
    'https://news.ycombinator.com/',
    'https://www.linkedin.com/feed',
    '',  // Direct traffic (no referrer)
  ];
});

// User agents for device simulation
export const userAgents = new SharedArray('userAgents', function () {
  return [
    'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.0 Mobile/15E148 Safari/604.1',
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36',
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36',
    'Mozilla/5.0 (Linux; Android 13) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.6045.163 Mobile Safari/537.36',
    'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36',
  ];
});

// Helper functions
export function getRandomShortcode() {
  return shortcodes[Math.floor(Math.random() * shortcodes.length)];
}

export function getRandomReferrer() {
  return referrers[Math.floor(Math.random() * referrers.length)];
}

export function getRandomUserAgent() {
  return userAgents[Math.floor(Math.random() * userAgents.length)];
}