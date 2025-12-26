<?php
session_start();
require_once "config/db.php";

$urun_id = $_GET['id'] ?? null;
if (!$urun_id) {
    die("Ürün ID bulunamadı!");
}

$stmt = $conn->prepare("
    SELECT u.*, k.kategori_adi, usr.ad, usr.soyad
    FROM urunler u
    JOIN kategoriler k ON u.kategori_id = k.kategori_id
    LEFT JOIN kullanicilar usr ON u.kullanici_id = usr.kullanici_id
    WHERE u.urun_id = :urun_id
");
$stmt->execute([':urun_id' => $urun_id]);
$urun = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$urun) {
    die("Ürün bulunamadı!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sepete_ekle'])) {
    if (!isset($_SESSION['sepet'])) {
        $_SESSION['sepet'] = [];
    }
    $_SESSION['sepet'][] = $urun['urun_id'];
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($urun['urun_adi'], ENT_QUOTES, 'UTF-8') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .urun-detay {
            max-width: 900px;
            margin: 40px auto;
            display: flex;
            gap: 30px;
        }
        .urun-detay img {
            max-width: 400px;
            border-radius: 8px;
            object-fit: cover;
        }
        .urun-info {
            flex: 1;
        }
        .btn-grup {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }
    </style>
</head>

<body class="p-4" style="background:#f4f6f9">

<div class="urun-detay bg-white p-4 shadow rounded">

    <div>
        <img src="uploads/<?= htmlspecialchars($urun['fotograf'], ENT_QUOTES, 'UTF-8') ?>" alt="Ürün Fotoğrafı">
    </div>

    <div class="urun-info">
        <h2><?= htmlspecialchars($urun['urun_adi'], ENT_QUOTES, 'UTF-8') ?></h2>

        <p class="fs-5">
            <strong>Fiyat:</strong> <?= number_format($urun['fiyat'], 2) ?> TL
        </p>

        <p>
            <strong>Kategori:</strong>
            <?= htmlspecialchars($urun['kategori_adi'], ENT_QUOTES, 'UTF-8') ?>
        </p>

        <p>
            <strong>Satıcı:</strong>
            <?= htmlspecialchars($urun['ad'] . ' ' . $urun['soyad'], ENT_QUOTES, 'UTF-8') ?>
        </p>

        <p>
            <?= nl2br(htmlspecialchars($urun['aciklama'], ENT_QUOTES, 'UTF-8')) ?>
        </p>

        <div class="btn-grup">

            <!-- Ana Menü -->
            <a href="index.php" class="btn btn-secondary">
                Ana Menüye Dön
            </a>

            <!-- Sepete Ekle -->
            <form method="POST">
                <button type="submit" name="sepete_ekle" class="btn btn-warning">
                    Sepete Ekle
                </button>
            </form>

            <!-- Satın Al (DİREKT ÖDEME) -->
            <a href="odeme.php?urun_id=<?= $urun['urun_id'] ?>" class="btn btn-success">
                Satın Al
            </a>

        </div>
    </div>
</div>

</body>
</html>
