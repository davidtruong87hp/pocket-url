import { Observable } from 'rxjs';

export interface ResolveShortcodeResponse {
  success: boolean;
  destinationUrl: string;
  error?: string;
  metadata: {
    shortCode: string;
    shortUrl: string;
    title: string;
    createdAt: string;
  };
}

export interface ShortenerService {
  resolveShortcode(data: {
    shortcode: string;
    domain?: string;
  }): Observable<ResolveShortcodeResponse>;
}
