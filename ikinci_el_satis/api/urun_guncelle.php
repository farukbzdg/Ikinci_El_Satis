<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../config/db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Sadece POST isteği kabul edilir!");
}

/* POST ile ID al */
$urun_id = $_POST['urun_id'] ?? null;
if (!$urun_id) {
    die("Ürün ID belirtilmedi (POST)");
}

/* Ürün var mı */
$stmt = $conn->prepare("SELECT * FROM urunler WHERE urun_id = :id");
$stmt->execute([':id' => $urun_id]);
$urun = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$urun) {
    die("Güncellenecek ürün bulunamadı!");
}

/* Yeni veriler */
$urun_adi = $_POST['urun_adi'] ?? $urun['urun_adi'];
$fiyat    = $_POST['fiyat'] ?? $urun['fiyat'];
$aciklama = $_POST['aciklama'] ?? $urun['aciklama'];

/* Fotoğraf */
$foto = $urun['fotograf'];

if (!empty($_FILES['fotograf']['name'])) {
    $ext = pathinfo($_FILES['fotograf']['name'], PATHINFO_EXTENSION);
    $foto = uniqid() . "." . $ext;
    move_uploaded_file($_FILES['fotograf']['tmp_name'], "../uploads/" . $foto);
}

/* UPDATE */
$sql = "UPDATE urunler SET
        urun_adi = :urun_adi,
        fiyat = :fiyat,
        aciklama = :aciklama,
        fotograf = :fotograf
        WHERE urun_id = :id";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ':urun_adi' => $urun_adi,
    ':fiyat' => $fiyat,
    ':aciklama' => $aciklama,
    ':fotograf' => $foto,
    ':id' => $urun_id
]);

header("Location: ../index.php");
exit;
