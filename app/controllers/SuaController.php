<?php

require_once __DIR__ . '/../models/SuaModel.php';
/** @noinspection PhpUndefinedClassInspection */

class SuaController
{
    /** @var SuaModel */
    private $model;
    private $uploadDir;

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
        $this->uploadDir = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/assets/images/';
    }

    /* ================= UTIL ================= */

    private function validateCsrf()
    {
        if (
            !isset($_POST['csrf_token']) ||
            !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
        ) {
            die("CSRF validation failed");
        }
    }

    private function setFlash($msg)
    {
        $_SESSION['flash'] = $msg;
    }

    private function safeUnlink($filename)
    {
        if (empty($filename) || $filename === 'default.jpg') return;

        $realBase = realpath($this->uploadDir);
        $realFile = realpath($this->uploadDir . $filename);

        if ($realFile && strpos($realFile, $realBase) === 0) {
            unlink($realFile);
        }
    }

    private function handleUpload()
    {
        if (!isset($_FILES['hinh']) || $_FILES['hinh']['error'] !== 0) {
            return 'default.jpg';
        }

        if ($_FILES['hinh']['size'] > 2 * 1024 * 1024) {
            die("File quá lớn (tối đa 2MB)");
        }

        $mime = mime_content_type($_FILES['hinh']['tmp_name']);
        $allowed = ['image/jpeg','image/png','image/webp'];

        if (!in_array($mime, $allowed)) {
            die("Định dạng file không hợp lệ");
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

    private function sanitize($input)
    {
        return [
            'ma_sua' => trim($input['ma_sua']),
            'ten_sua' => trim($input['ten_sua']),
            'ma_hang_sua' => trim($input['ma_hang_sua']),
            'loai_sua' => trim($input['loai_sua']),
            'trong_luong' => (int)$input['trong_luong'],
            'don_gia' => (int)$input['don_gia'],
            'tpdd' => trim($input['tpdd']),
            'loi_ich' => trim($input['loi_ich'])
        ];
    }

    /* ================= MAIN ================= */

    public function index()
    {
        if (($_GET['action'] ?? '') === 'logout') {
            $_SESSION = [];
            session_destroy();
            header("Location: /");
            exit;
        }

        if (isset($_POST['btn_login'])) {

            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if ($username === 'admin' && $password === '123') {
                $_SESSION['logged_in'] = true;
                $this->setFlash("Đăng nhập thành công!");
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

        /* ===== LOAD WITH PAGINATION ===== */

        $perPage = 9;
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;

        $totalProducts = $this->model->countAll();
        $totalPages = ceil($totalProducts / $perPage);

        $offset = ($page - 1) * $perPage;

        $products = $this->model->getPaginated($perPage, $offset);

        $hangSua = $this->model->getHangSua();
        $ma_sua_auto = $this->model->generateMaSua();

        $chitiet = null;
        if (isset($_GET['ma_sua'])) {
            $chitiet = $this->model->getById($_GET['ma_sua']);
        }

        require __DIR__ . '/../views/list.php';
    }
}