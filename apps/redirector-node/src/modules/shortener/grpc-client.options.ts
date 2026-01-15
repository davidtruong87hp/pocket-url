import { ClientOptions, Transport } from '@nestjs/microservices';
import { join } from 'node:path';

export const grpcClientOptions: ClientOptions = {
  transport: Transport.GRPC,
  options: {
    package: 'shortener',
    protoPath: join(process.cwd(), 'src/proto/shortener.proto'),
    url: process.env.SHORTENER_GRPC_URL || 'localhost:9001',
  },
};
