<?php

// Cleaning
foreach ($_GET as &$value) {
    $value = htmlspecialchars($value);
}
$action = (isset($_GET["action"])) ? $_GET["action"] : "";
?>