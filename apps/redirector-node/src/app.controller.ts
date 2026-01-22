import { Controller, Get, Res, Param, Req } from '@nestjs/common';
import type { Request, Response } from 'express';

import { ShortenerClient } from './modules/shortener/shortener.service';
import { ShortcodeNotFoundException } from './common/exceptions';
import { ClickPublisher } from './modules/analytics/publishers/click.publisher';

@Controller()
export class AppController {
  constructor(
    private readonly shortenerClient: ShortenerClient,
    private readonly clickPublisher: ClickPublisher,
  ) {}

  @Get(':shortcode')
  async redirect(
    @Param('shortcode') shortcode: string,
    @Req() req: Request,
    @Res() res: Response,
  ) {
    if (!shortcode || !/^[a-zA-Z0-9]{6}$/.test(shortcode)) {
      throw new ShortcodeNotFoundException(shortcode);
    }

    const result = await this.shortenerClient.resolve(shortcode);

    if (!result) {
      throw new ShortcodeNotFoundException(shortcode);
    }

    this.clickPublisher
      .publishClickEvent({
        shortcode,
        timestamp: new Date(),
        userAgent: req.headers['user-agent'],
        referer: req.headers.referer,
        ip: req.ip || req.socket.remoteAddress,
        ownerId: result.metadata.ownerId,
      })
      .catch((err) => {
        console.log('Failed to publish click event', err);
      });

    return res.redirect(301, result.destinationUrl);
  }
}
