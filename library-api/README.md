# Hotel Management API

API Backend để quản lý phòng khách sạn và đặt phòng. Dự án được xây dựng với Laravel 12 và cung cấp các RESTful CRUD endpoints cho `rooms` và `bookings`, bao gồm coverage test toàn diện, Postman requests, và seed data để setup local nhanh chóng.

## Yêu cầu hệ thống
- PHP 8.2+
- Composer
- MySQL 8 (hoặc tương thích)

## Cài đặt và chạy

1. Cài đặt dependencies:
   ```bash
   composer install
   ```
2. Sao chép file environment và cấu hình database:
   ```bash
   cp .env.example .env
   ```
   Cập nhật các giá trị `DB_*` trong `.env` cho MySQL instance của bạn.
3. Tạo application key:
   ```bash
   php artisan key:generate
   ```
4. Chạy database migrations và seed sample data:
   ```bash
   php artisan migrate --seed
   ```
5. Chạy bộ test feature:
   ```bash
   php artisan test
   ```
6. Khởi động API server:
   ```bash
   php artisan serve
   ```

## Bộ sưu tập Postman
- Import `postman/hotel_management_api.postman_collection.json`.
- Set biến collection `baseUrl` nếu API không serve từ `http://localhost:8000`.
- Thực hiện requests theo thứ tự: tạo room trước, sau đó tạo bookings. Bộ sưu tập bao gồm cả success và validation scenarios.

## Nhật ký Test Case
- Cập nhật `test_cases.docx` với chi tiết thực hiện (timestamp, request, scenario, status) mỗi khi chạy Postman hoặc PHPUnit suites. File starter đã được cung cấp trong project root.
