# Hotel Management API 

API Backend để quản lý phòng khách sạn và đặt phòng.

## 🏗️ Cấu Trúc Dự Án

```
library-api/
├── app/
│   ├── Http/Controllers/
│   │   ├── BookingController.php    # CRUD operations cho bookings
│   │   └── RoomController.php       # CRUD operations cho rooms
│   └── Models/
│       ├── Booking.php              # Model Booking với relationships
│       └── Room.php                 # Model Room với relationships
├── database/
│   ├── factories/                   # Model factories cho testing
│   │   ├── BookingFactory.php
│   │   ├── RoomFactory.php
│   │   └── UserFactory.php
│   ├── migrations/                  # Database schema migrations
│   │   ├── 2025_10_17_021604_create_rooms_table.php
│   │   └── 2025_10_17_021610_create_bookings_table.php
│   └── seeders/                     # Database seeders
├── postman/
│   └── hotel_management_api.postman_collection.json  # Bộ sưu tập API test hoàn chỉnh
├── tests/
│   ├── Feature/
│   │   ├── BookingControllerTest.php  # PHPUnit tests cho bookings
│   │   └── RoomControllerTest.php     # PHPUnit tests cho rooms
│   └── Unit/                         # Thư mục unit tests
├── tmp_test_cases/
│   └── word/
│       └── document.xml              # Nhật ký thực hiện test case
├── .env.example                      # Template environment
├── composer.json                     # Dependencies PHP
├── phpunit.xml                       # Cấu hình test
└── test_cases.docx                   # Tài liệu test
```

## 🔧 Yêu Cầu Hệ Thống
- PHP 8.2+
- Composer
- MySQL 8 (hoặc tương thích)
- Laravel 12

## 🚀 Hướng Dẫn Khởi Chạy Nhanh Cho Giáo Viên

### 1. Thiết Lập Môi Trường
```bash
# Vào thư mục dự án
cd library-api

# Cài đặt dependencies PHP
composer install

# Sao chép file environment
cp .env.example .env

# Cấu hình database trong file .env
# Cập nhật DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
```

### 2. Thiết Lập Database
```bash
# Tạo application key
php artisan key:generate

# Chạy migrations và seed data
php artisan migrate --seed
```

### 3. Chạy Tests
```bash
# Thực hiện đầy đủ bộ test PHPUnit (22 assertions)
php artisan test

# Kết quả mong đợi: Tất cả tests pass ✅
```

### 4. Khởi Động API Server
```bash
php artisan serve
# API có sẵn tại: http://localhost:8000
```

## 📡 API Endpoints

### Quản Lý Phòng (Rooms)
| Method | Endpoint | Mô tả |
|--------|----------|--------|
| GET | `/api/rooms` | Liệt kê tất cả phòng với bookings |
| POST | `/api/rooms` | Tạo phòng mới |
| GET | `/api/rooms/{id}` | Lấy chi tiết phòng cụ thể |
| PUT | `/api/rooms/{id}` | Cập nhật thông tin phòng |
| DELETE | `/api/rooms/{id}` | Xóa phòng (cascade bookings) |

### Quản Lý Đặt Phòng (Bookings)
| Method | Endpoint | Mô tả |
|--------|----------|--------|
| GET | `/api/bookings` | Liệt kê tất cả bookings với thông tin phòng |
| POST | `/api/bookings` | Tạo booking mới |
| GET | `/api/bookings/{id}` | Lấy chi tiết booking cụ thể |
| PUT | `/api/bookings/{id}` | Cập nhật thông tin booking |
| DELETE | `/api/bookings/{id}` | Xóa booking |

## 🧪 Testing & Validation

### PHPUnit Tests
- **RoomControllerTest**: 8 methods test toàn diện
- **BookingControllerTest**: 8 methods test toàn diện
- **Tổng cộng**: 16 test methods, 22 assertions
- **Coverage**: Tất cả CRUD operations + validation + error handling

### Bộ Sưu Tập Postman
- Import: `postman/hotel_management_api.postman_collection.json`
- **16 requests** bao phủ tất cả endpoints
- Kịch bản thành công + kịch bản lỗi validation
- Scripts tự động để verify responses

### Validation Rules
**Tạo/Cập Nhật Phòng:**
- `room_number`: bắt buộc, string, max:50, unique
- `type`: bắt buộc, string, max:50
- `price`: bắt buộc, numeric, min:0
- `availability_status`: bắt buộc, boolean

**Tạo/Cập Nhật Booking:**
- `room_id`: bắt buộc, tồn tại trong bảng rooms
- `customer_name`: bắt buộc, string, max:100
- `check_in_date`: bắt buộc, date
- `check_out_date`: bắt buộc, date, sau check_in_date
- `status`: bắt buộc, trong: ['pending', 'confirmed', 'canceled']


## 📝 Tài Liệu Test Case

File `test_cases.docx` chứa nhật ký thực hiện chi tiết cho:
- Tất cả requests API Postman (16 endpoints × success/error scenarios)
- Thực hiện bộ test PHPUnit
- Timestamps và status cho mỗi lần chạy test

**Vị trí**: `library-api/test_cases.docx` (file nộp chính)

## 🔍 Files Quan Trọng Để Review

**Core Implementation:**
- `app/Http/Controllers/RoomController.php`
- `app/Http/Controllers/BookingController.php`
- `app/Models/Room.php`
- `app/Models/Booking.php`

**Database Schema:**
- `database/migrations/2025_10_17_021604_create_rooms_table.php`
- `database/migrations/2025_10_17_021610_create_bookings_table.php`

**Testing:**
- `tests/Feature/RoomControllerTest.php`
- `tests/Feature/BookingControllerTest.php`
- `postman/hotel_management_api.postman_collection.json`

**Documentation:**
- `test_cases.docx`
- `README.md` 
---
