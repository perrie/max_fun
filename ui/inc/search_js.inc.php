<div id="accordion">
  <h3>Basic Search</h3>
  <div>
    <form action="index_js.php" method="get">
					<input type="text" name="gen_text" value="<?php echo $search_gen_text; ?>" />
				  <input type="hidden" name="action" value="<?php echo $search_gen_yes_str . $do_json_str ?>" />
				  <input type="submit" value="Search All" name="search_gen_searchAll" />
		
	  <input type="submit" value="Search Users" name="search_gen_searchUsers" />
	  <input type="submit" value="Search Rounds" name="search_gen_searchRounds" />
	  <input type="submit" value="Search Entries" name="search_gen_searchEntries" />
		
	</form>
  </div>
  <h3>Advanced Entry Search</h3>
  <div>
    <form action="index_js.php" method="get">
	<table>
		<tr>
			<td>Receiver:</td>
			<td><input type="text" name="adv_recu" value="<?php echo $search_adv_recu; ?>" /></td>
		</tr>
		<tr>
			<td>Giver:</td>
			<td><input type="text" name="adv_give" value="<?php echo $search_adv_give; ?>" /></td>
		</tr>
		<tr>
			<td>Round Title:</td>
			<td><input type="text" name="adv_round" value="<?php echo $search_adv_round; ?>" /></td>
		</tr>
		<tr>
			<td>Entry Title:</td>
			<td><input type="text" name="adv_title" value="<?php echo $search_adv_title; ?>" /></td>
		</tr>
		<tr>
			<td>Characters:</td>
			<td><input type="text" name="adv_character" value="<?php echo $search_adv_character; ?>" /></td>
		</tr>
		<tr>
			<td>Description:</td>
			<td><input type="text" name="adv_desc" value="<?php echo $search_adv_desc; ?>" /></td>
		</tr>
	  </table>
	  <input type="hidden" name="action" value="<?php echo $search_adv_yes_str . $do_json_str ?>" />
	  <input type="submit" value="Search Entries" />
	</form>
  </div>
</div>
<div style="display:none">
	<div style=" width: 50%; float: left; ">
		<h2>Basic</h2>
		<form action="inc/search_results.inc.php" method="get">
			<table>
				<tr>
					<td>
						<input type="text" name="gen_text" value="<?php echo $search_gen_text; ?>" />
					</td>
					<td>
					  <input type="hidden" name="action" value="<?php echo $search_gen_yes ?>_json" />
					  <input type="submit" value="Search All" name="search_gen_searchAll" /><br />
				</tr>
				<tr>
					<td></td>
					<td>
		  <input type="submit" value="Search Users" name="search_gen_searchUsers" /><br />
		  <input type="submit" value="Search Rounds" name="search_gen_searchRounds" /><br />
		  <input type="submit" value="Search Entries" name="search_gen_searchEntries" />
					</td>
				</tr>
			</table>
		</form>
	</div>
	<div style=" margin-left: 50%; border-left: 1px solid black; padding-left: 5%; ">
		<h2>Advanced Entry Search</h2>
		<form action="inc/search_results.inc.php" method="get">
		<table>
			<tr>
				<td>Receiver:</td>
				<td><input type="text" name="adv_recu" value="<?php echo $search_adv_recu; ?>" /></td>
			</tr>
			<tr>
				<td>Giver:</td>
				<td><input type="text" name="adv_give" value="<?php echo $search_adv_give; ?>" /></td>
			</tr>
			<tr>
				<td>Round Title:</td>
				<td><input type="text" name="adv_round" value="<?php echo $search_adv_round; ?>" /></td>
			</tr>
			<tr>
				<td>Entry Title:</td>
				<td><input type="text" name="adv_title" value="<?php echo $search_adv_title; ?>" /></td>
			</tr>
			<tr>
				<td>Characters:</td>
				<td><input type="text" name="adv_character" value="<?php echo $search_adv_character; ?>" /></td>
			</tr>
			<tr>
				<td>Description:</td>
				<td><input type="text" name="adv_desc" value="<?php echo $search_adv_desc; ?>" /></td>
			</tr>
		  </table>
		  <input type="hidden" name="action" value="<?php echo $search_adv_yes ?>_json" />
		  <input type="submit" value="Search Entries" />
		</form>
	</div>
</div>