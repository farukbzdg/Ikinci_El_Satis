<?php

require_once "../config/db.php";

$stmt = $conn->query("SELECT * FROM kategoriler ORDER BY kategori_adi ASC");
$kategoriler = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($kategoriler);
?>
