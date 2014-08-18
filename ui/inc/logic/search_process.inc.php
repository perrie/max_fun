<?php

include("return_array.inc.php");

###############
#             #
#  FUNCTIONS  #
#             #
###############

/* Binds search parameters for SELECT MySQL statment
  * stmt 			- Prepared Statement to select info from database
  * research_text 	- search parameters to add
  * broken 			- string to use if process doesn't work
 */
function bind_stmt_array($stmt, $search_text) {

	$replace_str = "";
	# Approach: http://www.pontikis.net/blog/dynamically-bind_param-array-mysqli
	for ($i = 0; $i < count($search_text); $i++) {
			
		$replace_str .= (gettype($search_text[$i]) == "string") 
			? "s" : "i";
		$search_text[$i] = (gettype($search_text[$i]) == "string") 
			? "%" . $search_text[$i] . "%"
			: $search_text[$i];
	}
	
	$a_params = array();
	# with call_user_func_array, array params must be passed by reference
	$a_params[] = &$replace_str;
	
	for ($i = 0; $i < count($search_text); $i++)
		$a_params[] = &$search_text[$i];
	
	call_user_func_array(array($stmt, 'bind_param'), $a_params);
	return $stmt;
}

/* Gets results from database
 * query		- MySQL string - follows Prepared Statment setup
 * mysqli		- connection to database
 * search_text	- array with search text
 */
function getResults($query, $mysqli, $search_text) {
		
	if (!($stmt = 
		$mysqli->prepare($query))) {
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		return false;
	}
	
	if (!($stmt = bind_stmt_array($stmt, $search_text)))
		return false;
	
	if (!$stmt->execute()) {
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		return false;
	}
	$stmt->store_result();
	return iimysqli_stmt_get_result($stmt);
}

/* Parses in search term to find their intersearch in database
 * 	(spaces indicate that two words are being searched...and that 
 *  they should appear in the same field of the output)
 * search_gen_text	- search field with potentialy multiple search terms
 * query_str		- query string of search following PrepareStatment idea
 * add_str			- what string to add to query string for each additional search term
 */
function resolve_spaces(&$search_gen_text, $query_str, $add_str) {
	$search_parts = [];
	for ($i = 0; $i < count($search_gen_text); $i++) {
		$pieces = explode(" ", $search_gen_text[0]);
		
		if (count($pieces) > 1) {
			for ($j = 0; $j < count($pieces); $j++) {
				array_push($search_parts, $pieces[$j]);
			}
		}
	}
	if (count($search_parts) > count($search_gen_text)) {
		$search_gen_text = $search_parts;
		for ($i = 1; $i < count($search_parts); $i++) {
			$query_str .= $add_str;
		}
	}
	return $query_str;
}

/* Sets up SELECT query depending on the type search
 * type				- type of search/result to process
 * mysqli			- connection to database
 * search_gen_text	- array with search terms
 */
function process_gen_result($type, $mysqli, $search_gen_text) {
	$query_str = "";
	$search_size = 0;
	#########
	# Users #
	#########
	if ($type == "Users") {
		$query_str = "SELECT * FROM user WHERE NAME LIKE ?";
		$query_str = resolve_spaces($search_gen_text, $query_str, " AND NAME LIKE ?");
	##########
	# Rounds #
	##########
	} else if ($type == "Rounds") {
		$query_str = "SELECT * FROM round WHERE NAME LIKE ?";
		$query_str = resolve_spaces($search_gen_text, $query_str, " AND NAME LIKE ?");
	###########
	# Entries #
	###########
	} else if ($type == "Entries") {
		# Description
		$query_str = "SELECT * FROM max.entry WHERE DESCRIPTION LIKE ?"; 
		$query_str = resolve_spaces($search_gen_text, $query_str, " AND DESCRIPTION LIKE ?");
		$search_size = count($search_gen_text);
		# Title
		$query_str .= " OR TITLE LIKE ?";
		array_push($search_gen_text, $search_gen_text[0]);
		for ($i = 1; $i  < $search_size; $i++) {
			$query_str .= " AND TITLE LIKE ?";
			array_push($search_gen_text, $search_gen_text[$i]);
		}
		# Characters
		$query_str .= " OR CHARACTERS LIKE ?";
		array_push($search_gen_text, $search_gen_text[0]);
		for ($i = 1; $i  < $search_size; $i++) {
			$query_str .= " AND CHARACTERS LIKE ?";
			array_push($search_gen_text, $search_gen_text[$i]);
		}
	} 	
	return getResults($query_str, $mysqli, $search_gen_text);
}

##############################
#                            #
#   PROCESS GENERAL SEARCH   #
#                            #
##############################
if ($search_gen_yes) {

	$searchAll = (isset($_GET['search_gen_searchAll'])) ? true : false;
	$search_gen_array = [$search_gen_text];
	#########
	# Users #
	#########
	if ($searchAll || isset($_GET['search_gen_searchUsers'])) {
		$result_users = process_gen_result("Users", $mysqli, $search_gen_array);
	}
	##########
	# Rounds #
	##########
	if ($searchAll || isset($_GET['search_gen_searchRounds'])) {
		$result_rounds = process_gen_result("Rounds", $mysqli, $search_gen_array);
	}
	###########
	# Entries #
	###########
	if ($searchAll || isset($_GET['search_gen_searchEntries'])) {
		$result_entries = process_gen_result("Entries", $mysqli, $search_gen_array);
	}

###############################
#                             #
#   PROCESS ADVANCED SEARCH   #
#                             #
###############################
} else if ($search_adv_yes) {
	
	$search_adv_array = [];
	array_push($search_adv_array, $search_adv_recu);
	$result_recu = ($search_adv_recu == "") ? null 
		: process_gen_result("Users", $mysqli, $search_adv_array);
	$search_adv_array = [];
	array_push($search_adv_array, $search_adv_give);
	$result_give = ($search_adv_give == "") ? null 
		: process_gen_result("Users", $mysqli, $search_adv_array);
	$search_adv_array = [];
	array_push($search_adv_array, $search_adv_round);
	$result_round = ($search_adv_round == "") ? null 
		: process_gen_result("Rounds", $mysqli, $search_adv_array);
		
	$entry_results = [];
	$entry_number = 0;
	$NAME = 1; $ID = 0; 
	while ($search_adv_recu == "" || $myrow_recu = iimysqli_result_fetch_array($result_recu)) {
	
		$recu_id = ($search_adv_recu == "") ? -1 : $myrow_recu[$ID];
		$recu_name = ($search_adv_recu == "") ? "" : $myrow_recu[$NAME];
		
		while ($search_adv_give == "" || $myrow_give = iimysqli_result_fetch_array($result_give)) {
		
			$give_id = ($search_adv_give == "") ? -1 : $myrow_give[$ID];
			$give_name = ($search_adv_give == "") ? "" : $myrow_give[$NAME];
		
			while ($search_adv_round == "" || $myrow_round = iimysqli_result_fetch_array($result_round)) {

				$round_id = ($search_adv_round == "") ? -1 : $myrow_round[$ID];
				$round_name = ($search_adv_round == "") ? "" : $myrow_round[$NAME];
				
				$query_str_start = "SELECT * FROM max.entry WHERE ";
				$query_str = "";
				$search_adv_vars = [];
				$search_adv_title_arr  = [];
				$search_adv_desc_arr = [];
				$search_adv_character_arr = [];


				if ($search_adv_title != "") {
					$add_str = " AND TITLE LIKE ?";
					$query_str .= $add_str;
					array_push($search_adv_title_arr, $search_adv_title);
					$query_str = resolve_spaces($search_adv_title_arr, $query_str, " AND TITLE LIKE ?");
					foreach ($search_adv_title_arr as &$arr)
						array_push($search_adv_vars, $arr);
				}
				
				if ($search_adv_desc != "") {
					$add_str = " AND DESCRIPTION LIKE ?";
					$query_str .= $add_str;
					array_push($search_adv_desc_arr, $search_adv_desc);
					$query_str = resolve_spaces($search_adv_desc_arr, $query_str, $search_adv_desc);
					foreach ($search_adv_desc_arr as &$arr)
						array_push($search_adv_vars, $arr);
				}
				
				if ($search_adv_character != "") {
					$add_str = " AND CHARACTERS LIKE ?";
					$query_str .= $add_str;
					array_push($search_adv_character_arr, $search_adv_character);
					$query_str = resolve_spaces($search_adv_character_arr, $query_str, $add_str);
					foreach ($search_adv_character_arr as &$arr)
						array_push($search_adv_vars, $arr);
				}
								
				if ($recu_id != -1) {
					$query_str .= " AND RECU_ID = ?";
					array_push($search_adv_vars, $recu_id);
				}
				if ($give_id != -1) {
					$query_str .= " AND GIVE_ID = ?";
					array_push($search_adv_vars, $give_id);
				}
				if ($round_id != -1) {
					$query_str .= " AND ROUND_ID = ?";
					array_push($search_adv_vars, $round_id);
				}
				
				$query_str = $query_str_start . substr($query_str, 4);	
				$result2 = getResults($query_str, $mysqli, $search_adv_vars);				
				
				array_push($entry_results, $result2);
				$entry_number += $result2->nRows;
				
				if ($search_adv_round == "") break;
			}
		
			if ($search_adv_give == "") break;
		}
		
		if ($search_adv_recu == "") break;
	}
	
	# Pass on...
	# $entry_results, $entry_number
}
?>