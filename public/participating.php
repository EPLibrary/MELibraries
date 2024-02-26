<?php
session_start();
$pageTitle="ME Libraries | Participating Libraries";
include 'header.php';

/*
	On this page we will insert the member into the user table if they haven't been added yet.
	After this, we will insert the membership for the library that they have just joined into the membership table

	There will be a component here that requires talking to the server to do.
	Send request to create/update user,
	$message=array(
		"code" => "CREATE_CUSTOMER",
		"authorityToken" => $authorityToken,
		"customer" => "null"
	);
*/

?>
<div class="mainContent" id="mainContent">

<a href="index.php" style="border:none;" title="Return to Login"><img id="meLogoTop" src="images/ME_Libraries_Logo_black.png"/></a>
<h1 class="pageTitle greenbg">Libraries</h1>

<div class="subContent">
<h2>Participating Libraries</h2>
<p>The libraries below are currently all participating in the ME Libraries service.</p>

<ul>
	<li>Calgary Public Library</li>
	<li>Chinook Arch Regional Library System</li>
	<ul class="columns">
			<li><a href="http://www.arrowwoodlibrary.ca/">Arrowwood Municipal Library</a></li>
			<li><a href="http://www.barnwelllibrary.ca">Barnwell Public Library</a></li>
			<li><a href="http://www.cardstonlibrary.ca/">Jim &amp; Mary Kearl Library of Cardston</a></li>
			<li><a href="http://www.carmangaylibrary.ca/">Carmangay Municipal Library</a></li>
			<li><a href="http://www.championlibrary.ca/">Champion Municipal Library</a></li>
			<li><a href="http://www.claresholmlibrary.ca/">Claresholm Public Library</a></li>
			<li><a href="http://www.coaldalelibrary.ca/">Coaldale Public Library</a></li>
			<li><a href="http://www.couttslibrary.ca/">Coutts Municipal Library</a></li>
			<li><a href="http://www.crowsnestpasslibrary.ca">Crowsnest Pass Municipal Library</a></li>
			<li><a href="http://www.enchantlibrary.ca/">Enchant Community Library</a></li>
			<li><a href="http://www.fortmacleodlibrary.ca/">Fort Macleod RCMP Centennial Library</a></li>
			<li><a href="http://www.glenwoodlibrary.ca/">Glenwood Municipal Library</a></li>
			<li><a href="http://www.granumlibrary.ca/">Granum Municipal Library</a></li>
			<li><a href="http://www.grassylakelibrary.ca/">Grassy Lake Community Library</a></li>
			<li><a href="http://www.hayslibrary.ca/">Hays Public Library</a></li>
			<li><a href="http://www.kainailibrary.ca/">Kainai Public Library</a></li>
			<li><a href="http://www.lethbridgepubliclibrary.ca/">Lethbridge Public Library</a></li>
	</ul><!--columns-->
	<ul class="columns">
			<li><a href="http://www.lomondlibrary.ca">Lomond Library</a></li>
			<li><a href="http://www.magrathlibrary.ca/">Magrath Public Library</a></li>
			<li><a href="http://www.mfem.ca/">M&eacute;diath&egrave;que Francophone Emma Morrier</a></li>
			<li><a href="http://www.milkriverlibrary.ca/">Milk River Municipal Library</a></li>
			<li><a href="http://www.milolibrary.ca/">Milo Municipal Library</a></li>
			<li><a href="http://www.nantonlibrary.ca/">Nanton Thelma Fanning Library</a></li>
			<li><a href="http://www.picturebuttelibrary.ca/">Picture Butte Municipal Library</a></li>
			<li><a href="http://www.pinchercreeklibrary.ca/">Pincher Creek Municipal Library</a></li>
			<li><a href="http://www.raymondlibrary.ca/">Raymond Public Library</a></li>
			<li><a href="http://www.stavelylibrary.ca/">Stavely Municipal Library</a></li>
			<li><a href="http://www.stirlinglibrary.ca/">Stirling Theodore Brandley Library</a></li>
			<li><a href="http://www.taberlibrary.ca/">Taber Public Library</a></li>
			<li><a href="http://www.vauxhalllibrary.ca/">Vauxhall Public Library</a></li>
			<li><a href="http://www.vulcanlibrary.ca/">Vulcan Municipal Library</a></li>
			<li><a href="http://www.warnerlibrary.ca/">Warner Municipal Library</a></li>
			<li><a href="http://www.wrenthamlibrary.ca/">Wretham Memorial Library</a></li>
	</ul><!--columns-->
	<li style="clear:left;">Edmonton Public Library</li>
	<li>Fort Saskatchewan Public Library</li>
	<li>Parkland Regional Library</li>
	<ul class="columns">
		<li>Alix Public Library</li>
		<li>Alliance Public Library</li>
		<li>Amisk Municipal Library</li>
		<li>Bashaw Municipal Library</li>
		<li>Bawlf - David Knipe Memorial Library</li>
		<li>Bentley Municipal Library</li>
		<li>Big Valley Municipal Library</li>
		<li>Blackfalds Public Library</li>
		<li>Bodo Public Library</li>
		<li>Bowden Public Library</li>
		<li>Brownfield Community Library</li>
		<li>Cadogan Public Library</li>
		<li>Camrose Public Library</li>
		<li>Caroline Municipal Library</li>
		<li>Carstairs Public Library</li>
		<li>Castor Municipal Library</li>
		<li>Clive Public Library</li>
		<li>Coronation Memorial Library</li>
		<li>Cremona Municipal Library</li>
		<li>Czar Public Library</li>
		<li>David Knipe Memorial Library (Bawlf)</li>
		<li>Daysland Public Library</li>
		<li>Delburne Municipal Library</li>
		<li>Didsbury Municipal Library</li>
		<li>Donalda Public Library</li>
		<li>Eckville Municipal Library</li>

	</ul><!--columns-->
	<ul class="columns">
		<li>Edberg Public Library</li>
		<li>Elnora Public Library</li>
		<li>Forestburg Public Library</li>
		<li>Galahad Public Library</li>
		<li>Hardisty Public Library</li>
		<li>Hay Lakes Municipal Library</li>
		<li>Heisler Municipal Library</li>
		<li>Hughenden Public Library</li>
		<li>Innisfail Public Library</li>
		<li>Killam Public Library</li>
		<li>Lacombe - Mary C. Moore Public Library</li>
		<li>Lougheed Public Library</li>
		<li>Mary C. Moore Public Library (Lacombe)</li>
		<li>Nordegg Public Library</li>
		<li>Olds Municipal Library</li>
		<li>Penhold and District Public Library</li>
		<li>Ponoka Jubilee Library</li>
		<li>Provost Municipal Library</li>
		<li>Rimbey Municipal Library</li>
		<li>Rocky Mountain House Public Library</li>
		<li>Sedgewick & District Municipal Library</li>
		<li>Spruce View Community Library</li>
		<li>Stettler Public Library</li>
		<li>Sundre Municipal Library</li>
		<li>Sylvan Lake Municipal Library</li>
		<li>Water Valley Public Library</li>
	</ul><!--columns-->
	<li style="clear:left;">Red Deer Public Library</li>
	<li>St. Albert Public Library</li>
	<li>Strathcona County Library</li>
	<li>Shortgrass Library System</li>
	<ul class="columns">
		<li>Alcoma Community Library</li>
		<li>Bassano Memorial Library</li>
		<li>Bow Island Municipal Library</li>
		<li>Brooks Public Library</li>
		<li>Duchess &amp; District Public Library</li>
		<li>Foremost Municipal Library</li>
	</ul><!--columns-->
	<ul class="columns">
		<li>Graham Community Library</li>
		<li>Medicine Hat Public Library</li>
		<li>Redcliff Public Library</li>
		<li>Rolling Hills Public Library</li>
		<li>Rosemary Community Library</li>
		<li>Tilley &amp; District Public Library</li>
	</ul><!--columns-->
	<li style="clear:left;">TRAC (The Regional Automation Consortium)</li>
	<ul>
		<li style="clear:left;">Marigold Library System</li>
		<ul class="columns">
			<li>Acadia Municipal Library</li>
			<li>Acme Municipal Library</li>
			<li>Airdrie Public Library</li>
			<li>Banff Public Library</li>
			<li>Beiseker Municipal Library</li>
			<li>Berry Creek Community Library</li>
			<li>Bighorn Library</li>
      <li>Bragg Creek Satellite Library</li>
			<li>Canmore Public Library</li>
			<li>Village of Carbon Library</li>
			<li>Carseland Community Library</li>
			<li>Chestermere Public Library</li>
			<li>Cochrane Public Library</li>
			<li>Consort Municipal Library</li>
			<li>Crossfield Municipal Library</li>
			<li>Delia Municipal Library</li>
			<li>Drumheller Public Library</li>
			<li>Empress Municipal Library</li>
      <li>Gleichen and District Library</li>
		</ul><!--columns-->
		<ul class="columns">
			<li>Hanna Municipal Library</li>
			<li>High River Library</li>
			<li>Hussar Municipal Library</li>
			<li>Irricana & Rural Municipal Library</li>
      <li>Langdon Community Library</li>
			<li>Linden Municipal Library</li>
			<li>Longview Municipal Library</li>
			<li>Millarville Community Library</li>
			<li>Morrin Municipal Library</li>
			<li>Okotoks Public Library</li>
			<li>Town of Oyen Library</li>
			<li>Rockyford Library</li>
			<li>Rumsey Community Library</li>
			<li>Sheep River Library</li>
			<li>Standard Municipal Library</li>
			<li>Strathmore Municipal Library</li>
			<li>Three Hills Municipal Library</li>
			<li>Trochu Municipal Library</li>
			<li>Youngstown Municipal Library</li>
		</ul><!--columns-->
		<li style="clear:left;">Northern Lights Library System</li>
		<ul class="columns">
			<li>Alice B. Donahue Library and Archives</li>
			<li>Alice Melnyk Public Library</li>
			<li>Anne Chorney Public Library</li>
			<li>Ashmont Public Library</li>
			<li>Bon Accord Public Library</li>
			<li>Bonnyville Municipal Library</li>
			<li>Boyle Public Library</li>
			<li>Chauvin Municipal Library</li>
			<li>Cold Lake Public Library - 4 Wing</li>
			<li>Cold Lake Public Library - Bookmobile</li>
			<li>Cold Lake Public Library - Grand Centre Branch</li>
			<li>Cold Lake Public Library - Harbour View</li>
			<li>Edgerton Public Library</li>
			<li>Edmonton Garrison Community Library</li>
			<li>Elk Point Municipal Library</li>
			<li>Gibbons Municipal Library</li>
			<li>Grassland Community Library</li>
			<li>Holden Municipal Library</li>
			<li>Innisfree Public Library</li>
			<li>Irma Municipal Library</li>
			<li>Kitscoty Public Library</li>
			<li>Lamont Public Library</li>
			<li>Mallaig Public Library</li>
			<li>Mannville Centennial Public Library</li>
		</ul><!--columns-->
		<ul class="columns">
			<li>Marwayne Public Library</li>
			<li>McPherson Municipal Library</li>
			<li>Metro Kalyn Community Library</li>
			<li>Morinville Public Library</li>
			<li>Mundare Municipal Public Library</li>
			<li>Myrnam Community Library</li>
			<li>Newbrook Public Library</li>
			<li>Plamondon Municipal Library</li>
			<li>Radway and District Municipal Library</li>
			<li>Redwater Public Library</li>
			<li>Rochester Municipal Library</li>
			<li>Smoky Lake Municipal Library</li>
			<li>St. Paul Municipal Library</li>
			<li>Stuart MacPherson Public Library</li>
			<li>Thorhild Library</li>
			<li>Three Cities Public Library</li>
			<li>Tofield Municipal Library</li>
			<li>Vegreville Centennial Library</li>
			<li>Vermilion Public Library</li>
			<li>Viking Municipal Library</li>
			<li>Vilna Municipal Library</li>
			<li>Wainwright Public Library</li>
			<li>Wandering River Women's Institute Public Library</li>
		</ul><!--columns-->
		<li style="clear:left;">Peace Library System</li>
		<ul class="columns">
			<li>Bear Point Community Library</li>
			<li>Beaverlodge Public Library</li>
			<li>Berwyn Municipal Library</li>
			<li>Bibliotheque de St. Isidore</li>
			<li>Bonanza Municipal Library</li>
			<li>Brownvale Community Library</li>
			<li>Calling Lake Public Library</li>
			<li>DeBolt Public Library</li>
			<li>Dixonville Community Library</li>
			<li>Eaglesham Public LIbrary</li>
			<li>Elmworth Community Library</li>
			<li>Fairview Public Library</li>
			<li>Falher Library - Bibliotheque Dentinger</li>
			<li>Flatbush Community Library</li>
			<li>Fox Creek Municipal Library</li>
			<li>Grande Prairie Public LIbrary</li>
			<li>Grimshaw Municipal Library</li>
			<li>High Level Municipal Library</li>
			<li>High Prairie Municipal Library</li>
			<li>Hines Creek Municipal Library</li>
			<li>Hythe Municipal Library</li>
			<li>Keg River Community Library</li>
			<li>Kinuso Municipal Library</li>
		</ul><!--columns-->
		<ul class="columns">
			<li>LaGlace Community Library</li>
			<li>Manning Municipal Library</li>
			<li>McLennan Municipal Library</li>
			<li>Menno-Simons Community Library</li>
			<li>Nampa Municipal Library</li>
			<li>Paddle Prairie Public Library</li>
			<li>Peace River Municipal Library</li>
			<li>Rainbow Lake Municipal Library</li>
			<li>Red Earth Public Library</li>
			<li>Rycroft Municipal Library</li>
			<li>Savanna Municipal Library</li>
			<li>Sexsmith Shannon Municipal LIbrary</li>
			<li>Slave Lake Municipal Library</li>
			<li>Smith Community Library</li>
			<li>Spirit River Municipal Library</li>
			<li>Tangent Community Library</li>
			<li>Valhalla Community Library</li>
			<li>Valleyview Municipal Library</li>
			<li>Wabasca Public Library</li>
			<li>Wembley Public Library</li>
			<li>Woking Municipal Library</li>
			<li>Worsley &amp; District Library</li>
		</ul><!--columns-->
		<li style="clear:left;">Yellowhead Regional Library</li>
		<ul class="columns">
			<li>Alberta Beach Municipal Library</li>
			<li>Alder Flats Buck Lake Public Library</li>
			<li>Barrhead Public Library</li>
			<li>Beaumont Library Bibliotheque de Beaumont</li>
			<li>Blue Ridge Community Library</li>
			<li>Breton Municipal Library</li>
			<li>Calmar Public Library</li>
			<li>Darwell Public Library</li>
			<li>Devon Public Library</li>
			<li>Drayton Valley Municipal Library</li>
			<li>Drayton Valley Rotary Public Library</li>
			<li>Duffield Public Library</li>
			<li>Edson Public Library</li>
			<li>Entwistle Public Library</li>
			<li>Evansburg &amp; District Public Library</li>
			<li>Fort Assiniboine Public Library</li>
			<li>Grande Cache Municipal Library</li>
			<li>Hinton Municipal Library</li>
			<li>Jarvie Public Library</li>
			<li>Jasper Municipal Library</li>
			<li>Keephills Public Library</li>
			<li>Leduc Public Library</li>
			<li>M.Alice Frose Public Library</li>
		</ul><!--columns-->
		<ul class="columns">
			<li>Mayerthorpe Public Library</li>
			<li>Millet Public Library</li>
			<li>Neerlandia Public Library</li>
			<li>New Sarepta Public Library</li>
			<li>Niton Library</li>
			<li>Onoway Public Library</li>
			<li>Pigeon Lake Public Library</li>
			<li>Rich Valley Public Library</li>
			<li>Sangudo Public Library</li>
			<li>Seba Beach Public Library</li>
			<li>Spruce Grove Public Library</li>
			<li>Stony Plain Public Library</li>
			<li>Swan Hills Municipal Library</li>
			<li>Thorsby Municipal Library</li>
			<li>Tomahawk Public Library</li>
			<li>Wabamun Public Library</li>
			<li>Warburg Public Library</li>
			<li>Westlock Municipal Library</li>
			<li>Wetaskiwin Public Library</li>
			<li>Whitecourt and District Public Library</li>
			<li>Wildwood Public Library</li>
			<li>Winfield Community Library</li>
		</ul><!--columns-->
	</ul>
	<li style="clear:left;">Wood Buffalo Regional Library</li>
</ul>
<div style="clear:left;"></div>



<a href="http://melibraries.ca/">Return to MELibraries.ca</a>


</div><!--subContent-->
<div id="spacer"></div>
</div><!--mainContent-->
<?php
include 'footer.php';
?>