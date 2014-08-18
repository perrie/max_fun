<?php

function issetset($value) {
	return (isset($_GET[$value])) ? $_GET[$value] : "";
}

$action = (!isset($action)) ? $_GET['action'] : $action;


# GEN
$search_gen_text = issetset("gen_text");
# ADV
$search_adv_round = issetset("adv_round");
$search_adv_give = issetset("adv_give");
$search_adv_recu = issetset("adv_recu");
$search_adv_title = issetset("adv_title");
$search_adv_character = issetset("adv_character");
$search_adv_desc = issetset("adv_desc");

$search_gen_yes_str = "searching_gen";
$search_adv_yes_str = "searching_adv";
$search_gen_yes = (substr($action,0,strlen($search_gen_yes_str)) == $search_gen_yes_str);
$search_adv_yes = (substr($action,0,strlen($search_adv_yes_str)) == $search_adv_yes_str);

$do_json_str = "_json";
$do_json = (substr($action,strlen($action)-4) == "json") ? TRUE : FALSE;

?>