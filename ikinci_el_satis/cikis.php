<?php
session_start();

session_unset();
session_destroy();

header("Location: /ikinci_el_satis/index.php");
exit;
