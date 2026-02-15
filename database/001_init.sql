-- ===============================
-- DATABASE: ql_ban_sua (PostgreSQL)
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

INSERT INTO khach_hang VALUES
('kh001','Khuất Thùy Phương',1,'A21 Nguyễn Oanh quận Gò Vấp','9874125'),
('kh002','Đỗ Lâm Thiên',0,'357 Lê Hồng Phong Q.10','8351056'),
('kh003','Phạm Thị Nhung',1,'56 Đinh Tiên Hoàng quận 1','9745698'),
('kh004','Nguyễn Khắc Thiện',0,'12bis Đường 3-2 quận 10','8769128'),
('kh005','Tô Trần Hồ Giảng',0,'75 Nguyễn Kiệm quận Gò Vấp','5792564'),
('kh006','Nguyễn Kiên Thi',1,'357 Lê Hồng Phong Q.10','9874125'),
('kh008','Nguyễn Anh Tuấn',0,'1/2bis Nơ Trang Long Q.BT TP.HCM','8753159');

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

INSERT INTO sua VALUES
('S01','Fristi','DL','Sữa tươi',180,3600,
'Sữa tươi tiệt trùng, nước, đường, đạm sữa, canxi, vitamin A, vitamin D, hương dâu tự nhiên.',
'Giúp cung cấp năng lượng nhanh, bổ sung canxi và vitamin giúp trẻ phát triển chiều cao và tăng cường sức đề kháng.',
'fristi.jpg'),

('S02','Sữa chua Plus','VNM','Sữa chua',120,4000,
'Giúp cung cấp năng lượng nhanh, bổ sung canxi và vitamin giúp trẻ phát triển chiều cao và tăng cường sức đề kháng.',
'Hỗ trợ tiêu hóa, tăng cường hệ vi sinh đường ruột, giúp hấp thu dinh dưỡng tốt hơn và tăng sức đề kháng.',
'suachuaplus.jpg'),

('S03','Sữa Chua Cô Gái Hà Lan','DL','Sữa chua',100,3000,
'Sữa tươi, men sữa chua, canxi, protein, vitamin A, vitamin D, khoáng chất thiết yếu.',
'Giúp hệ tiêu hóa khỏe mạnh, bổ sung canxi cho xương chắc khỏe, hỗ trợ phát triển toàn diện.',
'suachuacogaihalan.jpg'),

('S04','Dielac Sure','VNM','Sữa bột',400,90000,
'Sữa bột, đạm sữa, canxi, vitamin D, vitamin B12, sắt, kẽm và các khoáng chất.',
'Giúp bổ sung dinh dưỡng cho người lớn tuổi, hỗ trợ xương khớp chắc khỏe và tăng cường sức khỏe tổng thể.',
'dielacsure.jpg'),

('S05','Similac Neo Sure','AB','Sữa bột',370,145000,
'Sữa bột, đạm whey, DHA, ARA, canxi, vitamin D, vitamin E, các khoáng chất vi lượng.',
'Hỗ trợ phát triển trí não và thể chất cho trẻ sinh non, giúp tăng cân khỏe mạnh và phát triển toàn diện.',
'similacneosure.jpg'),

('S06','Abbott Pedia Sure','AB','Sữa bột',400,146000,
'Sữa bột, protein, DHA, vitamin A, C, D, E, canxi, sắt, kẽm và khoáng chất.',
'Giúp trẻ tăng cân, phát triển chiều cao, bổ sung đầy đủ dưỡng chất cho trẻ biếng ăn.',
'abbottpediasure.png'),

('S07','Sữa Tươi Tiệt Trùng Vinamilk 100% Có Đường','VNM','Sữa tươi',220,8600,
'Sữa tươi tiệt trùng 100%, đường, canxi, protein, vitamin A, D.',
'Bổ sung canxi và protein giúp xương chắc khỏe, cung cấp năng lượng cho cơ thể mỗi ngày.',
'suatuoivinamilk.jpg'),

('S08','Dutch Lady Kids Chocolate Milk 4X125ml','DL','Sữa bột',500,69000,
'Sữa tươi tiệt trùng, đường, bột cacao, canxi, protein, vitamin A, D.',
'Cung cấp năng lượng, bổ sung canxi giúp xương chắc khỏe, phù hợp cho trẻ em.',
'DutchLadyKidsChocolateMilk.jpg'),

('S09','Sữa bột Vinamilk ColosGold số 1 800g cho bé từ 0-1 tuổi - Kids Plaza',
'VNM','Sữa bột',800,550000,
'Sữa non 24h (Colostrum) chứa IgG, HMO, Probiotics (BB-12, LGG), DHA, ARA, Choline, Taurine, Vitamin và khoáng chất.',
'Tăng cường hệ miễn dịch, hỗ trợ tiêu hóa, phát triển trí não và thị giác.',
'sua-bot-vinamilk-colos-glod-so-1-800g.jpg');