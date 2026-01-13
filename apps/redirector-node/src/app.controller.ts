import { Controller, Get, Res, Param } from '@nestjs/common';
import type { Response } from 'express';

import { ShortenerClient } from './shortener/shortener.service';

@Controller()
export class AppController {
  constructor(private readonly shortenerClient: ShortenerClient) {}

  @Get(':shortcode')
  async redirect(@Param('shortcode') shortcode: string, @Res() res: Response) {
    const url = await this.shortenerClient.resolve(shortcode);

    if (!url) {
      return res.status(404).send('Shortcode not found');
    }

    return res.redirect(url);
  }
}
