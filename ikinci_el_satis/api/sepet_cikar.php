<?php
session_start();

$urun_id = $_POST['urun_id'] ?? null;

if ($urun_id && isset($_SESSION['sepet'])) {
    $_SESSION['sepet'] = array_values(
        array_diff($_SESSION['sepet'], [$urun_id])
    );
}

header("Location: /ikinci_el_satis/sepet.php");
exit;