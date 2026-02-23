-- ==================================================
-- DATABASE: ql_ban_sua
-- ==================================================

DROP TABLE IF EXISTS sua CASCADE;
DROP TABLE IF EXISTS hang_sua CASCADE;
DROP TABLE IF EXISTS khach_hang CASCADE;

-- ==================================================
-- TABLE: hang_sua
-- ==================================================

CREATE TABLE hang_sua (
    ma_hs VARCHAR(10) PRIMARY KEY,
    ten_hs VARCHAR(100) NOT NULL,
    dia_chi VARCHAR(255),
    dien_thoai VARCHAR(20),
    email VARCHAR(100) UNIQUE
);

CREATE INDEX idx_hang_sua_ten ON hang_sua(ten_hs);

-- ==================================================
-- INSERT: hang_sua
-- ==================================================

INSERT INTO hang_sua VALUES
('AB','Abbott','Công ty nhập khẩu Việt Nam','8741258','abbott@ab.com'),
('DL','Dutch Lady','Khu công nghiệp Biên Hòa - Đồng Nai','7826451','dutchlady@dl.com'),
('DM','Dumex','Khu công nghiệp Sóng Thần Bình Dương','6258943','dumex@dm.com'),
('DS','Daisy','Khu công nghiệp Sóng Thần Bình Dương','5789321','daisy@ds.com'),
('MJ','Mead Johnson','Công ty nhập khẩu Việt Nam','8741258','meadjohn@mj.com'),
('NTF','Nutifood','Khu công nghiệp Sóng Thần Bình Dương','7895632','nutifood@ntf.com'),
('VNM','Vinamilk','123 Nguyễn Du - Quận 1 - TP.HCM','8794561','vinamilk@vnm.com');

-- ==================================================
-- TABLE: khach_hang
-- ==================================================

CREATE TABLE khach_hang (
    ma_kh VARCHAR(10) PRIMARY KEY,
    ten_kh VARCHAR(100) NOT NULL,
    phai SMALLINT CHECK (phai IN (0,1)),
    dia_chi VARCHAR(255),
    dien_thoai VARCHAR(20)
);

CREATE INDEX idx_khach_hang_ten ON khach_hang(ten_kh);

-- ==================================================
-- TABLE: sua
-- ==================================================

CREATE TABLE sua (
    ma_sua VARCHAR(10) PRIMARY KEY,
    ten_sua VARCHAR(100) NOT NULL,
    ma_hang_sua VARCHAR(10) NOT NULL
        REFERENCES hang_sua(ma_hs)
        ON DELETE RESTRICT,
    loai_sua VARCHAR(50),
    trong_luong INTEGER NOT NULL CHECK (trong_luong > 0),
    don_gia INTEGER NOT NULL CHECK (don_gia > 0),
    thanh_phan_dinh_duong TEXT,
    loi_ich TEXT,
    hinh VARCHAR(100) DEFAULT 'default.jpg'
);

-- INDEX tối ưu truy vấn
CREATE INDEX idx_sua_mahang ON sua(ma_hang_sua);
CREATE INDEX idx_sua_ten ON sua(ten_sua);