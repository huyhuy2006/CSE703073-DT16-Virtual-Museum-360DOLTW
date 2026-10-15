# BẢO TÀNG 360 ĐỘ — ĐT-16

**Đề tài:** Bảo tàng ảo và tour tham quan 360 độ trên nền web.

**Học phần:** CSE703073 — Lập trình ứng dụng web trong du lịch 2.

**Trường:** Đại học Phenikaa.

**Sinh viên:** Wessel Peter Huy.

**Mục đích:** Sản phẩm học thuật phục vụ mục đích đào tạo.

## 1. Mục tiêu

Xây dựng website bảo tàng ảo có trải nghiệm panorama 360 độ,
điểm nóng hiện vật, thuyết minh đa ngữ và tuyến tham quan có
cấu trúc dẫn dắt.

Website hướng đến khả năng tiếp cận di sản từ xa, đồng thời
cung cấp lớp thông tin giúp người dùng hiểu nội dung trưng bày.

## 2. Công nghệ dự kiến

- PHP 8.3
- Laravel 11
- MySQL
- Vue 3 và Vite
- Pannellum cho panorama 360 độ
- Python 3.12 và pandas cho xử lý thống kê theo lô
- Hostinger cho triển khai website

## 3. Các chức năng chính

- Danh sách bảo tàng và trang thông tin từng bảo tàng
- Panorama 360 độ và điểm chuyển cảnh
- MiniMap và hotspot hiện vật
- Thông tin hiện vật
- Thuyết minh tiếng Việt và tiếng Anh
- Tuyến tham quan theo chủ đề
- Chế độ tự do và chế độ có hướng dẫn
- Tài khoản, bộ sưu tập cá nhân và sổ lưu bút
- Thống kê hành vi tham quan
- Trang sản phẩm bán vé, giỏ hàng và thanh toán
- Quản trị nội dung và tài khoản

## 4. Cấu trúc mã nguồn

- `02_SourceCode/laravel/`: ứng dụng Laravel
- `02_SourceCode/python-analytics/`: mô-đun phân tích theo lô
- `02_SourceCode/docker/`: tài liệu/cấu hình dự phòng nếu cần
- `03_Database/`: thiết kế cơ sở dữ liệu
- `04_HuongDanSuDung/`: hướng dẫn sử dụng
- `05_KiemThu/`: kiểm thử
- `07_QuanTriDuAn/`: biên bản và kế hoạch dự án

## 5. Tài nguyên panorama và audio

Ảnh panorama và audio gốc được lưu ngoài kho Git tại
`C:\CSE703073\media-goc\`.

Chỉ sử dụng tài nguyên đã xác minh nguồn, giấy phép và điều kiện
ghi công. Không đưa tài nguyên dung lượng lớn vào Git nếu không
cần thiết.

### Danh sách nguồn cần xác minh

- Naturalis Biodiversity Center — Hà Lan
- Deutsches Historisches Museum — Đức
- Museum House Rams Woerthe — Hà Lan

Các giấy phép và đường dẫn nguồn phải được kiểm tra lại trước
khi sử dụng trong bản triển khai công khai.

## 6. Chạy môi trường phát triển

Xem hướng dẫn cài đặt trong tài liệu dự án. Không chạy ứng dụng
Laravel trước khi hoàn thành phần khởi tạo ứng dụng ở các phần sau.

## 7. Triển khai

Mục tiêu triển khai: Hostinger với HTTPS.

Thông tin tên miền, gói hosting, đường dẫn triển khai và cấu hình
máy chủ sẽ được bổ sung sau khi xác minh trong hPanel.

## 8. Tác giả

Wessel Peter Huy
