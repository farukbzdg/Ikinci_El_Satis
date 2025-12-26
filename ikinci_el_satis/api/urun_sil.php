<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Sadece POST isteği kabul edilir!");
}

// Gelen ürün ID
$urun_id = $_POST['id'] ?? null;

if (!$urun_id) {
    die("Ürün ID gönderilmedi!");
}

try {
    // Önce ürünün fotoğrafını bul
    $stmt = $conn->prepare("SELECT fotograf FROM urunler WHERE urun_id = :id");
    $stmt->execute([':id' => $urun_id]);
    $urun = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$urun) {
        die("Ürün bulunamadı!");
    }

    $foto = $urun['fotograf'];
    $fotoYol = "../uploads/" . $foto;

    // Veritabanından sil
    $sil = $conn->prepare("DELETE FROM urunler WHERE urun_id = :id");
    $sil->execute([':id' => $urun_id]);

    // Fotoğrafı klasörden sil
    if (file_exists($fotoYol)) {
        unlink($fotoYol);
    }

    echo "Ürün başarıyla silindi!";

} catch (PDOException $e) {
    die("Veritabanı hatası: " . $e->getMessage());
}
?>