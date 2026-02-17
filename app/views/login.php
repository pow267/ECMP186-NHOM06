<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="/assets/css/output.css">
</head>
<body>

<div class="box max-w-[420px] mt-24">

    <div class="table-title">
        ĐĂNG NHẬP HỆ THỐNG
    </div>

    <form method="POST">

        <div class="form-row">
            <label>Tên đăng nhập</label>
            <input type="text" name="username" required>
        </div>

        <div class="form-row">
            <label>Mật khẩu</label>
            <input type="password" name="password" required>
        </div>

        <?php if (!empty($error)): ?>
            <div class="text-red-500 text-center mb-4 font-semibold">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div class="form-actions">
            <button type="submit" name="btn_login">
                Đăng nhập
            </button>
        </div>

    </form>

</div>

</body>
</html>