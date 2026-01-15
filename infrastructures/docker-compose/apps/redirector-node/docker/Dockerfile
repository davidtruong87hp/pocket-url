FROM node:20-alpine AS development

WORKDIR /app

COPY apps/redirector-node/package*.json ./

RUN npm install

COPY apps/redirector-node .

EXPOSE 3000

CMD ["npm", "run", "start:dev"]

FROM node:20-alpine AS production

WORKDIR /app

COPY apps/redirector-node/package*.json ./

RUN npm install --only=production

COPY apps/redirector-node .

RUN npm run build

EXPOSE 3000

CMD ["node", "dist/main"]
