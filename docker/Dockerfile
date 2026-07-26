FROM php:8.5-cli

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    supervisor \
    libsqlite3-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

ARG NO_DEV=true
RUN if [ "$NO_DEV" = "false" ]; then \
    apt-get update && apt-get install -y \
    nodejs \
    npm \
    && apt-get clean && rm -rf /var/lib/apt/lists/*; \
    fi

# Install PHP extensions
RUN docker-php-ext-install pdo_sqlite mbstring exif pcntl bcmath gd
RUN if [ "$NO_DEV" = "false" ]; then \
    docker-php-ext-install sockets; \
    fi

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . /var/www/html

# Copy tests directory only for dev builds
RUN if [ "$NO_DEV" = "false" ]; then \
    mkdir -p /var/www/html/tests && \
    cp -r tests/* /var/www/html/tests/; \
    fi

# Copy entrypoint script
COPY docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Install PHP dependencies
RUN if [ "$NO_DEV" = "true" ]; then \
    composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist; \
    else \
    composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist; \
    npm ci && \
    npx playwright install --with-deps; \
    fi

# Create database file
RUN touch database/database.sqlite

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Copy supervisord configuration
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port 80
EXPOSE 80

# Start supervisord via entrypoint
ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
