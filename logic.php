<?php
$conn = mysqli_connect("localhost", "root", "", "ql_ban_sua");
mysqli_set_charset($conn, "utf8");
$rs_ma_form = mysqli_query($conn, "SELECT MAX(Ma_sua) AS max_ma FROM sua");
$row_ma_form = mysqli_fetch_assoc($rs_ma_form);

if ($row_ma_form['max_ma'] == null) {
    $ma_sua_auto = 'S01';
} else {
    $so = intval(substr($row_ma_form['max_ma'], 1)) + 1;
    $ma_sua_auto = 'S' . str_pad($so, 2, '0', STR_PAD_LEFT);
}

$sql = 
    "SELECT sua.Ma_sua, sua.Ten_sua, sua.Trong_luong,
           sua.Don_gia, sua.Hinh
    FROM sua";
$result = mysqli_query($conn, $sql);
$chitiet = null;
if (isset($_GET['ma_sua'])) {
    $ma = mysqli_real_escape_string($conn, $_GET['ma_sua']);
    $rs = mysqli_query($conn, "
        SELECT sua.*, hang_sua.ten_hs
        FROM sua JOIN hang_sua
        ON sua.Ma_hang_sua = hang_sua.ma_hs
        WHERE sua.Ma_sua='$ma'");

    if (mysqli_num_rows($rs) > 0) {
        $chitiet = mysqli_fetch_assoc($rs);
    }
}

if (isset($_POST['btn_them'])) {
    $ma_sua = $_POST['ma_sua'];
    $ten_sua = $_POST['ten_sua'];
    $ma_hang_sua = $_POST['ma_hang_sua'];
    $loai_sua = $_POST['loai_sua'];
    $trong_luong = $_POST['trong_luong'];
    $don_gia = $_POST['don_gia'];
    $tpdd = $_POST['tpdd'];
    $loi_ich = $_POST['loi_ich'];
    $hinh = '';

    if (!empty($_FILES['hinh']['name'])) {
        $hinh = $_FILES['hinh']['name'];
        move_uploaded_file($_FILES['hinh']['tmp_name'], "images/$hinh");
    }

    mysqli_query($conn, 
        "INSERT INTO sua
        VALUES ('$ma_sua','$ten_sua','$ma_hang_sua','$loai_sua',
                '$trong_luong','$don_gia','$tpdd','$loi_ich','$hinh')");

    header("Location: index.php");
    exit;
}
