# Expense Management

## Requirement
#### PHP Version: 8.5.5
#### Composer Version: 2.9.7

## Installation

```bash
git clone https://github.com/ajaycalicut17/expense-management.git
```

```bash
cd expense-management
```

```bash
composer install
```

```bash
cp .env.example .env
```

```bash
php artisan key:generate
```

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

```bash
php artisan migrate --seed
```

```bash
php artisan serve
```

```bash
Login URL: http://127.0.0.1:8000
```
## For Testing

```bash
php artisan test
```