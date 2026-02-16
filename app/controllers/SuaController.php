<?php

require_once __DIR__ . '/../models/SuaModel.php';

class SuaController
{
    private $model;
    private $uploadDir;

    public function __construct()
    {
        $this->model = new SuaModel();
        $this->uploadDir = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/assets/images/';
    }

    private function safeUnlink($filename)
    {
        if (empty($filename) || $filename === 'default.jpg') {
            return;
        }

        $realBase = realpath($this->uploadDir);
        $realFile = realpath($this->uploadDir . $filename);

        if ($realFile && $realBase && strpos($realFile, $realBase) === 0) {
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
        $allowedMime = ['image/jpeg', 'image/png'];

        if (!in_array($mime, $allowedMime)) {
            die("Định dạng file không hợp lệ");
        }

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }

        $ext = $mime === 'image/png' ? 'png' : 'jpg';
        $newFileName = uniqid('milk_', true) . '.' . $ext;
        $targetPath = $this->uploadDir . $newFileName;

        if (move_uploaded_file($_FILES['hinh']['tmp_name'], $targetPath)) {
            return $newFileName;
        }

        return 'default.jpg';
    }

    public function index()
    {
        /* ===================== XÓA ===================== */
        if ($_SERVER['REQUEST_METHOD'] === 'POST'
            && isset($_POST['action'])
            && $_POST['action'] === 'xoa'
            && !empty($_POST['ma_sua'])) {

            try {
                $sua = $this->model->getById($_POST['ma_sua']);

                if ($sua && !empty($sua['hinh'])) {
                    $this->safeUnlink($sua['hinh']);
                }

                $this->model->delete($_POST['ma_sua']);
            } catch (Exception $e) {
                error_log($e->getMessage());
                die("Có lỗi xảy ra.");
            }

            header("Location: /");
            exit;
        }

        /* ===================== THÊM ===================== */
        if ($_SERVER['REQUEST_METHOD'] === 'POST'
            && isset($_POST['btn_them'])) {

            $hinh = $this->handleUpload();

            $data = [
                'ma_sua' => $_POST['ma_sua'],
                'ten_sua' => $_POST['ten_sua'],
                'ma_hang_sua' => $_POST['ma_hang_sua'],
                'loai_sua' => $_POST['loai_sua'],
                'trong_luong' => $_POST['trong_luong'],
                'don_gia' => $_POST['don_gia'],
                'tpdd' => $_POST['tpdd'],
                'loi_ich' => $_POST['loi_ich'],
                'hinh' => $hinh
            ];

            try {
                $this->model->insert($data);
            } catch (Exception $e) {
                error_log($e->getMessage());
                die("Có lỗi xảy ra.");
            }

            header("Location: /?ma_sua=" . $_POST['ma_sua']);
            exit;
        }

        /* ===================== SỬA ===================== */
        if ($_SERVER['REQUEST_METHOD'] === 'POST'
            && isset($_POST['btn_sua'])) {

            $oldImage = $_POST['hinh_cu'] ?? 'default.jpg';
            $hinh = $oldImage;

            if (isset($_FILES['hinh']) && $_FILES['hinh']['error'] === 0) {

                $newImage = $this->handleUpload();

                if ($newImage !== 'default.jpg') {
                    $this->safeUnlink($oldImage);
                    $hinh = $newImage;
                }
            }

            $data = [
                'ma_sua' => $_POST['ma_sua'],
                'ten_sua' => $_POST['ten_sua'],
                'ma_hang_sua' => $_POST['ma_hang_sua'],
                'loai_sua' => $_POST['loai_sua'],
                'trong_luong' => $_POST['trong_luong'],
                'don_gia' => $_POST['don_gia'],
                'tpdd' => $_POST['tpdd'],
                'loi_ich' => $_POST['loi_ich'],
                'hinh' => $hinh
            ];

            try {
                $this->model->update($data);
            } catch (Exception $e) {
                error_log($e->getMessage());
                die("Có lỗi xảy ra.");
            }

            header("Location: /?ma_sua=" . $_POST['ma_sua']);
            exit;
        }

        /* ===================== LOAD DATA ===================== */
        $products = $this->model->getAll();
        $hangSua = $this->model->getHangSua();
        $ma_sua_auto = $this->model->generateMaSua();

        $chitiet = null;
        if (isset($_GET['ma_sua'])) {
            $chitiet = $this->model->getById($_GET['ma_sua']);
        }

        require __DIR__ . '/../views/list.php';
    }
}