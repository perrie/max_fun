<?php

include("inc/search_params.inc.php");

?>
<h1>Search</h1>
<p><em>Instructions:</em> Enter text to search in the fields provided. Partial phrases are accepted. Empty search fields will not be included in the search. There is also some issue with the character encoding (so searches with accented letters may be faulty). <strong>Warning:</strong> System currently returns all results, so the page may take a while to load if the entry results are large in number.</p>
<p><em>Advanced Entry Search case examples</em>:</p>
<ul>
<li>Search for all entries received by clemon from Hawk: <code>Receiver: clemon; Giver: hawk</code></li>
<li>Search for all entries that contain clemon's character "claire" and "nova": <code>Receiver: clemon; Characters: claire Nova</code></li>
<li>Search for all entries drawn by clemon in 2014: <code>Giver: clemon; Round Title: 2014</code></li>
</ul>
<div style=" width: 50%; float: left; ">
	<h2>Basic</h2>
	<form action="index.php" method="get">
		<table>
			<tr>
				<td>
					<input type="text" name="gen_text" value="<?php echo $search_gen_text; ?>" />
				</td>
				<td>
				  <input type="hidden" name="action" value="<?php echo $search_gen_yes_str ?>" />
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
	<form action="index.php" method="get">
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
	  <input type="hidden" name="action" value="<?php echo $search_adv_yes_str ?>" />
	  <input type="submit" value="Search Entries" />
	</form>
</div>