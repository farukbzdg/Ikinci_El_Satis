<?php
session_start();
require_once __DIR__ . "/config/db.php";

if (!isset($_SESSION['sepet'])) {
    $_SESSION['sepet'] = [];
}

$urunler = [];
$toplam = 0;

if (!empty($_SESSION['sepet'])) {
    $in = implode(',', array_fill(0, count($_SESSION['sepet']), '?'));
    $stmt = $conn->prepare("SELECT * FROM urunler WHERE urun_id IN ($in)");
    $stmt->execute($_SESSION['sepet']);
    $urunler = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($urunler as $u) {
        $toplam += $u['fiyat'];
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sepet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h2>🛒 Sepet</h2>

<?php if (empty($urunler)): ?>
    <p>Sepet boş</p>
<?php else: ?>

    <?php foreach ($urunler as $urun): ?>
        <div class="card mb-3 p-3">
            <h5><?= htmlspecialchars($urun['urun_adi']) ?></h5>
            <p><?= number_format($urun['fiyat'], 2) ?> TL</p>

            <!-- 🔴 SEPETTEN ÇIKAR -->
            <form action="api/sepet_cikar.php" method="POST">
                <input type="hidden" name="urun_id" value="<?= $urun['urun_id'] ?>">
                <button class="btn btn-danger btn-sm">Sepetten Çıkar</button>
            </form>
        </div>
    <?php endforeach; ?>

    <!-- 🔵 TOPLAM + TOPLU SATIN AL -->
    <div class="card p-3 mt-4 border-success">
        <h4>Toplam: <?= number_format($toplam, 2) ?> TL</h4>

        <form action="odeme.php" method="GET">
            <input type="hidden" name="sepet" value="1">
            <button class="btn btn-primary btn-lg w-100 mt-2">
                🧾 Tüm Sepeti Satın Al
            </button>
        </form>
    </div>

<?php endif; ?>

<a href="index.php" class="btn btn-secondary mt-4">← Ana Sayfa</a>

</body>
</html>
