#!/bin/bash

set -e

psql -v ON_ERROR_STOP=1 --username "$POSTGRES_USER" --dbname "$POSTGRES_DB" <<-EOSQL
  -- Create users first
  DO \$\$
  BEGIN
    IF NOT EXISTS (SELECT FROM pg_catalog.pg_roles WHERE rolname = '${POSTGRES_USER}') THEN
      CREATE USER ${POSTGRES_USER} WITH PASSWORD '${POSTGRES_PASSWORD}';
    END IF;
  END
  \$\$;

  -- Create database and grant privileges
  SELECT 'CREATE DATABASE ${POSTGRES_DB}'
  WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = '${POSTGRES_DB}')\gexec

  GRANT ALL PRIVILEGES ON DATABASE ${POSTGRES_DB} TO ${POSTGRES_USER};

  -- Repeat for testing database
  DO \$\$
  BEGIN
    IF NOT EXISTS (SELECT FROM pg_catalog.pg_roles WHERE rolname = '${POSTGRES_USER}_testing') THEN
      CREATE USER ${POSTGRES_USER}_testing WITH PASSWORD '${POSTGRES_PASSWORD}';
    END IF;
  END
  \$\$;

  -- Create database and grant privileges
  SELECT 'CREATE DATABASE ${POSTGRES_DB}_testing'
  WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = '${POSTGRES_DB}_testing')\gexec

  GRANT ALL PRIVILEGES ON DATABASE ${POSTGRES_DB}_testing TO ${POSTGRES_USER}_testing;
EOSQL

echo "✅ Databases and users created successfully!"
