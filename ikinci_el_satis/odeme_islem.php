<?php
session_start();
require_once __DIR__ . "/config/db.php";

if (
    empty($_POST['kart_isim']) ||
    empty($_POST['kart_no']) ||
    empty($_POST['son_kullanim']) ||
    empty($_POST['cvv'])
) {
    die("Kart bilgileri eksik!");
}

if (!isset($_SESSION['kullanici_id'])) {
    die("Lütfen giriş yapın");
}

$toplam = $_POST['toplam'] ?? 0;
$urunler = [];

if (!empty($_SESSION['sepet'])) {
    $urunler = $_SESSION['sepet'];
} elseif (!empty($_POST['urun_id'])) {
    $urunler[] = $_POST['urun_id'];
}

if (empty($urunler)) {
    die("Ürün bulunamadı");
}

try {
    $conn->beginTransaction();

    foreach ($urunler as $urun_id) {

        $stmt = $conn->prepare("
            INSERT INTO satislar (urun_id)
            VALUES (?)
        ");
        $stmt->execute([$urun_id]);
        $satis_id = $conn->lastInsertId();
        $stmt = $conn->prepare("SELECT fiyat FROM urunler WHERE urun_id = ?");
        $stmt->execute([$urun_id]);
        $fiyat = $stmt->fetchColumn();

        $stmt = $conn->prepare("
            INSERT INTO odemeler (satis_id, tutar)
            VALUES (?, ?)
        ");
        $stmt->execute([$satis_id, $fiyat]);
    }

    unset($_SESSION['sepet']);
    $conn->commit();

    echo "
    <div style='max-width:600px;margin:60px auto;text-align:center'>
        <h2>✅ Ödeme Başarılı</h2>
        <p>🛒 Siparişiniz oluşturuldu</p>
        <p><strong>Toplam:</strong> {$toplam} TL</p>
        <a href='index.php'>Ana Sayfaya Dön</a>
    </div>
    ";

} catch (Exception $e) {
    $conn->rollBack();
    echo 'Hata: ' . $e->getMessage();
}
