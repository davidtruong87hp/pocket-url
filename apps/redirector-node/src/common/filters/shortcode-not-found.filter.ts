import {
  ArgumentsHost,
  Catch,
  ExceptionFilter,
  HttpStatus,
} from '@nestjs/common';
import { Response } from 'express';
import { ShortcodeNotFoundException } from '../exceptions';

@Catch(ShortcodeNotFoundException)
export class ShortcodeNotFoundFilter implements ExceptionFilter {
  catch(exception: ShortcodeNotFoundException, host: ArgumentsHost) {
    const ctx = host.switchToHttp();
    const response = ctx.getResponse<Response>();

    response.status(HttpStatus.NOT_FOUND).render('errors/404', {
      title: 'Link Not Found',
      shortcode: exception.shortcode,
      homeUrl: '/',
    });
  }
}
