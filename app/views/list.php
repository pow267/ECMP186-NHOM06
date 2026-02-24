<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin các sản phẩm</title>
    <link rel="stylesheet" href="/assets/css/output.css">
</head>

<body>

<div class="box">

    <div class="table-title">
        THÔNG TIN CÁC SẢN PHẨM
    </div>

    <!-- TOAST -->
    <?php if (!empty($_SESSION['flash'])): ?>
        <div id="toast" class="toast">
            <?= $_SESSION['flash']; unset($_SESSION['flash']); ?>
        </div>
        <script>
            setTimeout(() => {
                const t = document.getElementById('toast');
                if (t) t.style.opacity = '0';
            }, 2500);
        </script>
    <?php endif; ?>

    <!-- SEARCH -->
    <div class="search-section">
        <input type="text"
               id="searchInput"
               placeholder="Tìm kiếm sản phẩm..."
               class="search-input">
    </div>

    <!-- ================= DANH SÁCH ================= -->
    <div class="product-grid">
        <?php foreach ($products as $row):
            $hinh = !empty($row['hinh']) ? $row['hinh'] : 'default.jpg';
        ?>
        <div class="product-card">

            <div class="product-name">
                <a href="?ma_sua=<?= htmlspecialchars($row['ma_sua']) ?>&page=<?= $page ?>#chitiet">
                    <?= htmlspecialchars($row['ten_sua']) ?>
                </a>
            </div>

            <div class="product-price">
                <?= htmlspecialchars($row['trong_luong']) ?> gr -
                <?= number_format($row['don_gia'], 0, ',', '.') ?> VND
            </div>

            <div class="img-box">
                <img loading="lazy"
                     src="/assets/images/<?= htmlspecialchars($hinh) ?>"
                     alt="Hình sản phẩm">
            </div>

        </div>
        <?php endforeach; ?>
    </div>

    <!-- ================= PAGINATION ================= -->
    <?php if (!empty($totalPages) && $totalPages > 1): ?>
    <div class="pagination">

        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>" class="page-btn">« Trước</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>"
               class="page-btn <?= $i == $page ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>" class="page-btn">Sau »</a>
        <?php endif; ?>

    </div>
    <?php endif; ?>

    <!-- ================= NÚT ================= -->
    <div class="add-btn-box">

        <a href="?action=them&page=<?= $page ?>" class="add-btn">
            THÊM SỮA MỚI
        </a>

        <?php if (isset($_GET['ma_sua'])): ?>

            <a href="?action=sua&ma_sua=<?= htmlspecialchars($_GET['ma_sua']) ?>&page=<?= $page ?>#formsua"
               class="add-btn">
                SỬA THÔNG TIN
            </a>

            <form method="POST" class="inline-block">
                <input type="hidden" name="ma_sua"
                       value="<?= htmlspecialchars($_GET['ma_sua']) ?>">
                <input type="hidden" name="action" value="xoa">
                <input type="hidden" name="csrf_token"
                       value="<?= $_SESSION['csrf_token'] ?>">
                <button type="submit"
                        class="add-btn"
                        onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                    XÓA SẢN PHẨM
                </button>
            </form>

        <?php endif; ?>
    </div>

    <!-- ================= CHI TIẾT ================= -->
    <?php if (!empty($chitiet)): ?>

    <div class="detail-box" id="chitiet">

        <div class="form-title">CHI TIẾT SẢN PHẨM</div>

        <div class="detail-content">

            <div class="detail-img">
                <img loading="lazy"
                     src="/assets/images/<?= htmlspecialchars($chitiet['hinh'] ?? 'default.jpg') ?>">
            </div>

            <div class="detail-info">

                <p><strong>Tên sữa:</strong>
                    <?= htmlspecialchars($chitiet['ten_sua']) ?>
                </p>

                <p><strong>Hãng sữa:</strong>
                    <?= htmlspecialchars($chitiet['ma_hang_sua']) ?>
                </p>

                <p><strong>Loại sữa:</strong>
                    <?= htmlspecialchars($chitiet['loai_sua']) ?>
                </p>

                <p><strong>Thành phần dinh dưỡng:</strong><br>
                    <?= nl2br(htmlspecialchars($chitiet['tpdd'])) ?>
                </p>

                <p><strong>Lợi ích:</strong><br>
                    <?= nl2br(htmlspecialchars($chitiet['loi_ich'])) ?>
                </p>

                <p><strong>Trọng lượng:</strong>
                    <?= htmlspecialchars($chitiet['trong_luong']) ?> gr
                </p>

                <p><strong>Đơn giá:</strong>
                    <?= number_format($chitiet['don_gia'], 0, ',', '.') ?> VND
                </p>

            </div>

        </div>

    </div>

    <?php endif; ?>

    <!-- ================= FORM THÊM ================= -->
    <?php if (isset($_GET['action']) && $_GET['action'] === 'them'): ?>

    <div class="add-form">

        <div class="form-title">THÊM SỮA MỚI</div>

        <form method="POST" enctype="multipart/form-data">

            <input type="hidden" name="csrf_token"
                   value="<?= $_SESSION['csrf_token'] ?>">

            <div class="form-row">
                <label>Mã sữa</label>
                <input type="text" name="ma_sua"
                       value="<?= $ma_sua_auto ?>" readonly>
            </div>

            <div class="form-row">
                <label>Tên sữa</label>
                <input type="text" name="ten_sua" required>
            </div>

            <div class="form-row">
                <label>Hãng sữa</label>
                <select name="ma_hang_sua">
                    <?php foreach ($hangSua as $hang): ?>
                        <option value="<?= $hang['ma_hang_sua'] ?>">
                            <?= $hang['ten_hang_sua'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <label>Loại sữa</label>
                <input type="text" name="loai_sua">
            </div>

            <div class="form-row">
                <label>Trọng lượng</label>
                <input type="number" name="trong_luong" required>
            </div>

            <div class="form-row">
                <label>Đơn giá</label>
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
                <button type="submit" name="btn_them">
                    Thêm mới
                </button>
            </div>

        </form>

    </div>

    <?php endif; ?>

    <!-- ================= FORM SỬA ================= -->
    <?php if (isset($_GET['action']) && $_GET['action'] === 'sua' && !empty($chitiet)): ?>

    <div class="add-form" id="formsua">

        <div class="form-title">SỬA THÔNG TIN SẢN PHẨM</div>

        <form method="POST" enctype="multipart/form-data">

            <input type="hidden" name="csrf_token"
                   value="<?= $_SESSION['csrf_token'] ?>">

            <input type="hidden" name="hinh_cu"
                   value="<?= htmlspecialchars($chitiet['hinh'] ?? 'default.jpg') ?>">

            <div class="form-row">
                <label>Mã sữa</label>
                <input type="text" name="ma_sua"
                       value="<?= htmlspecialchars($chitiet['ma_sua']) ?>" readonly>
            </div>

            <div class="form-row">
                <label>Tên sữa</label>
                <input type="text" name="ten_sua"
                       value="<?= htmlspecialchars($chitiet['ten_sua']) ?>" required>
            </div>

            <div class="form-row">
                <label>Hãng sữa</label>
                <select name="ma_hang_sua">
                    <?php foreach ($hangSua as $hang): ?>
                        <option value="<?= $hang['ma_hang_sua'] ?>"
                            <?= ($hang['ma_hang_sua'] == $chitiet['ma_hang_sua']) ? 'selected' : '' ?>>
                            <?= $hang['ten_hang_sua'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <label>Loại sữa</label>
                <input type="text" name="loai_sua"
                       value="<?= htmlspecialchars($chitiet['loai_sua']) ?>">
            </div>

            <div class="form-row">
                <label>Trọng lượng</label>
                <input type="number" name="trong_luong"
                       value="<?= htmlspecialchars($chitiet['trong_luong']) ?>" required>
            </div>

            <div class="form-row">
                <label>Đơn giá</label>
                <input type="number" name="don_gia"
                       value="<?= htmlspecialchars($chitiet['don_gia']) ?>" required>
            </div>

            <div class="form-row">
                <label>Thành phần dinh dưỡng</label>
                <textarea name="tpdd"><?= htmlspecialchars($chitiet['tpdd']) ?></textarea>
            </div>

            <div class="form-row">
                <label>Lợi ích</label>
                <textarea name="loi_ich"><?= htmlspecialchars($chitiet['loi_ich']) ?></textarea>
            </div>

            <div class="form-row">
                <label>Hình ảnh mới</label>
                <input type="file" name="hinh">
            </div>

            <div class="form-actions">
                <button type="submit" name="btn_capnhat">
                    Cập nhật
                </button>
            </div>

        </form>

    </div>

    <?php endif; ?>

</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    let value = this.value.toLowerCase();
    let cards = document.querySelectorAll('.product-card');
    cards.forEach(card => {
        let name = card.querySelector('.product-name').innerText.toLowerCase();
        card.style.display = name.includes(value) ? 'flex' : 'none';
    });
});
</script>

</body>
</html>