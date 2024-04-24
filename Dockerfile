FROM php:7.3-fpm

ENV MASTER_TZ=Asia/Manila
ENV MASTER_DIR=/var/www

RUN ln -snf /usr/share/zoneinfo/${MASTER_TZ} /etc/localtime && echo ${MASTER_TZ} > /etc/timezone

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y --no-install-recommends apt-utils\
    build-essential \
    default-mysql-client \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    libzip-dev \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    nano \
    unzip \
    git \
    curl

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-install gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy composer.lock and composer.json
# This will allow the container to use a cached version of PHP packages
COPY composer.lock composer.json ${MASTER_DIR}/

# This is included just to bypass errors thrown by composer scripts
COPY database ${MASTER_DIR}/database

RUN composer install --no-interaction --no-plugins --no-scripts

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . ${MASTER_DIR}

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
