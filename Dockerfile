# Use Debian Bullseye Slim as the base image
FROM debian:bullseye-slim

# Set non-interactive mode for apt-get
ENV DEBIAN_FRONTEND=noninteractive

# Install PHP 7.4 CLI, required PHP extensions, Git, and utilities
RUN apt-get update && \
    apt-get install -y \
      php7.4-cli \
      php7.4-mbstring \
      php7.4-xml \
      php7.4-curl \
      php7.4-gd \
      php7.4-zip \
      php7.4-mysql \
      git \
      curl \
      unzip \
      ca-certificates \
      nano \
      procps \
      rsync \
      inotify-tools \
      netcat && \
    rm -rf /var/lib/apt/lists/*

# Install Node.js and npm (using NodeSource for Node.js 14.x)
RUN apt-get update && \
    apt-get install -y curl gnupg && \
    curl -fsSL https://deb.nodesource.com/setup_14.x | bash - && \
    apt-get install -y nodejs && \
    rm -rf /var/lib/apt/lists/*

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php && \
    HASH="$(curl -sS https://composer.github.io/installer.sig)" && \
    php -r "if (hash_file('SHA384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); exit(1); }" && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm composer-setup.php

# Set the working directory inside the container
WORKDIR /app

# Copy repository files to /app (make sure the Docker build context includes your repo)
COPY . .

# Keep the container running with a bash shell
CMD ["bash"]