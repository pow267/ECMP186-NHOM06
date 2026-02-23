<?php

require_once __DIR__ . '/../../config/Database.php';

class SuaModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /* ===== PAGINATION ===== */

    public function countAll()
    {
        return $this->pdo->query("SELECT COUNT(*) FROM sua")
                         ->fetchColumn();
    }

    public function getPaginated($limit, $offset)
    {
        $stmt = $this->pdo->prepare("
            SELECT ma_sua, ten_sua, trong_luong, don_gia, hinh
            FROM sua
            ORDER BY ma_sua ASC
            LIMIT :limit OFFSET :offset
        ");

        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ===== BASIC CRUD ===== */

    public function getAll()
    {
        $stmt = $this->pdo->prepare("
            SELECT ma_sua, ten_sua, trong_luong, don_gia, hinh
            FROM sua
            ORDER BY ma_sua ASC        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($ma_sua)
    {
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
    }

    public function getHangSua()
    {
        $stmt = $this->pdo->prepare("
            SELECT ma_hs AS ma_hang_sua,
                   ten_hs AS ten_hang_sua
            FROM hang_sua
            ORDER BY ten_hs ASC
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function generateMaSua()
    {
        $stmt = $this->pdo->query("
            SELECT COALESCE(MAX(CAST(SUBSTRING(ma_sua FROM 2) AS INTEGER)),0) AS max_num
            FROM sua
        ");

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $next = (int)$row['max_num'] + 1;

        return 'S' . str_pad($next, 2, '0', STR_PAD_LEFT);
    }

    public function insert($data)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO sua
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

        $stmt->execute($data);
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

        $stmt->execute($data);
    }

    public function delete($ma_sua)
    {
        $stmt = $this->pdo->prepare("DELETE FROM sua WHERE ma_sua = :ma");
        $stmt->execute(['ma' => $ma_sua]);
    }
}