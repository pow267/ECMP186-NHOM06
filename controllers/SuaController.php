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
        // ===== XỬ LÝ XÓA (POST an toàn) =====
        if ($_SERVER['REQUEST_METHOD'] === 'POST'
            && isset($_POST['action'])
            && $_POST['action'] === 'xoa') {

            if (!empty($_POST['ma_sua'])) {
                $this->model->delete($_POST['ma_sua']);
            }

            header("Location: /?deleted=1");
            exit;
        }

        // ===== XỬ LÝ THÊM =====
        if ($_SERVER['REQUEST_METHOD'] === 'POST'
            && isset($_POST['btn_them'])) {

            $hinh = 'default.jpg';

            if (isset($_FILES['hinh']) && $_FILES['hinh']['error'] === 0) {

                $uploadDir = __DIR__ . '/../public/images/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $originalName = $_FILES['hinh']['name'];
                $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

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

            header("Location: /?success=1");
            exit;
        }

        // ===== LOAD DỮ LIỆU =====
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