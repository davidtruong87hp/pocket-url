import { OnModuleInit } from '@nestjs/common';
import { Client, type ClientGrpc } from '@nestjs/microservices';
import { firstValueFrom } from 'rxjs';
import { grpcClientOptions } from './grpc-client.options';
import { ShortenerService } from './shortener.interface';

export class ShortenerClient implements OnModuleInit {
  @Client(grpcClientOptions)
  private readonly client: ClientGrpc;

  private shortenerService: ShortenerService;

  onModuleInit() {
    this.shortenerService =
      this.client.getService<ShortenerService>('ShortenerService');
  }

  async resolve(shortcode: string): Promise<string | null> {
    try {
      console.log('shortcode: ', shortcode);

      const result = await firstValueFrom(
        this.shortenerService.resolveShortcode({ shortcode }),
      );

      console.log(result);

      if (!result) {
        return null;
      }

      return result.success ? result.destinationUrl : null;
    } catch (error) {
      console.error('gRPC error: ', error);

      return null;
    }
  }
}
