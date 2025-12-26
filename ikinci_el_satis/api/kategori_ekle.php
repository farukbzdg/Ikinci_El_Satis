<?php
require_once "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') die("Sadece POST kabul edilir");

$kategori_adi = $_POST['kategori_adi'] ?? null;
if (!$kategori_adi) die("Kategori adı boş olamaz");

$stmt = $conn->prepare("INSERT INTO kategoriler (kategori_adi) VALUES (:adi)");
$stmt->execute([':adi' => $kategori_adi]);

echo "Kategori başarıyla eklendi!";
?>