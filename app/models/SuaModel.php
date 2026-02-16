<?php

require_once __DIR__ . '/../../config/Database.php';

class SuaModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public function getAll()
    {
        $sql = "SELECT ma_sua, ten_sua, trong_luong, don_gia, hinh FROM sua";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function getById($ma_sua)
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM sua
            WHERE ma_sua = :ma
        ");

        $stmt->execute(['ma' => $ma_sua]);
        return $stmt->fetch();
    }

    public function getHangSua()
    {
        return $this->pdo->query("
            SELECT 
                ma_hs AS ma_hang_sua,
                ten_hs AS ten_hang_sua
            FROM hang_sua
        ")->fetchAll();
    }

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

    public function update($data)
    {
        $stmt = $this->pdo->prepare("
            UPDATE sua SET
                ten_sua = :ten_sua,
                ma_hang_sua = :ma_hang_sua,
                loai_sua = :loai_sua,
                trong_luong = :trong_luong,
                don_gia = :don_gia,
                thanh_phan_dinh_duong = :tpdd,
                loi_ich = :loi_ich,
                hinh = :hinh
            WHERE ma_sua = :ma_sua
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

    public function delete($ma_sua)
    {
        $stmt = $this->pdo->prepare("DELETE FROM sua WHERE ma_sua = :ma");
        $stmt->execute(['ma' => $ma_sua]);
    }
}