<?php
session_start();
require_once "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Sadece POST");
}

$email = $_POST['email'] ?? '';
$sifre = $_POST['sifre'] ?? '';

$stmt = $conn->prepare("SELECT * FROM kullanicilar WHERE email = :email");
$stmt->execute([':email' => $email]);
$kullanici = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$kullanici || !password_verify($sifre, $kullanici['sifre'])) {
    die("Email veya şifre hatalı");
}

/* SESSION'A YAZ */
$_SESSION['kullanici_id'] = $kullanici['kullanici_id'];
$_SESSION['ad'] = $kullanici['ad'];
$_SESSION['soyad'] = $kullanici['soyad'];

/* ANA SAYFAYA GİT */
header("Location: /ikinci_el_satis/index.php");
exit;
