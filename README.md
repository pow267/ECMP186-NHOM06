# 1. Môi trường và chuẩn hóa

Cần nắm rõ nội dung:

    - Vì sao trước Docker dễ lỗi
    - “Works on my machine” là gì
    - Chuẩn hóa bằng container nghĩa là gì
    - Dev vs Production

File bắt buộc đọc để có nội dung:

    - docker-compose.yml
    - .env
    - Database.php

# 2. Multi-stage Dockerfile (Frontend Builder)

Cần nắm rõ nội dung:

    - Multi-stage build là gì
    - Tại sao dùng node:20-alpine
    - Tại sao không giữ node trong image cuối
    - COPY --from có ý nghĩa gì

File bắt buộc đọc để có nội dung:

    - docker/Dockerfile (STAGE 1)
    - package.json

# 3. PHP Runtime & Security

Cần nắm rõ nội dung:

    - php:8.2-apache là gì
    - docker-php-ext-install pdo_pgsql
    - OPcache
    - Apache DocumentRoot
    - Healthcheck
    - ServerTokens Prod

File bắt buộc đọc để có nội dung:

    - docker/Dockerfile (STAGE 2)

# 4. Docker Compose Architecture

Cần nắm rõ nội dung:

    - services: app & db
    - depends_on condition service_healthy
    - Volume db_data
    - Network bridge
    - Tại sao local mount source code

File bắt buộc đọc để có nội dung:

    - docker-compose.yml
    - init.sql

# 5. CI Pipeline

Cần nắm rõ nội dung:

    - Trigger pull_request & push main
    - docker build
    - docker run
    - curl health check
    - exit 1

File bắt buộc đọc để có nội dung:

    - .github/workflows/ci.yml

# 6. CD Pipeline

Cần nắm rõ nội dung:

    - workflow_run
    - Tại sao tách CI & CD
    - Điều kiện success
    - flyctl deploy
    - FLY_API_TOKEN

File bắt buộc đọc để có nội dung:

    - .github/workflows/cd.yml
    - fly.toml

# 7. Database & Environment Config

Cần nắm rõ nội dung:

    - DATABASE_URL format
    - parse_url
    - getenv
    - init.sql auto seed
    - Tại sao không hardcode

File bắt buộc đọc để có nội dung:

    - Database.php
    - .env
    - init.sql