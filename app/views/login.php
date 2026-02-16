<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập Demo</title>
    <link rel="stylesheet" href="/assets/css/index.css">
</head>
<body>

<div class="box" style="max-width:400px;margin-top:100px;">

    <div class="table-title">
        ĐĂNG NHẬP DEMO
    </div>

    <form method="POST">

        <div class="form-row">
            <label>Tên đăng nhập:</label>
            <input type="text" name="username" required>
        </div>

        <div class="form-row">
            <label>Mật khẩu:</label>
            <input type="password" name="password" required>
        </div>

        <?php if (!empty($error)): ?>
            <div style="color:red;text-align:center;">
                <?= $error ?>
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