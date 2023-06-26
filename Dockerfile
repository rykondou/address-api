# ベースイメージを指定
FROM php:8.1-fpm

# コンテナ内の作業ディレクトリを指定
WORKDIR /app

# 必要なパッケージのインストール
RUN apt-get update && \
    apt-get install -y \
    zip \
    unzip \
    curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath opcache

# Composerのインストール
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Laravelの依存パッケージをインストール
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader

# アプリケーションのファイルをコンテナ内にコピー
COPY . .

# キャッシュのクリアとアプリケーションキーの生成
RUN composer dump-autoload --no-scripts --optimize && \
    php artisan optimize

# ポートのエクスポートは不要です（Renderが自動的に設定します）

# コンテナ起動時にPHP-FPMを起動
CMD ["php-fpm"]
