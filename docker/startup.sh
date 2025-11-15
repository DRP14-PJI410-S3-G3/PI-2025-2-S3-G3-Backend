#!/bin/sh

#Start Redis
redis-server --daemonize yes

#Start Web Server
sed -i "s,LISTEN_PORT,$PORT,g" /etc/nginx/nginx.conf
php-fpm -D
nginx
