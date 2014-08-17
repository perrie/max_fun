<?php

$broken = "broken result";

//if (isset($do_json) && $do_json) {
if (isset($_GET['do_json'])) {
	include("connect.inc.php");
	include("search_params.inc.php");
}

include("return_array.inc.php");

function bind_stmt_array($stmt, $search_text, $broken) {

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

function getResults($query, $mysqli, $search_text, $broken) {
		
	if (!($stmt = 
		$mysqli->prepare($query))) {
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		return $broken;
	}
	
	if (($stmt = bind_stmt_array($stmt, $search_text, $broken)) == $broken)
		return $broken;
	
	if (!$stmt->execute()) {
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		return $broken;
	}
	$stmt->store_result();
	/*
	
	if (!$stmt->fetch()) {
		echo "Fetch Result failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	echo "<p>$district</p>";
	*/
	/* ORIGINAL BLOCK
	if(!($result = $stmt->get_result())) {
		echo "Unable to get results: (" . $stmt->errno . ") " . $stmt->error;
		return $broken;
	}
	return $result;
	*/
	return iimysqli_stmt_get_result($stmt);
	
}

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

function print_gen_result($type, $searchAll, $printing, $mysqli, $search_gen_text, $broken) {
	$query_str = "";
	$search_size = 0;
	if ($type == "Users") {
		$query_str = "SELECT * FROM user WHERE NAME LIKE ?";
		$div_value = '<div style=" float: left; width: 25%; ">';
		$query_str = resolve_spaces($search_gen_text, $query_str, " AND NAME LIKE ?");
	} else if ($type == "Rounds") {
		$query_str = "SELECT * FROM round WHERE NAME LIKE ?";
		$div_value = '<div style=" float: left; width: 25%; padding-left: 5%; border-left: 1px solid black; ">';
		$query_str = resolve_spaces($search_gen_text, $query_str, " AND NAME LIKE ?");
	} else if ($type == "Entries") {
		# Description
		$query_str = "SELECT * FROM max.entry WHERE DESCRIPTION LIKE ?"; //TITLE LIKE ?"// OR DESCRIPTION "
		//. "LIKE ? OR CHARACTERS LIKE ?";
		$div_value = '<div style=" margin-left: 55%; border-left: 1px solid black; padding-left: 5%; ">';
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
	if (!$searchAll)
		$div_value = '<div>';
	if ($printing) echo $div_value;
	
	$result = getResults($query_str, $mysqli, $search_gen_text, $broken);
	
	if ($printing) {
		echo "<h3>$type</h3>";
		if ($result == $broken) {
			echo "<p>Unable to query database for some reason</p>";
		} else {
			echo "<p><strong>Number of Results: " . $result->nRows . "</strong></p>";
			echo "<ul>";
			while ($myrow = iimysqli_result_fetch_array($result)) {
					if ($type == "Users") {
						$NAME = 1; $ID = 0;
						for ($i = 0; $i < count($search_gen_text); $i++) {
							$myrow[$NAME] = str_ireplace($search_gen_text[$i],"<strong>" 
								. $search_gen_text[$i] . "</strong>", $myrow[$NAME]);
						}
						 printf('<li><a href="http://www.comicdish.com/max/user_profile.php?user_id=%d">%s</a></li>', $myrow[$ID], $myrow[$NAME]);
					 } else if ($type == "Rounds") {
						$NAME = 1; $ID = 0; $NUM_USER = 4;
						for ($i = 0; $i < count($search_gen_text); $i++) {
							$myrow[$NAME] = str_ireplace($search_gen_text[$i],"<strong>" 
								. $search_gen_text[$i] . "</strong>", $myrow[$NAME]);
						}
						 printf('<li><a href="http://www.comicdish.com/max/gallery.php?round_id=%d">%s</a> - %s participants</li>', $myrow[$ID], $myrow[$NAME], $myrow[$NUM_USER]);
					 } else if ($type == "Entries") {
						$NAME = 1; $ID = 0; $TITLE = 5; $DESCRIPTION = 6; $CHARACTERS = 7; 
						$IMG_URL = 10; 	$GIVE_NAME = 12; $RECU_NAME = 11; $ROUND_NAME = 13; $RECU_ID = 1;
						$GIVE_ID = 2; $ROUND_ID = 3;
						for ($i = 0; $i < $search_size; $i++) {
							$myrow[$TITLE] = str_ireplace($search_gen_text[$i],"<strong>" 
								. $search_gen_text[$i] . "</strong>", 
								$myrow[$TITLE]);
							$myrow[$CHARACTERS] = str_ireplace($search_gen_text[$i],"<strong>" 
								. $search_gen_text[$i] . "</strong>", 
								$myrow[$CHARACTERS]);
							$myrow[$DESCRIPTION] = str_ireplace($search_gen_text[$i],"<strong>" 
								. $search_gen_text[$i] . "</strong>", $myrow[$DESCRIPTION]);
						}
						printf('<li>
								<p><a href="http://www.comicdish.com/max/view_entry.php?entry_id=%d"><img src="thumbs/%s" border="3" /></a></p>
								<p><em>Artist:</em> <a href="http://www.comicdish.com/max/user_profile.php?user_id=%d">%s</a></p>
								<p><em>Receiver:</em> <a href="http://www.comicdish.com/max/user_profile.php?user_id=%d">%s</a></p>
								<p><em>Round Title:</em> <a href="http://www.comicdish.com/max/gallery.php?round_id=%d">%s</a></p>
								<p><em>Title:</em> %s</p>
								<p><em>Characters:</em> %s</p>
								<p><em>Description:</em> %s</p></li>', $myrow[$ID], $myrow[$IMG_URL], 
									$myrow[$GIVE_ID], $myrow[$GIVE_NAME],
									$myrow[$RECU_ID], $myrow[$RECU_NAME],
									$myrow[$ROUND_ID], $myrow[$ROUND_NAME],
									$myrow[$TITLE], $myrow[$CHARACTERS],$myrow[$DESCRIPTION]);				 
		
					 }
			 }
			 echo "</ul>";
		}
		echo "</div>";
	}
	return $result;
}

if ($search_gen_yes) {

	if (!$do_json) {
		echo '<hr />';
		echo '<h2>Results</h2>';
	}
	$searchAll = (isset($_GET['search_gen_searchAll'])) ? true : false;
	# USERS
	if ($searchAll || isset($_GET['search_gen_searchUsers'])) {
		$search_gen_array = [$search_gen_text];
		$result = print_gen_result("Users", $searchAll, !$do_json, $mysqli, $search_gen_array, $broken);
		if ($do_json) {
			while (iimysqli_result_fetch_array($result));
			echo json_encode($result);
		}
		
	}
	
	# ROUNDS
	if ($searchAll || isset($_GET['search_gen_searchRounds'])) {
		$search_gen_array = [$search_gen_text];
		$result = print_gen_result("Rounds", $searchAll, !$do_json, $mysqli, $search_gen_array, $broken);
		if ($do_json) {
			while (iimysqli_result_fetch_array($result));
			echo json_encode($result);
		}
	}
	
	# ENTRY
	if ($searchAll || isset($_GET['search_gen_searchEntries'])) {
		$search_gen_array = [$search_gen_text];
		$result = print_gen_result("Entries", $searchAll, !$do_json, $mysqli, $search_gen_array, $broken);
		if ($do_json) {
			while (iimysqli_result_fetch_array($result));
			echo json_encode($result);
		}
	}
	
} else if ($search_adv_yes) {

	if (!$do_json) 
		echo "<h2>Results</h2>";
	
	$search_adv_array = [];
	array_push($search_adv_array, $search_adv_recu);
	$result_recu = ($search_adv_recu == "") ? null 
		: print_gen_result("Users", false, false, $mysqli, $search_adv_array, $broken);
	$search_adv_array = [];
	array_push($search_adv_array, $search_adv_give);
	$result_give = ($search_adv_give == "") ? null 
		: print_gen_result("Users", false, false, $mysqli, $search_adv_array, $broken);
	$search_adv_array = [];
	array_push($search_adv_array, $search_adv_round);
	$result_round = ($search_adv_round == "") ? null 
		: print_gen_result("Rounds", false, false, $mysqli, $search_adv_array, $broken);
	
	/*
	for ($recu_i = 0; $recu_i < max(mysqli_num_rows ($result_recu), 1); $recu_i++) {
		$myrow_recu = $result_recu->fetch_assoc();
		$recu_name = $myrow_recu['NAME'];
		$recu_id = $myrow_recu['ID'];
		
		for ($give_i = 0; $give_i < max(mysqli_num_rows ($result_give), 1); $give_i++) {
			$myrow_give = $result_give->fetch_assoc();
			
			
			//print_r(mysqli_fetch_fields ($result_recu));
			echo "<br />";
			//print_r(mysqli_fetch_fields ($result_give));
			echo "<br />";
			
			echo "RECU: " . $recu_name . "<br />GIVE: " . $myrow_give['NAME'];
		
		}
	}
	*/
	
	$entry_results = [];
	$entry_number = 0;
	$NAME = 1; $ID = 0; $TITLE = 5; $DESCRIPTION = 6; $CHARACTERS = 7; $IMG_URL = 10;
	$GIVE_NAME = 12; $RECU_NAME = 11; $ROUND_NAME = 13; $RECU_ID = 1; $GIVE_ID = 2; $ROUND_ID = 3;
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
								
				$result2 = getResults($query_str, $mysqli, $search_adv_vars, $broken);
				
				while ($myrow2 = iimysqli_result_fetch_array($result2)) {
					for ($i = 0; $i < count($search_adv_title_arr); $i++) {
						$myrow2[$TITLE] = str_ireplace($search_adv_title_arrp[$i],"<strong>" 
							. $search_adv_title_arr[$i] . "</strong>", 
							$myrow2[$TITLE]);
					}
					for ($i = 0; $i < count($search_adv_character_arr); $i++) {
						$myrow2[$CHARACTERS] = str_ireplace($search_adv_character_arr[$i],"<strong>" 
							. $search_adv_character_arr[$i] . "</strong>", 
							$myrow2[$CHARACTERS]);
					}
					for ($i = 0; $i < count($search_adv_desc_arr); $i++) {
						$myrow2[$DESCRIPTION] = str_ireplace($search_adv_desc_arr[$i],"<strong>" 
							. $search_adv_desc_arr[$i] . "</strong>", $myrow2[$DESCRIPTION]);
					}
				}
				
				
				array_push($entry_results, $result2);
				$entry_number += $result2->nRows;
				
				if ($search_adv_round == "") break;
			}
		
			if ($search_adv_give == "") break;
		}
		
		if ($search_adv_recu == "") break;
	}
	
	# PRINT RESULTS
	if ($do_json) {
		array_push($entry_results, $entry_number);
		echo json_encode($entry_results);
	} else {
		echo "<h3>Entries</h3>";
		echo "<p><strong>Number of Results: " . $entry_number . "</strong></p>";
		while ($search_adv_recu == "" || $myrow_recu = iimysqli_result_fetch_array($result_recu)) {
		/*
			$recu_id = ($search_adv_recu == "") ? -1 : $myrow_recu[$ID];
			$recu_name = ($search_adv_recu == "") ? "" : $myrow_recu[$NAME];
			//echo "<hr />";
			if ($recu_id != -1) echo "<h4><strong>Receiver: </strong>$recu_name</h4>";
	*/
			
			while ($search_adv_give == "" || $myrow_give = iimysqli_result_fetch_array($result_give)) {
			/*
				$give_id = ($search_adv_give == "") ? -1 : $myrow_give[$ID];
				$give_name = ($search_adv_give == "") ? "" : $myrow_give[$NAME];
				//echo "<hr />";
				if ($give_id != -1) echo "<h4><strong>Giver: </strong>$give_name</h4>";
			*/
				while ($search_adv_round == "" || $myrow_round = iimysqli_result_fetch_array($result_round)) {

				/*	$round_id = ($search_adv_round == "") ? -1 : $myrow_round[$ID];
					$round_name = ($search_adv_round == "") ? "" : $myrow_round[$NAME];
					//echo "<hr />";
					if ($round_id != -1) echo "<h4><strong>Round: </strong>$round_name</h4>";
					*/
								
					echo "<div>";
					$result2 = current($entry_results);
					next($entry_results);
					//echo "<p><strong>Number of Results: " . $result2->nRows . "</strong></p>";
					
					
					if ($result2 == $broken) {
						echo "<p>Unable to query database for some reason</p>";
					} else {
						echo "<ul>";
						while ($myrow2 = iimysqli_result_fetch_array($result2)) {
								$myrow2[$TITLE] = str_ireplace($search_adv_title,"<strong>" 
									. $search_adv_title . "</strong>", 
									$myrow2[$TITLE]);
								$myrow2[$CHARACTERS] = str_ireplace($search_adv_character,"<strong>" 
									. $search_adv_character . "</strong>", 
									$myrow2[$CHARACTERS]);
								$myrow2[$DESCRIPTION] = str_ireplace($search_adv_desc,"<strong>" 
									. $search_adv_desc . "</strong>", $myrow2[$DESCRIPTION]);
								 printf('<li>
									<p><a href="http://www.comicdish.com/max/view_entry.php?entry_id=%d"><img src="../thumbs/%s" border="3" /></a></p>
									<p><em>Artist:</em> <a href="http://www.comicdish.com/max/user_profile.php?user_id=%d">%s</a></p>
									<p><em>Receiver:</em> <a href="http://www.comicdish.com/max/user_profile.php?user_id=%d">%s</a></p>
									<p><em>Round Title:</em> <a href="http://www.comicdish.com/max/gallery.php?round_id=%d">%s</a></p>
									<p><em>Title:</em> %s</p>
									<p><em>Characters:</em> %s</p>
									<p><em>Description:</em> %s</p></li>', $myrow2[$ID], $myrow2[$IMG_URL], 
										$myrow2[$GIVE_ID], $myrow2[$GIVE_NAME],
										$myrow2[$RECU_ID], $myrow2[$RECU_NAME],
										$myrow2[$ROUND_ID], $myrow2[$ROUND_NAME],
										$myrow2[$TITLE], $myrow2[$CHARACTERS],$myrow2[$DESCRIPTION]);				 
				
						 }
						 echo "</ul>";
					}
					echo "</div>";
					
					if ($search_adv_round == "") break;
				}
				if ($search_adv_give == "") break;
			}
			if ($search_adv_recu == "") break;
		}
	}
}
?>