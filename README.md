# Products RESTful API - Laravel

Technical challenge solution implementing a RESTful API for products and multi-currency pricing, following SOLID principles, Clean Architecture, and Clean Code standards.

## Tech Stack
- PHP 8.2+
- Laravel 11/12
- MySQL 8.0+
- Swagger / OpenAPI (via L5-Swagger)
- PHPUnit / Pest for Testing

## Getting Started

### 1. Installation
Clone the repository and install dependencies:
```bash
composer install
cp .env.example .env
php artisan key:generate
```

## 2. Database Setup
```bash
DB_CONNECTION=mysql
DB_HOST=[IP_ADDRESS]
DB_PORT=3306
DB_DATABASE=products_api
DB_USERNAME=root
DB_PASSWORD=
```
# Run migrations and seeders
```bash
php artisan migrate
```

## 3. API Documentation
Generate and view the OpenAPI/Swagger interactive documentation:
```bash
php artisan l5-swagger:generate
```
Access the Swagger UI at: http://localhost:8000/api/documentation

A Postman collection is also provided at the root directory: `postman_collection.json`.

## 4. Running Tests

Run the automated test suite:
```bash
php artisan test
```

## Architecture Overview

*   Controllers: Thin controllers managing HTTP responses via API Resources.
*   Form Requests: Input validation and authorization.
*   Services: Encapsulate business logic.
*   Repositories: Abstract persistence and decouple Eloquent models via interfaces.
*   Resources: Contract serialization for clean JSON outputs.