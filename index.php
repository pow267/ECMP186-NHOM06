<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin các sản phẩm</title>
    <link rel="stylesheet" href="css/index.css?v=3">
</head>

<?php
include "logic.php";
?>

<body>
    <div class="box">
        <table>
            <tr>
                <th colspan="3" class="table-title">
                    THÔNG TIN CÁC SẢN PHẨM
                </th>
            </tr>
            <tr>
            <?php
            $count = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $count++;
                $hinh = !empty($row['Hinh']) ? $row['Hinh'] : 'default.jpg';
            ?>
                <td>
                    <div class="product-name">
                        <a href="?ma_sua=<?= $row['Ma_sua'] ?>#chitiet">
                            <?= $row['Ten_sua'] ?>
                        </a>
                    </div>

                    <div class="product-price">
                        <?= $row['Trong_luong'] ?> gr -
                        <?= number_format($row['Don_gia'], 0, ',', '.') ?> VND
                    </div>

                    <div class="img-box">
                        <img src="images/<?= $hinh ?>" alt="<?= $row['Ten_sua'] ?>">
                    </div>
                </td>
            <?php
                if ($count % 3 == 0) echo "</tr><tr>";
            }
            if ($count % 3 != 0) echo "</tr>";
            ?>
        </table>

        <div class="add-btn-box">
            <?php if (isset($_GET['action']) && $_GET['action'] == 'them') { ?>
                <a href="index.php" class="add-btn">ĐÓNG THÊM SỮA</a>
            <?php } else { ?>
                <a href="?action=them" class="add-btn">THÊM SỮA MỚI</a>
            <?php } ?>
        </div>

        <?php if (isset($_GET['action']) && $_GET['action'] == 'them') { ?>
        <form method="post" enctype="multipart/form-data" class="add-form">

            <div class="form-title">THÊM SỮA MỚI</div>
            
            <div class="form-row">
                <label>Mã sữa</label>
                <input type="text" value="<?= $ma_sua_auto ?>" readonly>
                <input type="hidden" name="ma_sua" value="<?= $ma_sua_auto ?>">
            </div>

            <div class="form-row">
                <label>Tên sữa</label>
                <input type="text" name="ten_sua" required>
            </div>

            <div class="form-row">
                <label>Hãng sữa</label>
                <select name="ma_hang_sua">
                    <?php
                    $hs = mysqli_query($conn, "SELECT * FROM hang_sua");
                    while ($h = mysqli_fetch_assoc($hs)) {
                        echo "<option value='{$h['ma_hs']}'>{$h['ten_hs']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-row">
                <label>Loại sữa</label>
                <select name="loai_sua">
                    <option>Sữa bột</option>
                    <option>Sữa tươi</option>
                    <option>Sữa chua</option>
                </select>
            </div>

            <div class="form-row">
                <label>Trọng lượng (gr)</label>
                <input type="number" name="trong_luong" required>
            </div>

            <div class="form-row">
                <label>Đơn giá (VND)</label>
                <input type="number" name="don_gia" required>
            </div>

            <div class="form-row">
                <label>Thành phần dinh dưỡng</label>
                <textarea name="tpdd"></textarea>
            </div>

            <div class="form-row">
                <label>Lợi ích</label>
                <textarea name="loi_ich"></textarea>
            </div>

            <div class="form-row">
                <label>Hình ảnh</label>
                <input type="file" name="hinh">
            </div>

            <div class="form-actions">
                <button type="submit" name="btn_them">Thêm mới</button>
            </div>
        </form>
        <?php } ?>

        <?php if ($chitiet) {
            $hinh_ct = !empty($chitiet['Hinh']) ? $chitiet['Hinh'] : 'default.jpg';
        ?>
        <hr>
        <div id="chitiet">
        <table class="detail-table">
            <tr>
                <th colspan="2" class="table-title">
                    <?= $chitiet['Ten_sua'] ?> - <?= $chitiet['ten_hs'] ?>
                </th>
            </tr>

            <tr>
                <td class="detail-image">
                    <div class="img-box">
                        <img src="images/<?= $hinh_ct ?>" alt="<?= $chitiet['Ten_sua'] ?>">
                    </div>
                </td>
                
                <td class="detail-info">
                    <div class="detail-block">
                        <div class="detail-title">Thành phần dinh dưỡng:</div>
                        <div class="detail-content">
                            <?= $chitiet['Thanh_phan_dinh_duong'] ?>
                        </div>
                    </div>

                    <div class="detail-block">
                        <div class="detail-title">Lợi ích:</div>
                        <div class="detail-content">
                            <?= $chitiet['Loi_ich'] ?>
                        </div>
                    </div>

                    <div class="detail-price">
                        Trọng lượng: <?= $chitiet['Trong_luong'] ?> gr -
                        Đơn giá: <?= number_format($chitiet['Don_gia'], 0, ',', '.') ?> VND
                    </div>
                </td>
            </tr>
        </table>
        </div>
        <?php } ?>
    </div>

</body>
</html>
