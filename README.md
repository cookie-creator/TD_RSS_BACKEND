## Backend features
Developed using TDD principles
Spent 49 hours to implement all features
### Backend features
- Implemented feeding from `https://lifehacker.com/feed/rss` using job what scheduled running every 1 hour.
- Implemented Unit Tests based on Pest
- Added Request-docs lib to create API requests for developing using Postman
- Created CRUD for Posts, Categories, Notifications
- Created Admin UI SPA based on Vue.js
- Auth for Admin UI used JWT Auth based on Bearer Token
- For the Posts entity have been created filter based on Search,Category,Sorting and Pagination
- Implemented fully functioning Posts, Categories, Notification pages

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
