import { NestFactory } from '@nestjs/core';
import { AppModule } from './app.module';
import { join } from 'node:path';
import { NestExpressApplication } from '@nestjs/platform-express';
import { ShortcodeNotFoundFilter } from './common/filters';
import { MicroserviceOptions, Transport } from '@nestjs/microservices';

async function bootstrap() {
  const app = await NestFactory.create<NestExpressApplication>(AppModule);

  app.setBaseViewsDir(join(__dirname, '..', 'views'));
  app.setViewEngine('hbs');

  app.useGlobalFilters(new ShortcodeNotFoundFilter());

  app.connectMicroservice<MicroserviceOptions>({
    transport: Transport.RMQ,
    options: {
      urls: [process.env.RABBITMQ_URL || 'amqp://localhost:5672'],
      queue: 'cache.invalidation',
      queueOptions: {
        durable: true,
      },
      noAck: false,
      prefetchCount: 10,
    },
  });

  await app.startAllMicroservices();
  await app.listen(process.env.PORT ?? 3000);

  console.log(`Application is running on: ${await app.getUrl()}`);
}
bootstrap();
