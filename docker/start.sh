#!/bin/sh
php-fpm -D
echo "php-fpm started"
nginx -t && nginx -g "daemon off;" || echo "nginx failed to start"