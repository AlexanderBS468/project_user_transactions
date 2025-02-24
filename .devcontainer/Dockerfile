FROM mcr.microsoft.com/devcontainers/php:1-8.2-bullseye

# Обновляем пакеты и устанавливаем зависимости для intl
RUN apt-get update && apt-get install -y libicu-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl