FROM php:8.2-apache

# Instala dependências e habilita pdo_pgsql
RUN apt-get update && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql && \
    a2enmod rewrite && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Configura o nome do servidor
RUN echo 'ServerName localhost' > /etc/apache2/conf-available/servername.conf \
 && a2enconf servername

# Copia o projeto para o diretório público do Apache
COPY . /var/www/html/
WORKDIR /var/www/html/

# Ajustes de permissões (se necessário)
RUN chown -R www-data:www-data /var/www/html/

EXPOSE 80
# Use apache2ctl quando presente; senão, caia para o servidor PHP embutido
CMD ["sh", "-c", "if [ -x /usr/sbin/apache2ctl ]; then exec /usr/sbin/apache2ctl -D FOREGROUND; else exec php -S 0.0.0.0:${PORT:-80} -t /var/www/html; fi"]
