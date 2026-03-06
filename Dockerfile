FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    curl \
    zip \
    unzip \
    git \
    openjdk-17-jdk \
    wget \
    libzip-dev \
    && docker-php-ext-install zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /opt
RUN wget https://dl.google.com/android/repository/commandlinetools-linux-9477386_latest.zip && \
    unzip commandlinetools-linux-*.zip && \
    mkdir -p android-sdk/cmdline-tools && \
    mv cmdline-tools android-sdk/cmdline-tools/latest && \
    rm commandlinetools-linux-*.zip

ENV ANDROID_SDK_ROOT=/opt/android-sdk
ENV PATH=$PATH:$ANDROID_SDK_ROOT/cmdline-tools/latest/bin:$ANDROID_SDK_ROOT/platform-tools

RUN yes | sdkmanager --licenses && \
    sdkmanager "platform-tools" "platforms;android-33" "build-tools;30.0.3"

WORKDIR /var/www/html

RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html/builds /var/www/html/output

EXPOSE 9000
