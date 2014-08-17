<?php

// Cleaning
foreach ($_GET as &$value) {
    $value = htmlspecialchars($value);
}
$action = (isset($_GET["action"])) ? $_GET["action"] : "";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta charset="UTF-8" />
<title><?php echo $page_title; ?></title>


