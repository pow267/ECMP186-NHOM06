<?php

require_once __DIR__ . '/../models/SuaModel.php';

class SuaController
{
    private $model;

    public function __construct()
    {
        $this->model = new SuaModel();
    }

    public function index()
    {
        /* ===================== XÓA ===================== */
        if ($_SERVER['REQUEST_METHOD'] === 'POST'
            && isset($_POST['action'])
            && $_POST['action'] === 'xoa') {

            if (!empty($_POST['ma_sua'])) {

                $sua = $this->model->getById($_POST['ma_sua']);

                if ($sua && !empty($sua['hinh']) && $sua['hinh'] !== 'default.jpg') {

                    $imagePath = __DIR__ . '/../../public/assets/images/' . $sua['hinh'];

                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }

                $this->model->delete($_POST['ma_sua']);
            }

            header("Location: /");
            exit;
        }

        /* ===================== THÊM ===================== */
        if ($_SERVER['REQUEST_METHOD'] === 'POST'
            && isset($_POST['btn_them'])) {

            $hinh = 'default.jpg';

            if (isset($_FILES['hinh']) && $_FILES['hinh']['error'] === 0) {

                $uploadDir = __DIR__ . '/../../public/assets/images/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $ext = strtolower(pathinfo($_FILES['hinh']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png'];

                if (in_array($ext, $allowed)) {

                    $newFileName = uniqid('milk_', true) . '.' . $ext;
                    $targetPath = $uploadDir . $newFileName;

                    if (move_uploaded_file($_FILES['hinh']['tmp_name'], $targetPath)) {
                        $hinh = $newFileName;
                    }
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

            $this->model->insert($data);

            header("Location: /?ma_sua=" . $_POST['ma_sua']);
            exit;
        }

        /* ===================== SỬA ===================== */
        if ($_SERVER['REQUEST_METHOD'] === 'POST'
            && isset($_POST['btn_sua'])) {

            $hinh = $_POST['hinh_cu'];
            $oldImage = $_POST['hinh_cu'];

            if (isset($_FILES['hinh']) && $_FILES['hinh']['error'] === 0) {

                $uploadDir = __DIR__ . '/../../public/assets/images/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $ext = strtolower(pathinfo($_FILES['hinh']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png'];

                if (in_array($ext, $allowed)) {

                    $newFileName = uniqid('milk_', true) . '.' . $ext;
                    $targetPath = $uploadDir . $newFileName;

                    if (move_uploaded_file($_FILES['hinh']['tmp_name'], $targetPath)) {

                        // Xóa ảnh cũ nếu không phải default
                        if (!empty($oldImage) && $oldImage !== 'default.jpg') {

                            $oldPath = $uploadDir . $oldImage;

                            if (file_exists($oldPath)) {
                                unlink($oldPath);
                            }
                        }

                        $hinh = $newFileName;
                    }
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

            $this->model->update($data);

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