#!/bin/sh
set -eu

if [ ! -f .env ]; then
    cp .env.docker.example .env
fi

if ! grep -q '^APP_KEY=base64:' .env; then
    php artisan key:generate --force --ansi
fi

php artisan storage:link --ansi 2>/dev/null || true
php artisan migrate --force --ansi

USER_COUNT="$(php -r 'require "vendor/autoload.php"; $app = require "bootstrap/app.php"; $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class); $kernel->bootstrap(); echo Illuminate\Support\Facades\DB::table("users")->count();')"

if [ "${USER_COUNT:-0}" = "0" ]; then
    php artisan db:seed --force --ansi
else
    echo "Database already contains ${USER_COUNT} users; skipping seed."
fi

php artisan optimize:clear --ansi
