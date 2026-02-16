ECMP186-NHOM06
TECHNICAL DEEP DIVE
Dockerized PHP Application with Runtime-Validated CI/CD
1. Giới thiệu kỹ thuật

    Tài liệu này phân tích chi tiết kiến trúc và quyết định kỹ thuật của dự án ECMP186-NHOM06. Mục tiêu của hệ thống không chỉ là chạy được ứng dụng PHP trong Docker, mà là xây dựng một pipeline DevOps có khả năng:

        - Chuẩn hóa môi trường phát triển

        - Xác thực runtime tự động

        - Triển khai production an toàn

        - Quản lý secret đúng chuẩn

        - Tách biệt hoàn toàn Dev và Production

    Triết lý thiết kế: Build once, deploy many.
    Docker image được build một lần và cấu hình theo môi trường khi triển khai.

2. Kiến trúc hệ thống

2.1 Môi trường Development

    Developer
    → Docker Compose
    → App Container (PHP 8.2 + Apache)
    → PostgreSQL Container

    Đặc điểm kỹ thuật:

        - Ứng dụng và database chạy trong hai container riêng biệt

        - Docker Compose quản lý network nội bộ giữa các service

        - Volume được dùng để persist dữ liệu database

        - Có healthcheck cho PostgreSQL

    Lợi ích:

        - Tái tạo môi trường chỉ với một lệnh docker compose up

        - Loại bỏ phụ thuộc cấu hình máy cá nhân

        - Giảm rủi ro lỗi “chạy được trên máy tôi”

    2.2 Môi trường Production

    GitHub
    → CI (Build + Runtime Validation)
    → CD (Deploy khi CI thành công)
    → Fly.io
    → PostgreSQL Production

    Đặc điểm:

        - CI xác thực runtime thực tế

        - CD chỉ chạy khi CI thành công

        - Secret được inject theo môi trường

        - Có healthcheck production

3. Phân tích Dockerfile
    Base image:

    php:8.2-apache

    Lý do lựa chọn:

        - Phiên bản ổn định

        - Tích hợp Apache

        - Phù hợp với mô hình PHP truyền thống

    Các bước chính:

        - Cài đặt libpq-dev

        - docker-php-ext-install pdo pdo_pgsql

        - Bật mod_rewrite

    Ý nghĩa kỹ thuật:

        - pdo_pgsql cho phép kết nối PostgreSQL

        - mod_rewrite hỗ trợ routing linh hoạt

        - Không cài đặt thừa dependency để giữ image gọn nhẹ

    Thiết kế tuân thủ nguyên tắc:

        - Một container chỉ phục vụ một trách nhiệm

        - Không nhúng database vào image ứng dụng

    Có thể mở rộng:

        - Sử dụng multi-stage build để giảm image size

        - Thay Apache bằng php-fpm + nginx nếu cần tối ưu hiệu năng

4. Docker Compose – Thiết kế môi trường Local
    Cấu trúc gồm:

    Service: app
    Service: db

    db:
        - image postgres:15

        - environment variables

        - volume để lưu dữ liệu

        - healthcheck kiểm tra trạng thái DB

    app:

        - build từ Dockerfile

        - depends_on db

        - sử dụng .env

        - map port 8080:80

    Điểm quan trọng:

    Healthcheck database giúp đảm bảo app không chạy logic trước khi DB sẵn sàng.

    Compose tạo một network nội bộ để app kết nối database thông qua service name.

5. Cơ chế khởi tạo Database
    Database được tự động khởi tạo khi container start lần đầu.

    Các cách tiếp cận thường dùng:

        - Mount file SQL vào thư mục docker-entrypoint-initdb.d
        - Hoặc để ứng dụng kiểm tra và tạo bảng nếu chưa tồn tại
    
    Lợi ích:
        - Không cần chạy script thủ công

        - CI có thể spin-up môi trường mới hoàn toàn

        - Production có thể tái tạo hệ thống

6. CI Pipeline – Runtime Validation
    Mục tiêu của CI không chỉ là build thành công mà là xác thực runtime thực tế.
    Luồng CI:
        Build Docker image

        Khởi động database container

        Khởi động app container

        Chờ database healthy

        Gửi HTTP request tới ứng dụng

        Kiểm tra HTTP status 200

        Shutdown container

    Điểm quan trọng:

    Build success không đồng nghĩa runtime success.

    Lỗi có thể xảy ra dù build thành công:

        - Sai ENV

        - Sai DB host

        - Thiếu extension

        - Migration lỗi

        - Lỗi kết nối database

    Pipeline hiện tại phát hiện được các lỗi đó trước khi merge.

    Đây là integration-level validation.

7. CD Pipeline – Triển khai Production
    CD chỉ chạy khi:

        - Push lên branch main
        - CI thành công

    Luồng CD:

        Authenticate với Fly.io

        Build image production

        Deploy lên Fly

        Inject biến môi trường bằng fly secrets

        Fly thực hiện healthcheck

    Ý nghĩa:

        - Không deploy code lỗi

        - Secret không nằm trong repository

        - Production có thể restart nếu unhealthy

8. Secret Management Strategy
    Nguyên tắc:

        - Không commit .env

        - .env.example chỉ chứa cấu trúc

        - Production dùng fly secrets

    Tách biệt:

    Local:

        - .env riêng

    Repository:

        - Không chứa secret

    Production:

        - Secret lưu trên Fly

    Lợi ích:

        - Có thể rotate secret mà không rebuild image

        - Image không chứa thông tin nhạy cảm

        - An toàn khi open-source repo

9. Tách biệt Environment
    Development:

        - Docker Compose

        - Database local

        - ENV local

    Production:

        - Fly infrastructure

        - Secret riêng

        - Database production riêng

    Không dùng chung database.
    Không dùng chung cấu hình.

    Điều này ngăn:

        - Dev ghi đè dữ liệu production

        - Lộ thông tin nhạy cảm

10. Failure Scenario Analysis

10.1 Database không khởi động

    CI sẽ fail khi healthcheck DB không pass.

10.2 App không kết nối được DB

    HTTP request trong CI sẽ trả lỗi, pipeline fail.

10.3 Production bị crash

    Fly healthcheck sẽ đánh dấu unhealthy và restart instance.

10.4 Secret sai

    Ứng dụng không connect DB → healthcheck fail → deployment không ổn định.

11. Khả năng mở rộng
    Hệ thống có thể nâng cấp thêm:

        - Unit test stage

        - Integration test nâng cao

        - Staging environment

        - Container registry riêng

        - Monitoring (Prometheus, Grafana)

        - Logging tập trung

        - Database migration tool

    Kiến trúc hiện tại đủ linh hoạt để mở rộng theo hướng microservice nếu cần.

12. Năng lực DevOps thể hiện
    - Thiết kế multi-container system

    - Viết Dockerfile chuẩn production

    - Runtime validation trong CI

    - CD có điều kiện

    - Tách biệt environment

    - Quản lý secret an toàn

    - Healthcheck production

    - Hiểu rõ sự khác biệt giữa build success và runtime success

13. Kết luận kỹ thuật
    Dự án này không chỉ dừng ở mức container hóa, mà mô phỏng một pipeline DevOps thực tế:

        - Chuẩn hóa môi trường

        - Kiểm tra tự động có dependency

        - Triển khai có kiểm soát

        - Bảo mật cấu hình

        - Đảm bảo ổn định production

    Ở mức độ kỹ thuật, đây là một implementation DevOps entry-to-mid level, có thể mở rộng lên hệ thống production phức tạp hơn khi bổ sung thêm testing, monitoring và observability.