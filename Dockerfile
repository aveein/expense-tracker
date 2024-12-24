# use PHP 8.2
FROM php:8.2-fpm

# Install common php extension dependencies
RUN apt-get update && apt-get install -y \
    libfreetype-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    zlib1g-dev \
    libzip-dev \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install zip \
    && docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable pdo_mysql


# Set the working directory
COPY . /var/www/app

WORKDIR /var/www/app

RUN chown -R www-data:www-data /var/www/app \
    && chmod -R 775 /var/www/app/storage


# install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# copy composer.json to workdir & install dependencies
COPY composer.json ./

RUN composer update



# Copy the entrypoint script into the container
COPY entrypoint.sh /entrypoint.sh

# Make sure the script is executable
RUN chmod +x /entrypoint.sh


# Set the custom entrypoint
ENTRYPOINT ["/entrypoint.sh"]

# Set the default command to run php-fpm
CMD ["php-fpm"]
