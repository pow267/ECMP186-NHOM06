<?php
$databaseUrl = getenv('DATABASE_URL');

try {
    $pdo = new PDO($databaseUrl);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

/* =========================
   TẠO MÃ SỮA TỰ ĐỘNG
========================= */
$stmt = $pdo->query('SELECT MAX("Ma_sua") AS max_ma FROM sua');
$row_ma_form = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row_ma_form || $row_ma_form['max_ma'] == null) {
    $ma_sua_auto = 'S01';
} else {
    $so = intval(substr($row_ma_form['max_ma'], 1)) + 1;
    $ma_sua_auto = 'S' . str_pad($so, 2, '0', STR_PAD_LEFT);
}

/* =========================
   DANH SÁCH SỮA
========================= */
$sql = '
    SELECT "Ma_sua", "Ten_sua", "Trong_luong",
           "Don_gia", "Hinh"
    FROM sua
';
$result = $pdo->query($sql);

/* =========================
   CHI TIẾT
========================= */
$chitiet = null;

if (isset($_GET['ma_sua'])) {
    $ma = $_GET['ma_sua'];

    $stmt = $pdo->prepare('
        SELECT sua.*, hang_sua.ten_hs
        FROM sua
        JOIN hang_sua
        ON sua."Ma_hang_sua" = hang_sua.ma_hs
        WHERE sua."Ma_sua" = :ma
    ');

    $stmt->execute(['ma' => $ma]);
    $chitiet = $stmt->fetch(PDO::FETCH_ASSOC);
}

/* =========================
   THÊM SỮA
========================= */
if (isset($_POST['btn_them'])) {

    $hinh = '';

    if (!empty($_FILES['hinh']['name'])) {
        $hinh = $_FILES['hinh']['name'];
        move_uploaded_file($_FILES['hinh']['tmp_name'], "images/$hinh");
    }

    $stmt = $pdo->prepare('
        INSERT INTO sua (
            "Ma_sua",
            "Ten_sua",
            "Ma_hang_sua",
            "Loai_sua",
            "Trong_luong",
            "Don_gia",
            "Thanh_phan_dinh_duong",
            "Loi_ich",
            "Hinh"
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
    ');

    $stmt->execute([
        'ma_sua' => $_POST['ma_sua'],
        'ten_sua' => $_POST['ten_sua'],
        'ma_hang_sua' => $_POST['ma_hang_sua'],
        'loai_sua' => $_POST['loai_sua'],
        'trong_luong' => $_POST['trong_luong'],
        'don_gia' => $_POST['don_gia'],
        'tpdd' => $_POST['tpdd'],
        'loi_ich' => $_POST['loi_ich'],
        'hinh' => $hinh
    ]);

    header("Location: index.php");
    exit;
}