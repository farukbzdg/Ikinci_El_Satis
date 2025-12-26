<?php
require_once "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Sadece POST kabul edilir");
}

$ad    = $_POST['ad'] ?? null;
$soyad = $_POST['soyad'] ?? null;
$email = $_POST['email'] ?? null;
$sifre = $_POST['sifre'] ?? null;

if (!$ad || !$soyad || !$email || !$sifre) {
    die("Eksik bilgi");
}

$sifre_hash = password_hash($sifre, PASSWORD_DEFAULT);

try {
    $stmt = $conn->prepare("
        INSERT INTO kullanicilar (ad, soyad, email, sifre)
        VALUES (:ad, :soyad, :email, :sifre)
    ");

    $stmt->execute([
        ':ad'    => $ad,
        ':soyad' => $soyad,
        ':email' => $email,
        ':sifre' => $sifre_hash
    ]);

    echo "✅ Kullanıcı başarıyla kaydedildi";

} catch (PDOException $e) {
    die("❌ Hata: " . $e->getMessage());
}
?>
