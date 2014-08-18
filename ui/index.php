<?php

include ("inc/logic/clean_get.inc.php");
$page_title = "Search";
include("inc/html/head_open.inc.php");
include("inc/html/head_close.inc.php");


include("inc/logic/connect.inc.php");

include("inc/logic/search_params.inc.php");
include("inc/html/search.inc.php");
include("inc/logic/search_process.inc.php");
include("inc/html/search_results.inc.php");

include("inc/html/close.inc.php");

?>