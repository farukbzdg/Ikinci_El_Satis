<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "../config/db.php";

/* GİRİŞ KONTROLÜ */
if (!isset($_SESSION['kullanici_id'])) {
    die("Giriş yapmadan ürün ekleyemezsiniz!");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Sadece POST isteği kabul edilir!");
}

$kategori_id = $_POST['kategori_id'] ?? null;
$urun_adi    = $_POST['urun_adi'] ?? null;
$fiyat       = $_POST['fiyat'] ?? null;
$aciklama    = $_POST['aciklama'] ?? null;

if (!$kategori_id || !$urun_adi || !$fiyat) {
    die("Lütfen tüm zorunlu alanları doldurun!");
}

/* FOTOĞRAF */
$dosya_adi = null;

if (isset($_FILES['fotograf']) && $_FILES['fotograf']['error'] === 0) {

    $dosya  = $_FILES['fotograf'];
    $uzanti = strtolower(pathinfo($dosya['name'], PATHINFO_EXTENSION));

    if (!in_array($uzanti, ['jpg', 'jpeg', 'png'])) {
        die("Sadece JPG ve PNG kabul edilir!");
    }

    $dosya_adi = uniqid() . "." . $uzanti;
    move_uploaded_file($dosya['tmp_name'], "../uploads/" . $dosya_adi);
}

try {
    $sql = "INSERT INTO urunler 
            (kullanici_id, kategori_id, urun_adi, fiyat, aciklama, fotograf)
            VALUES 
            (:kullanici_id, :kategori_id, :urun_adi, :fiyat, :aciklama, :fotograf)";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':kullanici_id' => $_SESSION['kullanici_id'],
        ':kategori_id'  => $kategori_id,
        ':urun_adi'     => $urun_adi,
        ':fiyat'        => $fiyat,
        ':aciklama'     => $aciklama,
        ':fotograf'     => $dosya_adi
    ]);

    header("Location: ../index.php?durum=urun_eklendi");
    exit;

} catch (PDOException $e) {
    die("Ürün eklenemedi: " . $e->getMessage());
}
