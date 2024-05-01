#!/bin/bash

artisan_path="/var/www/api/artisan"

info() {
  lgreen='\033[1;32m'
  nc='\033[0m'
  printf "${lgreen}[Info] ${@}${nc}\n"
}

parse_and_export_backend_envs () {
    info "Parsing and exporting BackendEnvs to /var/www/api/.env"
    # Assuming BackendEnvs is a comma-separated list of KEY=VALUE pairs
    for env in `echo ${BackendEnvs} | sed 's/\,/ /g'`; do
        echo "${env}" >> /var/www/api/.env
    done
    info "BackendEnvs have been written to /var/www/api/.env"
}

export_secrets () {
    info "Run export secrets"
    for env in `echo ${BackendEnvs} | sed 's/\,/ /g'`; do
        echo "export ${env}" >> /exports.sh
    done
    chmod 700 /exports.sh
    info "Current MAIL_MAILER - ${MAIL_MAILER}"
    info "Current MAIL_FROM_ADDRESS - ${MAIL_FROM_ADDRESS}"
}

set_access () {
   echo "start of logging: `date "+%Y-%m-%d-%H-%M"`" >> /var/www/api/storage/logs/laravel.log
   chown -R www-data:www-data /var/www/api/storage/logs/laravel.log
#    chown -R root:www-data /var/www/api
#    chmod -R ug+rwx /var/www/api/storage /var/www/api/bootstrap/cache
#    chgrp -R www-data /var/www/api/storage /var/www/api/bootstrap/cache
#    chmod -R ug+rwx /var/www/api/storage /var/www/api/bootstrap/cache
}

artisan_action () {
    source /exports.sh

    action=(
        'storage:link'
        'cache:clear'
        'view:clear'
        'optimize'
        'migrate --force'
    )
    cd /var/www/api/
    for action in "${action[@]}"; do
	    info "Executing - php artisan ${action}"
        php "${artisan_path}" ${action}
    done
    cd -
}


main() {
  parse_and_export_backend_envs
  export_secrets
  set_access
  artisan_action
}

main

echo "$@"
if [ "${1:0:1}" = '' ]; then
  /usr/bin/supervisord
else
  eval "$@"
fi
