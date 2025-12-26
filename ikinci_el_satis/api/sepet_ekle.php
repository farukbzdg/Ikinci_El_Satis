<?php
session_start();

if (!isset($_SESSION['sepet'])) {
    $_SESSION['sepet'] = [];
}

$urun_id = $_POST['urun_id'] ?? null;

if ($urun_id && !in_array($urun_id, $_SESSION['sepet'])) {
    $_SESSION['sepet'][] = $urun_id;
}

$geri = $_SERVER['HTTP_REFERER'] ?? '/ikinci_el_satis/index.php';
header("Location: $geri");
exit;
