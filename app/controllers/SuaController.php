<?php

require_once __DIR__ . '/../models/SuaModel.php';

class SuaController
{
    private SuaModel $model;
    private string $uploadDir;

    public function __construct()
    {
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'httponly' => true,
            'secure' => isset($_SERVER['HTTPS'])
        ]);

        session_start();

        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $this->model = new SuaModel();
        $this->uploadDir = __DIR__ . '/../../public/assets/images/';
    }

    /* ================= UTIL ================= */

    private function validateCsrf(): void
    {
        if (
            !isset($_POST['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {
            $_SESSION['errors'] = ["CSRF validation failed"];
            header("Location: /");
            exit;
        }
    }

    private function sanitize(array $input): array
    {
        return [
            'ma_sua' => trim($input['ma_sua'] ?? ''),
            'ten_sua' => trim($input['ten_sua'] ?? ''),
            'ma_hang_sua' => trim($input['ma_hang_sua'] ?? ''),
            'loai_sua' => trim($input['loai_sua'] ?? ''),
            'trong_luong' => (int)($input['trong_luong'] ?? 0),
            'don_gia' => (int)($input['don_gia'] ?? 0),
            'tpdd' => trim($input['tpdd'] ?? ''),
            'loi_ich' => trim($input['loi_ich'] ?? '')
        ];
    }

    private function handleUpload(): string
    {
        if (!isset($_FILES['hinh']) || $_FILES['hinh']['error'] !== UPLOAD_ERR_OK) {
            return 'default.jpg';
        }

        if ($_FILES['hinh']['size'] > 2 * 1024 * 1024) {
            $_SESSION['errors'][] = "File quá lớn (tối đa 2MB)";
            return 'default.jpg';
        }

        $mime = mime_content_type($_FILES['hinh']['tmp_name']);
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($mime, $allowed)) {
            $_SESSION['errors'][] = "Định dạng file không hợp lệ";
            return 'default.jpg';
        }

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }

        $ext = match ($mime) {
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => 'jpg'
        };

        $newName = uniqid('milk_', true) . '.' . $ext;
        $target = $this->uploadDir . $newName;

        if (move_uploaded_file($_FILES['hinh']['tmp_name'], $target)) {
            return $newName;
        }

        return 'default.jpg';
    }

    /* ================= MAIN ================= */

    public function index(): void
    {
        // LOGOUT
        if (($_GET['action'] ?? '') === 'logout') {
            $_SESSION = [];
            session_destroy();
            header("Location: /");
            exit;
        }

        // LOGIN
        if (isset($_POST['btn_login'])) {

            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if ($username === 'admin' && $password === '123') {
                $_SESSION['logged_in'] = true;
                $_SESSION['flash'] = "Đăng nhập thành công!";
                header("Location: /");
                exit;
            }

            $error = "Sai tài khoản hoặc mật khẩu!";
            require __DIR__ . '/../views/login.php';
            return;
        }

        if (!isset($_SESSION['logged_in'])) {
            require __DIR__ . '/../views/login.php';
            return;
        }

        // ADD
        if (isset($_POST['btn_them'])) {
            $this->store();
            return;
        }

        // UPDATE
        if (isset($_POST['btn_capnhat'])) {
            $this->update();
            return;
        }

        // DELETE
        if (isset($_GET['delete'])) {
            $this->delete($_GET['delete']);
            return;
        }

        // PAGINATION
        $perPage = 9;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $offset = ($page - 1) * $perPage;

        $totalProducts = $this->model->countAll();
        $totalPages = max(1, ceil($totalProducts / $perPage));

        $products = $this->model->getPaginated($perPage, $offset);
        $hangSua = $this->model->getHangSua();
        $ma_sua_auto = $this->model->generateMaSua();

        $chitiet = null;
        if (isset($_GET['ma_sua'])) {
            $chitiet = $this->model->getById($_GET['ma_sua']);
        }

        require __DIR__ . '/../views/list.php';
    }

    /* ================= STORE ================= */

    private function store(): void
    {
        $this->validateCsrf();

        $data = $this->sanitize($_POST);

        $data['hinh'] = $this->handleUpload();

        $result = $this->model->insert($data);

        if ($result) {
            $_SESSION['flash'] = "Thêm sản phẩm thành công!";
        } else {
            $_SESSION['errors'] = ["Có lỗi xảy ra khi thêm sản phẩm"];
        }

        header("Location: /?page=1");
        exit;
    }

    /* ================= UPDATE ================= */

    private function update(): void
    {
        $this->validateCsrf();

        $data = $this->sanitize($_POST);

        $current = $this->model->getById($data['ma_sua']);

        if (!$current) {
            $_SESSION['errors'] = ["Sản phẩm không tồn tại"];
            header("Location: /");
            exit;
        }

        // Nếu có ảnh mới
        if (isset($_FILES['hinh']) && $_FILES['hinh']['error'] === UPLOAD_ERR_OK) {

            $newImage = $this->handleUpload();

            if ($newImage !== 'default.jpg') {

                // Xóa ảnh cũ
                if (!empty($current['hinh']) && $current['hinh'] !== 'default.jpg') {
                    $oldPath = $this->uploadDir . $current['hinh'];
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                $data['hinh'] = $newImage;
            } else {
                $data['hinh'] = $current['hinh'];
            }

        } else {
            $data['hinh'] = $current['hinh'];
        }

        $result = $this->model->update($data);

        if ($result) {
            $_SESSION['flash'] = "Cập nhật thành công!";
        } else {
            $_SESSION['errors'] = ["Cập nhật thất bại"];
        }

        header("Location: /?ma_sua=" . $data['ma_sua']);
        exit;
    }

    /* ================= DELETE ================= */

    private function delete(string $ma_sua): void
    {
        $current = $this->model->getById($ma_sua);

        if ($current && $current['hinh'] !== 'default.jpg') {
            $path = $this->uploadDir . $current['hinh'];
            if (file_exists($path)) {
                unlink($path);
            }
        }

        $this->model->delete($ma_sua);

        $_SESSION['flash'] = "Xóa sản phẩm thành công!";
        header("Location: /");
        exit;
    }
}