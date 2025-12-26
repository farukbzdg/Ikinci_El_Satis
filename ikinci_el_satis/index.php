<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/config/db.php";

if (!isset($_SESSION['sepet'])) {
    $_SESSION['sepet'] = [];
}

$kategori_id = $_GET['kategori_id'] ?? null;
$arama = $_GET['arama'] ?? '';

$sql = "SELECT u.*, k.kategori_adi, usr.ad, usr.soyad
        FROM urunler u
        JOIN kategoriler k ON u.kategori_id = k.kategori_id
        LEFT JOIN kullanicilar usr ON u.kullanici_id = usr.kullanici_id
        WHERE 1=1";
$params = [];

if ($arama) {
    $sql .= " AND u.urun_adi LIKE :arama";
    $params[':arama'] = "%$arama%";
}

if ($kategori_id) {
    $sql .= " AND u.kategori_id = :kategori";
    $params[':kategori'] = $kategori_id;
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$urunler = $stmt->fetchAll(PDO::FETCH_ASSOC);

$kat_stmt = $conn->query("SELECT * FROM kategoriler ORDER BY kategori_adi ASC");
$kategoriler = $kat_stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Sahilinden</title>
<link rel="icon" type="image/png" href="/ikinci_el_satis/sahibinden.png" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
.urun-card img{height:220px;object-fit:cover}
.urun-id{font-size:13px;color:#6c757d}
</style>
</head>
<body class="p-4">

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

    <div>
        <div class="d-flex align-items-center gap-2">
            <img src="/ikinci_el_satis/sahibinden.png" style="height:50px">
            <h2 class="m-0">Sahilinden</h2>
        </div>

        <?php if (isset($_SESSION['kullanici_id'])): ?>
            <small class="text-muted ms-1">
                Hoş geldin, <?= htmlspecialchars($_SESSION['ad'].' '.$_SESSION['soyad']) ?>
            </small>
        <?php endif; ?>
    </div>

    <div class="d-flex gap-2 flex-wrap">

        <?php if (!isset($_SESSION['kullanici_id'])): ?>
            <a href="/ikinci_el_satis/kayit.php" class="btn btn-outline-success">Kayıt Ol</a>
            <a href="/ikinci_el_satis/giris.php" class="btn btn-outline-primary">Giriş Yap</a>
        <?php else: ?>
            <a href="/ikinci_el_satis/cikis.php" class="btn btn-outline-danger">Çıkış Yap</a>
        <?php endif; ?>

        <a href="/ikinci_el_satis/sepet.php" class="btn btn-info">
            Sepet (<?= count($_SESSION['sepet']) ?>)
        </a>

        <?php if (isset($_SESSION['kullanici_id'])): ?>
            <a href="/ikinci_el_satis/urun_ekle_form.php" class="btn btn-success">Ürün Ekle</a>
            <a href="/ikinci_el_satis/urun_guncelle_sec.php" class="btn btn-warning">Ürün Güncelle</a>
            <a href="/ikinci_el_satis/urun_sil_form.php" class="btn btn-danger">Ürün Sil</a>
            <a href="/ikinci_el_satis/kategori_ekle_form.php" class="btn btn-primary">Kategori Ekle</a>
            <a href="/ikinci_el_satis/kategori_sil_form.php" class="btn btn-secondary">Kategori Sil</a>
        <?php endif; ?>

    </div>
</div>

<form method="GET" class="row g-2 mb-4">
    <div class="col-md-6">
        <input type="text" name="arama" class="form-control" placeholder="Ürün ara..."
               value="<?= htmlspecialchars($arama) ?>">
    </div>
    <div class="col-md-4">
        <select name="kategori_id" class="form-select">
            <option value="">Tüm Kategoriler</option>
            <?php foreach ($kategoriler as $kat): ?>
                <option value="<?= $kat['kategori_id'] ?>"
                    <?= ($kategori_id == $kat['kategori_id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($kat['kategori_adi']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary w-100">Filtrele</button>
    </div>
</form>

<div class="container-fluid">
    <div class="row">
        <?php foreach ($urunler as $urun): ?>
            <div class="col-md-4 col-lg-3 mb-4">
                <div class="card h-100 shadow-sm urun-card">

                    <a href="/ikinci_el_satis/urun_detay.php?id=<?= $urun['urun_id'] ?>">
                        <img src="/ikinci_el_satis/uploads/<?= htmlspecialchars($urun['fotograf'] ?: 'default_fotograf.png') ?>"
                             class="card-img-top">
                    </a>

                    <div class="card-body">
                        <div class="urun-id">Ürün ID: <?= (int)$urun['urun_id'] ?></div>
                        <h5><?= htmlspecialchars($urun['urun_adi']) ?></h5>
                        <p class="fw-bold text-success"><?= number_format($urun['fiyat'],2) ?> TL</p>
                        <small>Kategori: <?= htmlspecialchars($urun['kategori_adi']) ?></small><br>
                        <small>Satıcı: <?= htmlspecialchars($urun['ad'].' '.$urun['soyad']) ?></small>
                        <p class="mt-2"><?= nl2br(htmlspecialchars($urun['aciklama'])) ?></p>
                    </div>

                    <div class="card-footer bg-white border-0">
                        <form action="/ikinci_el_satis/api/sepet_ekle.php" method="POST">
                            <input type="hidden" name="urun_id" value="<?= (int)$urun['urun_id'] ?>">
                            <button class="btn btn-primary w-100">Sepete Ekle</button>
                        </form>
                    </div>

                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
