<?php

require_once __DIR__ . '/../config/Database.php';

class SuaModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    // Lấy toàn bộ sản phẩm
    public function getAll()
    {
        $sql = "SELECT ma_sua, ten_sua, trong_luong, don_gia, hinh FROM sua";
        return $this->pdo->query($sql)->fetchAll();
    }

    // Lấy chi tiết theo mã
    public function getById($ma_sua)
    {
        $stmt = $this->pdo->prepare("
            SELECT sua.*, hang_sua.ten_hs
            FROM sua
            JOIN hang_sua ON sua.ma_hang_sua = hang_sua.ma_hs
            WHERE sua.ma_sua = :ma
        ");

        $stmt->execute(['ma' => $ma_sua]);
        return $stmt->fetch();
    }

    // Lấy danh sách hãng sữa
    public function getHangSua()
    {
        return $this->pdo->query("SELECT * FROM hang_sua")->fetchAll();
    }

    // Tạo mã sữa tự động
    public function generateMaSua()
    {
        $stmt = $this->pdo->query("SELECT MAX(ma_sua) AS max_ma FROM sua");
        $row = $stmt->fetch();

        if (!$row || !$row['max_ma']) {
            return 'S01';
        }

        $so = intval(substr($row['max_ma'], 1)) + 1;
        return 'S' . str_pad($so, 2, '0', STR_PAD_LEFT);
    }

    // Thêm mới sản phẩm
    public function insert($data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO sua (
                ma_sua,
                ten_sua,
                ma_hang_sua,
                loai_sua,
                trong_luong,
                don_gia,
                thanh_phan_dinh_duong,
                loi_ich,
                hinh
            )
            VALUES (
                :ma_sua,
                :ten_sua,
                :ma_hang_sua,
                :loai_sua,
                :trong_luong,
                :don_gia,
                :tpdd,
                :loi_ich,
                :hinh
            )
        ");

        $stmt->execute([
            'ma_sua' => $data['ma_sua'],
            'ten_sua' => $data['ten_sua'],
            'ma_hang_sua' => $data['ma_hang_sua'],
            'loai_sua' => $data['loai_sua'],
            'trong_luong' => $data['trong_luong'],
            'don_gia' => $data['don_gia'],
            'tpdd' => $data['tpdd'],
            'loi_ich' => $data['loi_ich'],
            'hinh' => $data['hinh']
        ]);
    }

    // XÓA SẢN PHẨM (POST an toàn)
    public function delete($ma_sua)
    {
        $stmt = $this->pdo->prepare("DELETE FROM sua WHERE ma_sua = :ma");
        $stmt->execute(['ma' => $ma_sua]);
    }
}