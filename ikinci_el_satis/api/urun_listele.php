<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "../config/db.php";

try {
    $sql = "SELECT u.urun_id, u.urun_adi, u.fiyat, u.aciklama, u.fotograf,
                   k.kategori_adi,
                   CONCAT(ku.ad, ' ', ku.soyad) AS kullanici
            FROM urunler u
            JOIN kategoriler k ON u.kategori_id = k.kategori_id
            JOIN kullanicilar ku ON u.kullanici_id = ku.kullanici_id
            ORDER BY u.urun_id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $urunler = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Veritabanı hatası: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ürün Listesi</title>

    <!-- Bootstrap -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        .urun-kart {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            display: flex;
            gap: 20px;
            background: #fff;
        }

        .urun-kart img {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #ccc;
        }

        .urun-info {
            flex: 1;
        }

        .ust-menu {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin: 25px 0;
        }

        .ust-menu a {
            font-size: 16px;
        }

    </style>
</head>
<body class="container">

    <h1 class="mt-4">Ürünler</h1>

    <!-- Üst Menü -->
    <div class="ust-menu">
        <a href="urun_ekle.php" class="btn btn-success">Ürün Ekle</a>
        <a href="urun_guncelle.php" class="btn btn-warning">Ürün Güncelle</a>
        <a href="urun_sil.php" class="btn btn-danger">Ürün Sil</a>
    </div>

    <!-- Ürün Listeleme -->
    <?php foreach ($urunler as $urun): ?>

        <div class="urun-kart">

            <!-- Fotoğraf -->
            <?php 
                $foto = $urun['fotograf'] ? "../uploads/" . $urun['fotograf'] : "https://via.placeholder.com/180";
            ?>
            <img src="<?= $foto ?>" alt="Fotoğraf">

            <!-- Bilgiler -->
            <div class="urun-info">
                <h3><?= htmlspecialchars($urun['urun_adi']) ?></h3>

                <p><strong>Fiyat:</strong> <?= htmlspecialchars($urun['fiyat']) ?> TL</p>
                <p><strong>Kategori:</strong> <?= htmlspecialchars($urun['kategori_adi']) ?></p>
                <p><strong>Ekleyen:</strong> <?= htmlspecialchars($urun['kullanici']) ?></p>

                <p style="white-space: pre-wrap;">
                    <strong>Açıklama:</strong><br>
                    <?= htmlspecialchars($urun['aciklama']) ?>
                </p>

                <a href="urun_guncelle.php?id=<?= $urun['urun_id'] ?>" class="btn btn-primary btn-sm">
                    Düzenle
                </a>

                <a href="urun_sil.php?id=<?= $urun['urun_id'] ?>" class="btn btn-danger btn-sm">
                    Sil
                </a>
            </div>
        </div>

    <?php endforeach; ?>

</body>
</html>