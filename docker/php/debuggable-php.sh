#!/bin/sh
set -e
enabled_xdebug="/usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini"
disabled_xdebug="/usr/local/etc/php/conf.d/docker-php-ext-xdebug.disabled"
case "$@" in
        *-dxdebug.remote_enable=1*)
            if [ -e "$disabled_xdebug" ]; then
                mv "$disabled_xdebug" "$enabled_xdebug"
            fi
        ;;
        *)
            if [ -e "$enabled_xdebug" ]; then
                mv "$enabled_xdebug" "$disabled_xdebug"
            fi
        ;;
esac
set -- php "$@"
exec "$@"