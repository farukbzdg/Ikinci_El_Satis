DROP DATABASE IF EXISTS ikinci_el_satis;
CREATE DATABASE ikinci_el_satis;
USE ikinci_el_satis;

CREATE TABLE kategoriler (
    kategori_id INT AUTO_INCREMENT PRIMARY KEY,
    kategori_adi VARCHAR(100) NOT NULL UNIQUE
);

CREATE TABLE kullanicilar (
    kullanici_id INT AUTO_INCREMENT PRIMARY KEY,
    ad VARCHAR(50) NOT NULL,
    soyad VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    sifre VARCHAR(255) NOT NULL,
    telefon VARCHAR(20),
    kayit_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE urunler (
    urun_id INT AUTO_INCREMENT PRIMARY KEY,
    kullanici_id INT NOT NULL,
    kategori_id INT NOT NULL,
    urun_adi VARCHAR(100) NOT NULL,
    fiyat DECIMAL(10,2) NOT NULL CHECK (fiyat > 0),
    aciklama TEXT,
    fotograf VARCHAR(255),
    eklenme_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kullanici_id) REFERENCES kullanicilar(kullanici_id),
    FOREIGN KEY (kategori_id) REFERENCES kategoriler(kategori_id)
);

CREATE TABLE satislar (
    satis_id INT AUTO_INCREMENT PRIMARY KEY,
    urun_id INT NOT NULL UNIQUE,
    satis_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (urun_id) REFERENCES urunler(urun_id)
);

CREATE TABLE odemeler (
    odeme_id INT AUTO_INCREMENT PRIMARY KEY,
    satis_id INT NOT NULL,
    tutar DECIMAL(10,2) NOT NULL CHECK (tutar > 0),
    odeme_tarihi TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (satis_id) REFERENCES satislar(satis_id)
);

CREATE TABLE log_kayitlari (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    tablo_adi VARCHAR(50),
    islem_turu VARCHAR(50),
    aciklama TEXT,
    tarih TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DELIMITER $$

CREATE TRIGGER trg_urun_insert
AFTER UPDATE ON urunler
FOR EACH ROW
BEGIN
	INSERT INTO log_kayitlari
    VALUES(NULL,'urunler','INSERT',
	CONCAT('Ürün eklendi: ', NEW.urun_id),NOW());
END$$

CREATE TRIGGER trg_urun_update
AFTER UPDATE ON urunler
FOR EACH ROW
BEGIN
    INSERT INTO log_kayitlari
    VALUES (NULL,'urunler','UPDATE',
    CONCAT('Ürün güncellendi ID=', NEW.urun_id), NOW());
END$$

CREATE TRIGGER trg_urun_delete
AFTER DELETE ON urunler
FOR EACH ROW
BEGIN
    INSERT INTO log_kayitlari
    VALUES (NULL,'urunler','DELETE',
    CONCAT('Ürün silindi ID=', OLD.urun_id), NOW());
END$$

DELIMITER ;

DELIMITER $$

CREATE PROCEDURE urun_ekle(
    IN p_kullanici INT,
    IN p_kategori INT,
    IN p_adi VARCHAR(100),
    IN p_fiyat DECIMAL(10,2),
    IN p_aciklama TEXT,
    IN p_fotograf VARCHAR(255)
)
BEGIN
    INSERT INTO urunler (
        kullanici_id,
        kategori_id,
        urun_adi,
        fiyat,
        aciklama,
        fotograf
    )
    VALUES (
        p_kullanici,
        p_kategori,
        p_adi,
        p_fiyat,
        p_aciklama,
        p_fotograf
    );
END$$

CREATE PROCEDURE urun_sil (IN p_urun INT)
BEGIN
	DELETE FROM urunler WHERE urun_id=p_urun;
END$$


CREATE PROCEDURE toplam_satis()
BEGIN
    SELECT SUM(tutar) AS toplam_ciro FROM odemeler;
END$$

DELIMITER ;

CREATE VIEW vw_urun_detay AS
SELECT
	u.urun_id,
    u.urun_adi,
    u.fiyat,
    u.aciklama,
    u.fotograf,
    k.kategori_adi
FROM urunler u
JOIN kategoriler k ON u.kategori_id=k.kategori_id;

CREATE VIEW vw_satislar AS
SELECT 
    s.satis_id,
    u.urun_adi,
    o.tutar,
    o.odeme_tarihi
FROM satislar s
JOIN urunler u ON s.urun_id = u.urun_id
JOIN odemeler o ON s.satis_id = o.satis_id;

CREATE VIEW vw_kullanici_urun_sayisi AS
SELECT 
    k.ad,
    k.soyad,
    COUNT(u.urun_id) AS urun_sayisi
FROM kullanicilar k
LEFT JOIN urunler u ON k.kullanici_id = u.kullanici_id
GROUP BY k.kullanici_id;

START TRANSACTION;
INSERT INTO kategoriler(kategori_adi) VALUES 
('Elektronik'),
('Vasıta'),
('Müzik Aleti'),
('Giyim');
COMMIT;

START TRANSACTION;
INSERT INTO kullanicilar(ad,soyad,email,sifre)
VALUES
('Ali','Veli','ali@mail.com','1234'),
('Faruk','Mbappe','mbappe@mail.com','1234');
COMMIT;

START TRANSACTION;
CALL urun_ekle(
    1,
    1,
    'Laptop',
    15000,
    'İkinci El Laptop',
    'laptop.jpeg'
);
CALL urun_ekle(
    2,
    4,
    'Kazak',
    1300,
    'LCW Son Kreasyon, L beden',
    'kazak.jpeg'
);
COMMIT;

SELECT * FROM urunler;
SELECT * FROM log_kayitlari;
SELECT * FROM vw_urun_detay;

SELECT k.ad, COUNT(u.urun_id)
FROM kullanicilar k
LEFT JOIN urunler u ON k.kullanici_id = u.kullanici_id
GROUP BY k.kullanici_id;

SELECT *
FROM urunler
ORDER BY fiyat DESC;

SELECT kategori_id, COUNT(*)
FROM urunler
GROUP BY kategori_id;

SELECT urun_adi,fiyat
FROM urunler
WHERE fiyat > (SELECT AVG(fiyat) FROM urunler);




