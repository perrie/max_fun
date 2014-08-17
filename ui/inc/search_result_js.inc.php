
<?php
/*
$grump_str = "?do_json=";
foreach ($_GET as $key => $value) {
	$grump_str .= "&" . $key . "=" . $value;
}
echo $grump_str;
$output = file_get_contents("inc/search_results.inc.php" . $grump_str);
$json_output = json_decode($output);;
echo "<br />";
echo var_dump($json_output);
//echo var_dump($_GET);
//search_results.inc.php?do_json=&gen_text=clemon&action=searching_gen_json&search_gen_searchEntries=Search+Entries
*/
?>
<script>
$( document ).ready(function() {
     $.ajax({
                    url: "test.json",
                    //force to handle it as text
                    dataType: "text",
                    success: function(data) {
                        
                        //data downloaded so we call parseJSON function 
                        //and pass downloaded data
                        var json = $.parseJSON(data);
                        //now json variable contains data in json format
                        //let's display a few items
						alert(json);
                        //$('#results').html('Plugin name: ' + json.name + '<br />Author: ' + json.author.name);
                    }
                });
});
</script>

<p>SEARCH RESULT PHP</p>
<div class="main">
	<ul id="og-grid" class="og-grid">
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/1.jpg" data-title="Azuki bean" data-description="Swiss chard pumpkin bunya nuts maize plantain aubergine napa cabbage soko coriander sweet pepper water spinach winter purslane shallot tigernut lentil beetroot.">
				<img src="../images/thumbs/1.jpg" alt="img01"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/2.jpg" data-title="Veggies sunt bona vobis" data-description="Komatsuna prairie turnip wattle seed artichoke mustard horseradish taro rutabaga ricebean carrot black-eyed pea turnip greens beetroot yarrow watercress kombu.">
				<img src="../images/thumbs/2.jpg" alt="img02"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/3.jpg" data-title="Dandelion horseradish" data-description="Cabbage bamboo shoot broccoli rabe chickpea chard sea lettuce lettuce ricebean artichoke earthnut pea aubergine okra brussels sprout avocado tomato.">
				<img src="../images/thumbs/3.jpg" alt="img03"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/1.jpg" data-title="Azuki bean" data-description="Swiss chard pumpkin bunya nuts maize plantain aubergine napa cabbage soko coriander sweet pepper water spinach winter purslane shallot tigernut lentil beetroot.">
				<img src="../images/thumbs/1.jpg" alt="img01"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/2.jpg" data-title="Veggies sunt bona vobis" data-description="Komatsuna prairie turnip wattle seed artichoke mustard horseradish taro rutabaga ricebean carrot black-eyed pea turnip greens beetroot yarrow watercress kombu.">
				<img src="../images/thumbs/2.jpg" alt="img02"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/3.jpg" data-title="Dandelion horseradish" data-description="Cabbage bamboo shoot broccoli rabe chickpea chard sea lettuce lettuce ricebean artichoke earthnut pea aubergine okra brussels sprout avocado tomato.">
				<img src="../images/thumbs/3.jpg" alt="img03"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/1.jpg" data-title="Azuki bean" data-description="Swiss chard pumpkin bunya nuts maize plantain aubergine napa cabbage soko coriander sweet pepper water spinach winter purslane shallot tigernut lentil beetroot.">
				<img src="../images/thumbs/1.jpg" alt="img01"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/2.jpg" data-title="Veggies sunt bona vobis" data-description="Komatsuna prairie turnip wattle seed artichoke mustard horseradish taro rutabaga ricebean carrot black-eyed pea turnip greens beetroot yarrow watercress kombu.">
				<img src="../images/thumbs/2.jpg" alt="img02"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/3.jpg" data-title="Dandelion horseradish" data-description="Cabbage bamboo shoot broccoli rabe chickpea chard sea lettuce lettuce ricebean artichoke earthnut pea aubergine okra brussels sprout avocado tomato.">
				<img src="../images/thumbs/3.jpg" alt="img03"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/1.jpg" data-title="Azuki bean" data-description="Swiss chard pumpkin bunya nuts maize plantain aubergine napa cabbage soko coriander sweet pepper water spinach winter purslane shallot tigernut lentil beetroot.">
				<img src="../images/thumbs/1.jpg" alt="img01"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/2.jpg" data-title="Veggies sunt bona vobis" data-description="Komatsuna prairie turnip wattle seed artichoke mustard horseradish taro rutabaga ricebean carrot black-eyed pea turnip greens beetroot yarrow watercress kombu.">
				<img src="../images/thumbs/2.jpg" alt="img02"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/3.jpg" data-title="Dandelion horseradish" data-description="Cabbage bamboo shoot broccoli rabe chickpea chard sea lettuce lettuce ricebean artichoke earthnut pea aubergine okra brussels sprout avocado tomato.">
				<img src="../images/thumbs/3.jpg" alt="img03"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/1.jpg" data-title="Azuki bean" data-description="Swiss chard pumpkin bunya nuts maize plantain aubergine napa cabbage soko coriander sweet pepper water spinach winter purslane shallot tigernut lentil beetroot.">
				<img src="../images/thumbs/1.jpg" alt="img01"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/2.jpg" data-title="Veggies sunt bona vobis" data-description="Komatsuna prairie turnip wattle seed artichoke mustard horseradish taro rutabaga ricebean carrot black-eyed pea turnip greens beetroot yarrow watercress kombu.">
				<img src="../images/thumbs/2.jpg" alt="img02"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/3.jpg" data-title="Dandelion horseradish" data-description="Cabbage bamboo shoot broccoli rabe chickpea chard sea lettuce lettuce ricebean artichoke earthnut pea aubergine okra brussels sprout avocado tomato.">
				<img src="../images/thumbs/3.jpg" alt="img03"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/1.jpg" data-title="Azuki bean" data-description="Swiss chard pumpkin bunya nuts maize plantain aubergine napa cabbage soko coriander sweet pepper water spinach winter purslane shallot tigernut lentil beetroot.">
				<img src="../images/thumbs/1.jpg" alt="img01"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/2.jpg" data-title="Veggies sunt bona vobis" data-description="Komatsuna prairie turnip wattle seed artichoke mustard horseradish taro rutabaga ricebean carrot black-eyed pea turnip greens beetroot yarrow watercress kombu.">
				<img src="../images/thumbs/2.jpg" alt="img02"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/3.jpg" data-title="Dandelion horseradish" data-description="Cabbage bamboo shoot broccoli rabe chickpea chard sea lettuce lettuce ricebean artichoke earthnut pea aubergine okra brussels sprout avocado tomato.">
				<img src="../images/thumbs/3.jpg" alt="img03"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/1.jpg" data-title="Azuki bean" data-description="Swiss chard pumpkin bunya nuts maize plantain aubergine napa cabbage soko coriander sweet pepper water spinach winter purslane shallot tigernut lentil beetroot.">
				<img src="../images/thumbs/1.jpg" alt="img01"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/2.jpg" data-title="Veggies sunt bona vobis" data-description="Komatsuna prairie turnip wattle seed artichoke mustard horseradish taro rutabaga ricebean carrot black-eyed pea turnip greens beetroot yarrow watercress kombu.">
				<img src="../images/thumbs/2.jpg" alt="img02"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/3.jpg" data-title="Dandelion horseradish" data-description="Cabbage bamboo shoot broccoli rabe chickpea chard sea lettuce lettuce ricebean artichoke earthnut pea aubergine okra brussels sprout avocado tomato.">
				<img src="../images/thumbs/3.jpg" alt="img03"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/1.jpg" data-title="Azuki bean" data-description="Swiss chard pumpkin bunya nuts maize plantain aubergine napa cabbage soko coriander sweet pepper water spinach winter purslane shallot tigernut lentil beetroot.">
				<img src="../images/thumbs/1.jpg" alt="img01"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/2.jpg" data-title="Veggies sunt bona vobis" data-description="Komatsuna prairie turnip wattle seed artichoke mustard horseradish taro rutabaga ricebean carrot black-eyed pea turnip greens beetroot yarrow watercress kombu.">
				<img src="../images/thumbs/2.jpg" alt="img02"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/3.jpg" data-title="Dandelion horseradish" data-description="Cabbage bamboo shoot broccoli rabe chickpea chard sea lettuce lettuce ricebean artichoke earthnut pea aubergine okra brussels sprout avocado tomato.">
				<img src="../images/thumbs/3.jpg" alt="img03"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/1.jpg" data-title="Azuki bean" data-description="Swiss chard pumpkin bunya nuts maize plantain aubergine napa cabbage soko coriander sweet pepper water spinach winter purslane shallot tigernut lentil beetroot.">
				<img src="../images/thumbs/1.jpg" alt="img01"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/2.jpg" data-title="Veggies sunt bona vobis" data-description="Komatsuna prairie turnip wattle seed artichoke mustard horseradish taro rutabaga ricebean carrot black-eyed pea turnip greens beetroot yarrow watercress kombu.">
				<img src="../images/thumbs/2.jpg" alt="img02"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/3.jpg" data-title="Dandelion horseradish" data-description="Cabbage bamboo shoot broccoli rabe chickpea chard sea lettuce lettuce ricebean artichoke earthnut pea aubergine okra brussels sprout avocado tomato.">
				<img src="../images/thumbs/3.jpg" alt="img03"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/1.jpg" data-title="Azuki bean" data-description="Swiss chard pumpkin bunya nuts maize plantain aubergine napa cabbage soko coriander sweet pepper water spinach winter purslane shallot tigernut lentil beetroot.">
				<img src="../images/thumbs/1.jpg" alt="img01"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/2.jpg" data-title="Veggies sunt bona vobis" data-description="Komatsuna prairie turnip wattle seed artichoke mustard horseradish taro rutabaga ricebean carrot black-eyed pea turnip greens beetroot yarrow watercress kombu.">
				<img src="../images/thumbs/2.jpg" alt="img02"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/3.jpg" data-title="Dandelion horseradish" data-description="Cabbage bamboo shoot broccoli rabe chickpea chard sea lettuce lettuce ricebean artichoke earthnut pea aubergine okra brussels sprout avocado tomato.">
				<img src="../images/thumbs/3.jpg" alt="img03"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/1.jpg" data-title="Azuki bean" data-description="Swiss chard pumpkin bunya nuts maize plantain aubergine napa cabbage soko coriander sweet pepper water spinach winter purslane shallot tigernut lentil beetroot.">
				<img src="../images/thumbs/1.jpg" alt="img01"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/2.jpg" data-title="Veggies sunt bona vobis" data-description="Komatsuna prairie turnip wattle seed artichoke mustard horseradish taro rutabaga ricebean carrot black-eyed pea turnip greens beetroot yarrow watercress kombu.">
				<img src="../images/thumbs/2.jpg" alt="img02"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/3.jpg" data-title="Dandelion horseradish" data-description="Cabbage bamboo shoot broccoli rabe chickpea chard sea lettuce lettuce ricebean artichoke earthnut pea aubergine okra brussels sprout avocado tomato.">
				<img src="../images/thumbs/3.jpg" alt="img03"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/1.jpg" data-title="Azuki bean" data-description="Swiss chard pumpkin bunya nuts maize plantain aubergine napa cabbage soko coriander sweet pepper water spinach winter purslane shallot tigernut lentil beetroot.">
				<img src="../images/thumbs/1.jpg" alt="img01"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/2.jpg" data-title="Veggies sunt bona vobis" data-description="Komatsuna prairie turnip wattle seed artichoke mustard horseradish taro rutabaga ricebean carrot black-eyed pea turnip greens beetroot yarrow watercress kombu.">
				<img src="../images/thumbs/2.jpg" alt="img02"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/3.jpg" data-title="Dandelion horseradish" data-description="Cabbage bamboo shoot broccoli rabe chickpea chard sea lettuce lettuce ricebean artichoke earthnut pea aubergine okra brussels sprout avocado tomato.">
				<img src="../images/thumbs/3.jpg" alt="img03"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/1.jpg" data-title="Azuki bean" data-description="Swiss chard pumpkin bunya nuts maize plantain aubergine napa cabbage soko coriander sweet pepper water spinach winter purslane shallot tigernut lentil beetroot.">
				<img src="../images/thumbs/1.jpg" alt="img01"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/2.jpg" data-title="Veggies sunt bona vobis" data-description="Komatsuna prairie turnip wattle seed artichoke mustard horseradish taro rutabaga ricebean carrot black-eyed pea turnip greens beetroot yarrow watercress kombu.">
				<img src="../images/thumbs/2.jpg" alt="img02"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/3.jpg" data-title="Dandelion horseradish" data-description="Cabbage bamboo shoot broccoli rabe chickpea chard sea lettuce lettuce ricebean artichoke earthnut pea aubergine okra brussels sprout avocado tomato.">
				<img src="../images/thumbs/3.jpg" alt="img03"/>
			</a>
		</li>
		<li>
			<a href="http://cargocollective.com/jaimemartinez/" data-largesrc="../images/1.jpg" data-title="Azuki bean" data-description="Swiss chard pumpkin bunya nuts maize plantain aubergine napa cabbage soko coriander sweet pepper water spinach winter purslane shallot tigernut lentil beetroot.">
				<img src="../images/thumbs/1.jpg" alt="img01"/>
			</a>
		</li>
	</ul>
	<p>Filler text by <a href="http://veggieipsum.com/">Veggie Ipsum</a></p>
	<a id="og-additems" href="#">add more</a>
</div>
