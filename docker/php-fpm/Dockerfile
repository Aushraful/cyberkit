# Get the specified image from Registry
FROM php:8.2-fpm

ARG USR
ARG UID
ARG GID
ARG APP_ENV

# Setting up the environment variable
ENV APP_ENV=APP_ENV

# Install system dependencies
RUN apt-get update && apt-get install -y \
	git \
	curl \
	libpng-dev \
	libonig-dev \
	libxml2-dev \
	zip \
	unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install mbstring exif pcntl bcmath gd pdo opcache

# Install Xdebug
RUN yes | pecl install xdebug

# Install PHP extensions for mysql
RUN docker-php-ext-install pdo_mysql

# php-fpm config
COPY ./docker/php-fpm/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

# php config
COPY ./docker/php-fpm/php.ini /usr/local/etc/php/php.ini

# xdebug config
COPY ./docker/php-fpm/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# opcache config
COPY ./docker/php-fpm/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Composer install
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

# setup config
COPY ./docker/php-fpm/setup.sh /setup.sh

RUN chmod +x /setup.sh

# Create a group and a user
RUN groupadd --gid $GID $USR && \
	useradd --uid $UID --gid $GID --create-home --shell /bin/bash --no-log-init $USR

# Set working directory
WORKDIR /var/www/cyberkit

# Copy application files (adjust the path as necessary)
COPY ./src /var/www/cyberkit

# Switch to the non-root user
USER $USR

CMD [ "bash", "-c", "/setup.sh && php-fpm" ]
