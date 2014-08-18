<?php
/* Print results given result
 * type				- type of result to print
 * searchAll		- print all values / format stuff
 * result			- result source to print
 * search_gen_text	- original search text array
 */
function print_gen_result($type, $searchAll, $result, $search_gen_text) {

	if ($type == "Users") {
		$div_value = '<div style=" float: left; width: 25%; ">';
	} else if ($type == "Rounds") {
		$div_value = '<div style=" float: left; width: 25%; padding-left: 5%; border-left: 1px solid black; ">';
	} else if ($type == "Entries") {
		$div_value = '<div style=" margin-left: 55%; border-left: 1px solid black; padding-left: 5%; ">'; 
	} 
	if (!$searchAll)
		$div_value = '<div>';
	
	echo $div_value;		
	echo "<h3>$type</h3>";
	
	if (!$result) {
		echo "<p>Unable to query database for some reason</p>";
	} else {
		echo "<p><strong>Number of Results: " . $result->nRows . "</strong></p>";
		echo "<ul>";
		while ($myrow = iimysqli_result_fetch_array($result)) {
				#########
				# Users #
				#########
				if ($type == "Users") {
					$NAME = 1; $ID = 0;
					for ($i = 0; $i < count($search_gen_text); $i++) {
						$myrow[$NAME] = str_ireplace($search_gen_text[$i],"<strong>" 
							. $search_gen_text[$i] . "</strong>", $myrow[$NAME]);
					}
					 printf('<li><a href="http://www.comicdish.com/max/user_profile.php?user_id=%d">%s</a></li>', $myrow[$ID], $myrow[$NAME]);
				##########
				# Rounds #
				##########
				 } else if ($type == "Rounds") {
					$NAME = 1; $ID = 0; $NUM_USER = 4;
					for ($i = 0; $i < count($search_gen_text); $i++) {
						$myrow[$NAME] = str_ireplace($search_gen_text[$i],"<strong>" 
							. $search_gen_text[$i] . "</strong>", $myrow[$NAME]);
					}
					 printf('<li><a href="http://www.comicdish.com/max/gallery.php?round_id=%d">%s</a> - %s participants</li>', $myrow[$ID], $myrow[$NAME], $myrow[$NUM_USER]);
				###########
				# Entries #
				###########
				 } else if ($type == "Entries") {
					$NAME = 1; $ID = 0; $TITLE = 5; $DESCRIPTION = 6; $CHARACTERS = 7; 
					$IMG_URL = 10; 	$GIVE_NAME = 12; $RECU_NAME = 11; $ROUND_NAME = 13; $RECU_ID = 1;
					$GIVE_ID = 2; $ROUND_ID = 3;
					for ($i = 0; $i < count($search_gen_text); $i++) {
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

############################
#                          #
#   PRINT GENERAL SEARCH   #
#                          #
############################
if ($search_gen_yes) {

	echo '<hr />';
	echo '<h2>Results</h2>';
	$searchAll = (isset($_GET['search_gen_searchAll'])) ? true : false;
	$search_gen_array = [$search_gen_text];
	#########
	# Users #
	#########
	if ($searchAll || isset($_GET['search_gen_searchUsers'])) {
		$result = print_gen_result("Users", $searchAll, $result_users, $search_gen_array);
	}
	##########
	# Rounds #
	##########
	if ($searchAll || isset($_GET['search_gen_searchRounds'])) {
		$result = print_gen_result("Rounds", $searchAll, $result_rounds, $search_gen_array);
	}
	
	# ENTRY
	if ($searchAll || isset($_GET['search_gen_searchEntries'])) {
		$result = print_gen_result("Entries", $searchAll, $result_entries, $search_gen_array);
	}

	
#############################
#                           #
#   PRINT ADVANCED SEARCH   #
#                           #
#############################
} else if ($search_adv_yes) {

	
	echo "<h2>Results</h2>";
	echo "<h3>Entries</h3>";
	echo "<p><strong>Number of Results: " . $entry_number . "</strong></p>";
	$NAME = 1; $ID = 0; $TITLE = 5; $DESCRIPTION = 6; $CHARACTERS = 7; 
		$IMG_URL = 10; 	$GIVE_NAME = 12; $RECU_NAME = 11; $ROUND_NAME = 13; $RECU_ID = 1;
		$GIVE_ID = 2; $ROUND_ID = 3;
	while ($search_adv_recu == "" || $myrow_recu = iimysqli_result_fetch_array($result_recu)) {
		$recu_name = ($search_adv_recu == "") ? "" : str_ireplace($search_adv_recu,"<strong>" 
			. $search_adv_recu . "</strong>", $myrow_recu[$NAME]);
		while ($search_adv_give == "" || $myrow_give = iimysqli_result_fetch_array($result_give)) {
			$give_name = ($search_adv_give == "") ? "" : str_ireplace($search_adv_give,"<strong>" 
				. $search_adv_give . "</strong>", $myrow_give[$NAME]);
			while ($search_adv_round == "" || $myrow_round = iimysqli_result_fetch_array($result_round)) {
			$round_name = ($search_adv_round == "") ? "" : str_ireplace($search_adv_round,"<strong>" 
				. $search_adv_round . "</strong>", $myrow_round[$NAME]);							
				echo "<div>";
				$result2 = current($entry_results);
				next($entry_results);
				
				if (!$result2) {
					echo "<p>Unable to query database for some reason</p>";
				} else {				
					echo "<ul>";
					while ($myrow2 = iimysqli_result_fetch_array($result2)) {
						for ($i = 0; $i < count($search_adv_title_arr); $i++) {
							$myrow2[$TITLE] = str_ireplace($search_adv_title_arr[$i],"<strong>" 
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
						$recu_name = ($search_adv_recu == "") ? $myrow2[$RECU_NAME] : $recu_name;
						$give_name = ($search_adv_give == "") ? $myrow2[$GIVE_NAME] : $give_name;
						$round_name = ($search_adv_round == "") ? $myrow2[$ROUND_NAME] : $round_name;	
					/*
							$myrow2[$TITLE] = str_ireplace($search_adv_title,"<strong>" 
								. $search_adv_title . "</strong>", 
								$myrow2[$TITLE]);
							$myrow2[$CHARACTERS] = str_ireplace($search_adv_character,"<strong>" 
								. $search_adv_character . "</strong>", 
								$myrow2[$CHARACTERS]);
							$myrow2[$DESCRIPTION] = str_ireplace($search_adv_desc,"<strong>" 
								. $search_adv_desc . "</strong>", $myrow2[$DESCRIPTION]);*/
							 printf('<li>
								<p><a href="http://www.comicdish.com/max/view_entry.php?entry_id=%d"><img src="../thumbs/%s" border="3" /></a></p>
								<p><em>Artist:</em> <a href="http://www.comicdish.com/max/user_profile.php?user_id=%d">%s</a></p>
								<p><em>Receiver:</em> <a href="http://www.comicdish.com/max/user_profile.php?user_id=%d">%s</a></p>
								<p><em>Round Title:</em> <a href="http://www.comicdish.com/max/gallery.php?round_id=%d">%s</a></p>
								<p><em>Title:</em> %s</p>
								<p><em>Characters:</em> %s</p>
								<p><em>Description:</em> %s</p></li>', $myrow2[$ID], $myrow2[$IMG_URL], 
									$myrow2[$GIVE_ID], $give_name,
									$myrow2[$RECU_ID], $recu_name,
									$myrow2[$ROUND_ID], $round_name,
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
?>