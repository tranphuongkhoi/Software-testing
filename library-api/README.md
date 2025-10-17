# Hotel Management API

Backend API for managing hotel rooms and bookings. The project is built with Laravel 12 and exposes RESTful CRUD endpoints for `rooms` and `bookings`, includes feature test coverage, Postman requests, and seed data for quick local setup.

## Requirements
- PHP 8.2+
- Composer
- MySQL 8 (or compatible service)

## Setup
1. Install dependencies:
   ```bash
   composer install
   ```
2. Copy the environment file and configure database credentials:
   ```bash
   cp .env.example .env
   ```
   Update the `DB_*` values in `.env` for your MySQL instance.
3. Generate the application key:
   ```bash
   php artisan key:generate
   ```
4. Run database migrations and seed sample data:
   ```bash
   php artisan migrate --seed
   ```
5. Run the feature test suite:
   ```bash
   php artisan test
   ```
6. Serve the API:
   ```bash
   php artisan serve
   ```

## Postman Collection
- Import `postman/hotel_management_api.postman_collection.json`.
- Set the `baseUrl` collection variable if the API is not served from `http://localhost:8000`.
- Execute the requests in order: create a room, then bookings. The collection captures both success and validation scenarios.

## Test Case Log
- Update `test_cases.docx` with execution details (timestamp, request, scenario, status) whenever you run Postman or PHPUnit suites. A starter document is provided in the project root.
