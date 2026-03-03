FROM php:8.2-fpm

# 必要なパッケージのインストール
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Composerのインストール
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 作業ディレクトリの設定
WORKDIR /var/www/html

# ユーザーの作成（権限問題回避）
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# 権限の設定
COPY --chown=www:www . /var/www/html

USER www

# PHP-FPMの起動
EXPOSE 9000
CMD ["php-fpm"]
