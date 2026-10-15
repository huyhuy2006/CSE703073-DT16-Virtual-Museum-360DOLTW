# Bảo tàng 360 độ — CSE703073

**Mã đề tài:** ĐT-16 — Bảo tàng ảo và tour tham quan 360 độ trên nền web.

**Người thực hiện:** Wessel Peter Huy

## 1. Mục tiêu

Xây dựng website bảo tàng ảo có trải nghiệm panorama 360 độ, thông tin hiện vật,
chuyển cảnh, thuyết minh đa ngôn ngữ và lộ trình tham quan theo chủ đề.

## 2. Phạm vi chức năng

- Quản trị không gian tham quan và điểm nhìn.
- Liên kết chuyển cảnh giữa các không gian.
- Hotspot hiện vật và nội dung thuyết minh.
- Audio tiếng Việt và English.
- Tuyến tham quan theo chủ đề.
- Chế độ tự do và có hướng dẫn.
- Bộ sưu tập cá nhân và sổ lưu bút.
- Thống kê hành vi tham quan và phát hiện điểm bỏ dở.

## 3. Ba bảo tàng được chọn

1. Naturalis Biodiversity Center — Hà Lan.
2. Deutsches Historisches Museum — Đức.
3. Museum House Rams Woerthe — Hà Lan.

Nguồn panorama, tác giả, giấy phép và nội dung ghi công phải được lưu riêng
cho từng tệp. Chỉ triển khai các tài nguyên đã xác minh quyền sử dụng.

## 4. Công nghệ

- PHP 8.3 / Laravel 11.
- Vue 3 / Vite.
- MySQL 8.
- Pannellum cho panorama 360 độ.
- Python 3.12 cho phân tích dữ liệu theo giai đoạn.
- Git cho quản lý mã nguồn.
- Hostinger làm môi trường triển khai dự kiến.

## 5. Môi trường phát triển

Windows / PowerShell / Visual Studio Code.

## 6. Cài đặt cục bộ

Mở PowerShell tại thư mục dự án:

    Set-Location "C:\CSE703073\BaoTang360"

Cài các thư viện PHP:

    Set-Location ".\02_SourceCode\laravel"
    composer install

Tạo file môi trường cục bộ sau khi đã chuẩn bị cấu hình:

    Copy-Item ".env.example" ".env"

Lưu ý: lệnh Copy-Item trên áp dụng khi file .env.example có sẵn tại thư mục
Laravel. Trong cấu trúc hiện tại, file mẫu ở thư mục gốc nên cần sao chép từ
đường dẫn tương đối chính xác.

## 7. Bản quyền tài nguyên

Mỗi panorama/audio phải có thông tin nguồn, tác giả, giấy phép và ngày truy cập.
Không mặc định toàn bộ nội dung trên trang của bảo tàng được phép tái sử dụng.

## 8. Tình trạng dự án

Phần III: khởi tạo dự án và quản trị cấu hình.
Các chức năng nghiệp vụ, database, kiểm thử và triển khai sẽ được bổ sung
theo từng giai đoạn của học phần.
