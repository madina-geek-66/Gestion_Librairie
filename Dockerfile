FROM php:8.2-fpm

# Arguments définis dans docker-compose.yml
ARG user
ARG uid

# Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \  # Dépendance pour PostgreSQL
    zip \
    unzip \
    nodejs \
    npm

# Nettoyer le cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Installer les extensions PHP, avec pdo_pgsql pour PostgreSQL
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Créer un utilisateur système pour exécuter les commandes Composer et Artisan
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Définir le répertoire de travail
WORKDIR /var/www

# Copier les fichiers de l'application
COPY . /var/www

# Installer les dépendances
RUN composer install
RUN npm install && npm run build

# Définir les permissions
RUN chown -R $user:$user /var/www
USER $user

EXPOSE 9000
CMD ["php-fpm"]
