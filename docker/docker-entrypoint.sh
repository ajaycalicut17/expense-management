#!/bin/bash
set -e

# Generate .env from environment variables if it doesn't exist
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Update .env with runtime environment variables
if [ -n "$APP_ENV" ]; then
    sed -i "s|APP_ENV=.*|APP_ENV=${APP_ENV}|" .env
fi

if [ -n "$APP_DEBUG" ]; then
    sed -i "s|APP_DEBUG=.*|APP_DEBUG=${APP_DEBUG}|" .env
fi

if [ -n "$APP_URL" ]; then
    sed -i "s|APP_URL=.*|APP_URL=${APP_URL}|" .env
fi

if [ -n "$APP_KEY" ]; then
    sed -i "s|APP_KEY=.*|APP_KEY=${APP_KEY}|" .env
fi

if [ -n "$DB_CONNECTION" ]; then
    sed -i "s|DB_CONNECTION=.*|DB_CONNECTION=${DB_CONNECTION}|" .env
fi

if [ -n "$DB_HOST" ]; then
    sed -i "s|DB_HOST=.*|DB_HOST=${DB_HOST}|" .env
fi

if [ -n "$DB_PORT" ]; then
    sed -i "s|DB_PORT=.*|DB_PORT=${DB_PORT}|" .env
fi

if [ -n "$DB_DATABASE" ]; then
    sed -i "s|DB_DATABASE=.*|DB_DATABASE=${DB_DATABASE}|" .env
fi

if [ -n "$DB_USERNAME" ]; then
    sed -i "s|DB_USERNAME=.*|DB_USERNAME=${DB_USERNAME}|" .env
fi

if [ -n "$DB_PASSWORD" ]; then
    sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=${DB_PASSWORD}|" .env
fi

# Generate APP_KEY if not set
if grep -q "^APP_KEY=$" .env; then
    php artisan key:generate
fi

# Run migrations if needed (optional, can be controlled by env var)
if [ "$RUN_MIGRATIONS" = "true" ]; then
    php artisan migrate --force
fi

# Execute the main command
exec "$@"
