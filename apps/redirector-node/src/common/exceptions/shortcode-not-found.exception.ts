import { NotFoundException } from '@nestjs/common';

export class ShortcodeNotFoundException extends NotFoundException {
  constructor(public shortcode: string) {
    super(`Shortcode ${shortcode} not found`);
  }
}
