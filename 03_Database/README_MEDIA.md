# Hướng dẫn quản lý media cho BaoTang360

## 1. Panorama 360 độ

Thư mục đích trong Laravel:

public/media/panoramas/<slug-bao-tang>/canh-NN/

Ví dụ cấu trúc:

public/media/panoramas/naturalis-biodiversity-center/canh-01/

Chỉ thêm panorama khi đã xác minh:
- Nguồn gốc tệp.
- Quyền sử dụng và điều kiện giấy phép.
- Độ phân giải, định dạng và khả năng hiển thị 360 độ.
- Tên tác giả hoặc đơn vị cung cấp.
- URL nguồn tham chiếu.

## 2. Ảnh hiện vật

Thư mục đích:

public/media/artifacts/<slug-bao-tang>/

Tên file nên gắn với mã hiện vật, ví dụ:

NAT-ART-001.jpg

Không sử dụng mã mẫu làm bằng chứng rằng hiện vật đó có thật.

## 3. Audio thuyết minh

Tiếng Việt:

public/media/audio/vi/<slug-bao-tang>/

Tiếng Anh:

public/media/audio/en/<slug-bao-tang>/

Đặt tên tệp theo mã hiện vật hoặc mã cảnh để dễ quản lý.

## 4. Thông tin nguồn media

Với mỗi tệp, lưu:
- Tên tệp.
- URL nguồn.
- Tác giả hoặc đơn vị sở hữu.
- Giấy phép chính xác.
- Ngày truy cập hoặc tải.
- Ghi chú về điều kiện ghi công.
- Trạng thái xác minh.

Không tự gán giấy phép Creative Commons cho một tệp
chỉ vì trang web hoặc bảo tàng có sử dụng nội dung mở.

## 5. Bảo vệ bản quyền

Không đưa lên website các tệp chưa xác minh quyền sử dụng.
Nếu chưa có media thật, giữ chỗ bằng placeholder và hiển thị
thông báo đang cập nhật trong môi trường phát triển.
