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
        try {
            $sql = "SELECT ma_sua, ten_sua, trong_luong, don_gia, hinh
                    FROM sua
                    ORDER BY CAST(SUBSTRING(ma_sua FROM 2) AS INTEGER) ASC";

            return $this->pdo
                ->query($sql)
                ->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function getById($ma_sua)
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    ma_sua,
                    ten_sua,
                    ma_hang_sua,
                    loai_sua,
                    trong_luong,
                    don_gia,
                    thanh_phan_dinh_duong AS tpdd,
                    loi_ich,
                    hinh
                FROM sua
                WHERE ma_sua = :ma
            ");

            $stmt->execute(['ma' => $ma_sua]);

            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function getHangSua()
    {
        try {
            $sql = "
                SELECT 
                    ma_hs AS ma_hang_sua,
                    ten_hs AS ten_hang_sua
                FROM hang_sua
                ORDER BY ten_hs ASC
            ";

            return $this->pdo
                ->query($sql)
                ->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            error_log($e->getMessage());
            return [];
        }
    }

    public function generateMaSua()
    {
        try {
            $stmt = $this->pdo->query("
                SELECT COALESCE(MAX(CAST(SUBSTRING(ma_sua FROM 2) AS INTEGER)), 0) AS max_number
                FROM sua
            ");

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $so = intval($row['max_number']) + 1;

            return 'S' . str_pad($so, 2, '0', STR_PAD_LEFT);

        } catch (Exception $e) {
            error_log($e->getMessage());
            return 'S01';
        }
    }

    public function insert($data)
    {
        try {
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

        } catch (Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }
    }

    public function update($data)
    {
        try {
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

        } catch (Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }
    }

    public function delete($ma_sua)
    {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM sua WHERE ma_sua = :ma");
            $stmt->execute(['ma' => $ma_sua]);

        } catch (Exception $e) {
            error_log($e->getMessage());
            throw $e;
        }
    }
}