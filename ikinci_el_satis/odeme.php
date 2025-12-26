<?php
session_start();
require_once __DIR__ . "/config/db.php";

if (!isset($_SESSION['kullanici_id'])) {
    header("Location: giris_gerekli.php");
    exit;
}

$urunler = [];
$toplam = 0;

if (isset($_GET['sepet']) && $_GET['sepet'] == 1 && !empty($_SESSION['sepet'])) {

    $in = implode(',', array_fill(0, count($_SESSION['sepet']), '?'));
    $stmt = $conn->prepare("SELECT * FROM urunler WHERE urun_id IN ($in)");
    $stmt->execute($_SESSION['sepet']);
    $urunler = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($urunler as $u) {
        $toplam += $u['fiyat'];
    }

} elseif (isset($_GET['urun_id'])) {

    $stmt = $conn->prepare("SELECT * FROM urunler WHERE urun_id = ?");
    $stmt->execute([$_GET['urun_id']]);
    $urun = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($urun) {
        $urunler[] = $urun;
        $toplam = $urun['fiyat'];
    }
}

if (empty($urunler)) {
    die("Ürün seçilmedi!");
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<title>Ödeme</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#f4f6f9">

<div class="container mt-5" style="max-width:720px">
<div class="card shadow-lg border-0">
<div class="card-body p-4">

<h3 class="mb-4 text-center">💳 Güvenli Ödeme</h3>


<div class="mb-4">
<h6>Satın Alınacak Ürünler</h6>
<ul class="list-group">
<?php foreach ($urunler as $u): ?>
<li class="list-group-item d-flex justify-content-between">
<span><?= htmlspecialchars($u['urun_adi']) ?></span>
<strong><?= number_format($u['fiyat'],2) ?> TL</strong>
</li>
<?php endforeach; ?>
</ul>

<div class="text-end mt-3 fs-5">
<strong>Toplam: <?= number_format($toplam,2) ?> TL</strong>
</div>
</div>

<form action="odeme_islem.php" method="POST" onsubmit="return kartKontrol()">

<input type="hidden" name="toplam" value="<?= $toplam ?>">

<?php if (isset($_GET['urun_id'])): ?>
<input type="hidden" name="urun_id" value="<?= (int)$_GET['urun_id'] ?>">
<?php endif; ?>

<div class="mb-3">
<label class="form-label">Kart Üzerindeki İsim</label>
<input type="text" name="kart_isim" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Kart Numarası</label>
<input type="text" id="kart_no" name="kart_no" class="form-control" maxlength="19" required>
</div>

<div class="row">
<div class="col-md-6 mb-3">
<label class="form-label">Son Kullanma (AA/YY)</label>
<input type="text" id="son_kullanim" name="son_kullanim" class="form-control" maxlength="5" required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">CVV</label>
<input type="password" name="cvv" class="form-control" maxlength="3" required>
</div>
</div>

<button class="btn btn-success btn-lg w-100 mt-3">
🔒 Ödemeyi Tamamla
</button>

</form>

</div>
</div>
</div>

<script>
document.getElementById("kart_no").addEventListener("input", function () {
    let v = this.value.replace(/\D/g, "").substring(0,16);
    this.value = v.match(/.{1,4}/g)?.join(" ") || v;
});

document.getElementById("son_kullanim").addEventListener("input", function () {
    let v = this.value.replace(/\D/g, "").substring(0,4);
    this.value = v.length > 2 ? v.slice(0,2) + "/" + v.slice(2) : v;
});

function kartKontrol() {
    const kart = document.getElementById("kart_no").value.replace(/\s/g,'');
    const cvv = document.querySelector("input[name='cvv']").value;
    if (kart.length !== 16 || cvv.length !== 3) {
        alert("Kart bilgileri hatalı");
        return false;
    }
    return true;
}
</script>

</body>
</html>
