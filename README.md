# ECMP186-NHOM06 – Đề tài: Docker & Chuẩn hóa môi trường phát triển phần mềm
# App Demo: Web Quản lý Sản phẩm Sữa

![CI - Build](https://github.com/pow267/ECMP186-NHOM06/actions/workflows/ci.yml/badge.svg)
![CD - Deploy](https://github.com/pow267/ECMP186-NHOM06/actions/workflows/cd.yml/badge.svg)

# TỔNG QUAN:
    Đây là Website quản lý sản phẩm sữa được xây dựng bằng PHP và PostgreSQL, 
    được container hóa bằng Docker và triển khai tự động thông qua CI/CD lên Fly.io.

# Mục tiêu của project:
    - Docker hóa ứng dụng
    - Tách môi trường Dev / Production
    - Tự động kiểm tra runtime bằng CI
    - Tự động deploy bằng CD
    - Quản lý secret đúng chuẩn

# Kiến trúc hệ thống:
    - Môi trường phát triển (Local):
        Developer
        → Docker Compose
        → PHP (Apache) Container
        → PostgreSQL Container

    - Môi trường Production:
        GitHub
        → CI (Build & Runtime Test)
        → CD (Auto Deploy)
        → Fly.io
        → PostgreSQL

# Công nghệ sử dụng:
    - PHP 8.2 (Apache)
    - PostgreSQL 15
    - Docker & Docker Compose
    - GitHub Actions (CI/CD)
    - Fly.io (Deployment)

# CI/CD Pipeline:
    - CI – Kiểm tra tự động, đảm bảo code không chỉ build được mà còn chạy được:
        * Build Docker image
        * Khởi động container App + DB
        * Chờ DB healthy
        * Kiểm tra HTTP trả về 200 OK
        * Shutdown container

    - CD – Triển khai tự động:
        * Chỉ chạy khi CI thành công
        * Tự động deploy lên Fly.io
        * Sử dụng Fly Secrets cho production
        * Có cấu hình healthcheck production

# Quản lý biến môi trường:
    Tuân thủ nguyên tắc: Không commit secret vào repo:
        - .env chỉ dùng cho local (không commit lên GitHub)
        - .env.example dùng làm mẫu
        - Production sử dụng fly secrets

# Các tính năng đã triển khai:
    - Container hóa ứng dụng
    - Tách biệt Dev và Production
    - Tự động khởi tạo database
    - Runtime CI validation
    - CD tự động triển khai
    - Healthcheck production
    - Quản lý secret theo best practice