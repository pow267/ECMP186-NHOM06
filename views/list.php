<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin các sản phẩm</title>
    <link rel="stylesheet" href="/css/index.css?v=3">
</head>

<body>
<div class="box">

    <?php if (isset($_GET['deleted'])) { ?>
        <div style="color:green; margin-bottom:10px;">
            Xóa sản phẩm thành công!
        </div>
    <?php } ?>

    <?php if (isset($_GET['success'])) { ?>
        <div style="color:green; margin-bottom:10px;">
            Thêm sản phẩm thành công!
        </div>
    <?php } ?>

    <table>
        <tr>
            <th colspan="3" class="table-title">
                THÔNG TIN CÁC SẢN PHẨM
            </th>
        </tr>
        <tr>

        <?php
        $count = 0;
        foreach ($products as $row) {
            $count++;
            $hinh = !empty($row['hinh']) ? $row['hinh'] : 'default.jpg';
        ?>

            <td>
                <div class="product-name">
                    <a href="?ma_sua=<?= htmlspecialchars($row['ma_sua']) ?>#chitiet">
                        <?= htmlspecialchars($row['ten_sua']) ?>
                    </a>
                </div>

                <div class="product-price">
                    <?= htmlspecialchars($row['trong_luong']) ?> gr -
                    <?= number_format($row['don_gia'], 0, ',', '.') ?> VND
                </div>

                <div class="img-box">
                    <img src="/images/<?= htmlspecialchars($hinh) ?>"
                         alt="<?= htmlspecialchars($row['ten_sua']) ?>">
                </div>
            </td>

        <?php
            if ($count % 3 == 0) echo "</tr><tr>";
        }
        if ($count % 3 != 0) echo "</tr>";
        ?>
    </table>

    <!-- NÚT THÊM + XÓA CHI TIẾT -->
    <div class="add-btn-box" style="margin-top:20px;">

        <a href="?action=them" class="add-btn">THÊM SỮA MỚI</a>

        <?php if (!empty($chitiet)) { ?>
            <form method="POST"
                  style="display:inline;"
                  onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">

                <input type="hidden" name="action" value="xoa">
                <input type="hidden" name="ma_sua"
                       value="<?= htmlspecialchars($chitiet['ma_sua']) ?>">

                <button type="submit"
                        class="add-btn"
                        style="margin-left:10px;">
                    XÓA SẢN PHẨM
                </button>
            </form>
        <?php } ?>

    </div>

    <!-- FORM THÊM -->
    <?php if (isset($_GET['action']) && $_GET['action'] == 'them') { ?>
    <form method="post" enctype="multipart/form-data" class="add-form">

        <div class="form-title">THÊM SỮA MỚI</div>

        <div class="form-row">
            <label>Mã sữa</label>
            <input type="text" value="<?= htmlspecialchars($ma_sua_auto) ?>" readonly>
            <input type="hidden" name="ma_sua"
                   value="<?= htmlspecialchars($ma_sua_auto) ?>">
        </div>

        <div class="form-row">
            <label>Tên sữa</label>
            <input type="text" name="ten_sua" required>
        </div>

        <div class="form-row">
            <label>Hãng sữa</label>
            <select name="ma_hang_sua">
                <?php foreach ($hangSua as $h) { ?>
                    <option value="<?= htmlspecialchars($h['ma_hs']) ?>">
                        <?= htmlspecialchars($h['ten_hs']) ?>
                    </option>
                <?php } ?>
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

    <!-- CHI TIẾT -->
    <?php if (!empty($chitiet)) {
        $hinh_ct = !empty($chitiet['hinh']) ? $chitiet['hinh'] : 'default.jpg';
    ?>
    <hr>
    <div id="chitiet">
        <table class="detail-table">
            <tr>
                <th colspan="2" class="table-title">
                    <?= htmlspecialchars($chitiet['ten_sua']) ?>
                    - <?= htmlspecialchars($chitiet['ten_hs']) ?>
                </th>
            </tr>

            <tr>
                <td class="detail-image">
                    <div class="img-box">
                        <img src="/images/<?= htmlspecialchars($hinh_ct) ?>"
                             alt="<?= htmlspecialchars($chitiet['ten_sua']) ?>">
                    </div>
                </td>

                <td class="detail-info">

                    <div class="detail-block">
                        <div class="detail-title">Thành phần dinh dưỡng:</div>
                        <div class="detail-content">
                            <?= htmlspecialchars($chitiet['thanh_phan_dinh_duong']) ?>
                        </div>
                    </div>

                    <div class="detail-block">
                        <div class="detail-title">Lợi ích:</div>
                        <div class="detail-content">
                            <?= htmlspecialchars($chitiet['loi_ich']) ?>
                        </div>
                    </div>

                    <div class="detail-price">
                        Trọng lượng:
                        <?= htmlspecialchars($chitiet['trong_luong']) ?> gr -
                        Đơn giá:
                        <?= number_format($chitiet['don_gia'], 0, ',', '.') ?> VND
                    </div>

                </td>
            </tr>
        </table>
    </div>
    <?php } ?>

</div>
</body>
</html>