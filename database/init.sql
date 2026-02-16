-- ===============================
-- DATABASE: ql_ban_sua
-- ===============================

DROP TABLE IF EXISTS sua CASCADE;
DROP TABLE IF EXISTS hang_sua CASCADE;
DROP TABLE IF EXISTS khach_hang CASCADE;

-- ===============================
-- TABLE: hang_sua
-- ===============================

CREATE TABLE hang_sua (
    ma_hs VARCHAR(10) PRIMARY KEY,
    ten_hs VARCHAR(100),
    dia_chi VARCHAR(255),
    dien_thoai VARCHAR(20),
    email VARCHAR(100)
);

-- ===============================
-- INSERT: hang_sua
-- ===============================

INSERT INTO hang_sua VALUES
('AB','Abbott','Công ty nhập khẩu Việt Nam','8741258','abbott@ab.com'),
('DL','Dutch Lady','Khu công nghiệp Biên Hòa - Đồng Nai','7826451','dutchlady@dl.com'),
('DM','Dumex','Khu công nghiệp Sóng Thần Bình Dương','6258943','dumex@dm.com'),
('DS','Daisy','Khu công nghiệp Sóng Thần Bình Dương','5789321','daisy@ds.com'),
('MJ','Mead Johnson','Công ty nhập khẩu Việt Nam','8741258','meadjohn@mj.com'),
('NTF','Nutifood','Khu công nghiệp Sóng Thần Bình Dương','7895632','nutifood@ntf.com'),
('VNM','Vinamilk','123 Nguyễn Du - Quận 1 - TP.HCM','8794561','vinamilk@vnm.com');

-- ===============================
-- TABLE: khach_hang
-- ===============================

CREATE TABLE khach_hang (
    ma_kh VARCHAR(10) PRIMARY KEY,
    ten_kh VARCHAR(100),
    phai INTEGER,
    dia_chi VARCHAR(255),
    dien_thoai VARCHAR(20)
);

-- ===============================
-- TABLE: sua
-- ===============================

CREATE TABLE sua (
    Ma_sua VARCHAR(10) PRIMARY KEY,
    Ten_sua VARCHAR(100),
    Ma_hang_sua VARCHAR(10) REFERENCES hang_sua(ma_hs),
    Loai_sua VARCHAR(50),
    Trong_luong INTEGER,
    Don_gia INTEGER,
    Thanh_phan_dinh_duong TEXT,
    Loi_ich TEXT,
    Hinh VARCHAR(100)
);