FROM univesp.pi20250-2-s3g3-backend-base-image AS php

#Configure NGINX
RUN mkdir -p /run/nginx
COPY docker/nginx.conf /etc/nginx/nginx.conf

#Copy PHP Uploads preferences
COPY docker/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

#Copy Application
RUN mkdir -p /app
COPY ./docker /app/docker
COPY ./src /app

#Copy .env
#RUN mv /app/.env.docker /app/.env
RUN mv /app/.env.cloud /app/.env

#Install Composer and Project dependencies
# Composer has been installed on base
# RUN sh -c "wget http://getcomposer.org/composer.phar && chmod a+x composer.phar && mv composer.phar /usr/local/bin/composer"
RUN cd /app && composer install --no-dev
RUN chown -R www-data: /app

#Try Faster
#https://serverfault.com/questions/378280/fastest-way-to-chown-a-whole-device-xfs
#find /home/user/sandbox -type f -print0 | xargs -0 chown outro_usuario:outro_grupo
#find /home/user/sandbox -type d -print0 | xargs -0 chown outro_usuario:outro_grupo
#OR
#find /home/user/sandbox -type f -print0 | xargs -0 -I{} chown -R outro_usuario:outro_grupo {}
#find /home/user/sandbox -type d -print0 | xargs -0 -I{} chown -R outro_usuario:outro_grupo {}

#Start Redis and Server
CMD ["sh", "/app/docker/startup.sh"]
