# Installation

- Create .env from .env.example in ***root*** directory:

```
cp .env.example .env
cp .env.docker.example .env.docker
```

- Install PHP/composer dependencies

```
make install-php
```

- Run application

```
make up
```

## TODO: Review and update documentation below

### Hints:

- Install entity: `` sudo apt install entity ``
- Install docker and docker-compose:
    1. `` sudo apt install -y docker.io docker-compose ``
    2. `` sudo systemctl enable docker.service ``
    3. `` sudo usermod -a -G docker $(whoami) ``
    4. Reboot system

### Package commands:

- Ide helper:  
  ``php artisan ide-helper:models`` (from app container)

## Documentation

```
All additional documentation you can find at `/docs`:
```

## Tests

- Before local testing please make changes in ``.env.testing``:

```
DB_HOST=mysql-test
```

- First You need to run a container:
  ``make test-up``
- Then You can go to container:  
  ``make test-bash``
- Finally just run:  
  ``php artisan test`` or ``php artisan test --testsuite=Projects --group=project-binding`` (Example)

[Telegram bot](/backend/docs/app-docs/TELEGRAM-BOT.md)
[Chat](/backend/docs/app-docs/CHAT.md)

## Available PEST expectations

```
https://defstudio.github.io/pest-plugin-laravel-expectations/
```
