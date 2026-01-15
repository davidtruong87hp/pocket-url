import { Controller, Get, Res, Param } from '@nestjs/common';
import type { Response } from 'express';

import { ShortenerClient } from './modules/shortener/shortener.service';
import { ShortcodeNotFoundException } from './common/exceptions';

@Controller()
export class AppController {
  constructor(private readonly shortenerClient: ShortenerClient) {}

  @Get(':shortcode')
  async redirect(@Param('shortcode') shortcode: string, @Res() res: Response) {
    const result = await this.shortenerClient.resolve(shortcode);

    if (!result) {
      throw new ShortcodeNotFoundException(shortcode);
    }

    return res.redirect(301, result.destinationUrl);
  }
}
