FROM php:8.2-apache

# Instala dependências e habilita pdo_pgsql
RUN apt-get update && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql && \
    a2enmod rewrite && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Copia o projeto para o diretório público do Apache
COPY . /var/www/html/
WORKDIR /var/www/html/

# Ajustes de permissões (se necessário)
RUN chown -R www-data:www-data /var/www/html/

EXPOSE 80
# Use apache2ctl com caminho absoluto para evitar 'command not found' em alguns ambientes
CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]
