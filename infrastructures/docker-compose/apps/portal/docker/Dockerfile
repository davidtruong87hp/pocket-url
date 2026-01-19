FROM node:22-alpine AS base

# Enable Corepack to use pnpm
RUN corepack enable

WORKDIR /app

FROM base AS development

# Copy package files
COPY apps/portal/package.json apps/portal/pnpm-lock.yaml ./

# Install dependencies
RUN pnpm i

COPY apps/portal ./

EXPOSE 3000

ENV NODE_ENV=development

CMD ["pnpm", "run", "dev"]
