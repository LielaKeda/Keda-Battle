<?php
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
include('includes/init_sql.php');
include "functions.php";

if(isset($_GET['id']) && isset($_GET['skatita'])){
	$bildesID = $_GET['id'];
	$skatita = $_GET['skatita'];
	
	//bilde, par kuru balsots	
	$result = mysqli_query($connection, "update ratings set votes = IFNULL(votes, 0) + 1,  views = IFNULL(views, 0) + 1 where Id = $bildesID");
	
	//otra bilde
	$result = mysqli_query($connection, "update ratings set views = IFNULL(views, 0) + 1 where Id = $skatita");
	header('Location: ?');
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="lv" lang="lv">
<head>
    <title>Kedas cīņa</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <meta name="description" content="Salīdzini Kedas bildes!"/>
    <meta name="keywords" content="Keda, foto, attēli, salīdzinājums"/>
    <meta name="author" content="Matīss Rikters"/>
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="includes/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<style>
	/* Pen-specific styles */
	html, body, section {
	  height: 100%;
	}

	body {
	  text-align: center;
	}

	div {
	  display: flex;
	  flex-direction: column;
	  justify-content: center;
	}

	/* Pattern styles */
	.container {
	  display: flex;
	}

	.left-half {
	  flex: 1;
	  padding: 1rem;
	}

	.right-half {
	  flex: 1;
	  padding: 1rem;
	}
	img.a {
		max-height:85vh;
		max-width:46vw;
		overflow: hidden;
	}
	img.b {
		max-height:85vh;
		max-width:46vw;
		overflow: hidden;
	}
	</style>
</head>
<body>
	<div style='position: fixed; top: 0; left: 0; width: 50%; height: 400%; background-color: black; z-index: 1;'></div>
	<div style='position: absolute; top: 0; left: 0; z-index: 2; width: 100%;'>
	<br/>
	<h2 style="margin:auto auto;margin-top:5px;text-align:center;padding:5px;background-color:lightgrey;border-radius:15px;width:250px;opacity:0.7">Kura bilde labāka?</h2>
	<div class="gallery" style="margin:auto auto; width:100%;height:100%;">
	<section class="container">
	<?php
	$query = mysqli_query($connection, "select * from ratings ORDER BY RAND() LIMIT 2");
	while($rez = mysqli_fetch_array($query)){
		$img["Id"]      	= $rez["Id"];
		$img["img"]     	= $rez["img"];
		$img["votes"]		= $rez["votes"];
		$img["views"]		= $rez["views"];
		$img["album"]		= $rez["album"];
		$img["albumID"] 	= $rez["albumID"];
		$img["model"]		= $rez["model"];
		$img["iso"]			= $rez["iso"];
		$img["fstop"]		= $rez["fstop"];
		$img["exposure"]	= $rez["exposure"];
		$img["focallength"] = $rez["focallength"];
		$img["year"]		= $rez["year"];
		$img["month"]		= $rez["month"];
		$img["day"]			= $rez["day"];
		$images[] = $img;

		$imgId = $img["Id"];
		$queryX = mysqli_query($connection, "select distinct tag from tags where img_id = $imgId");
		$tag = [];
		while($rx = mysqli_fetch_array($queryX)){
			$tag[] = $rx["tag"];
		}
		if (sizeof($tag)<1) $tag[0] = "nav";
		$tags[] = $tag;
	}

	//izskaitļo bildes ID
	$bb = explode("/", $images[0]["img"]);
	$bb2 = explode("/", $images[1]["img"]);
	if(strcmp($bb[0],"http:")==0||strcmp($bb[0],"https:")==0){$bildesID = "";}else{$bildesID = "http://";}
	if(strcmp($bb2[0],"http:")==0||strcmp($bb2[0],"https:")==0){$bildesID2 = "";}else{$bildesID2 = "http://";}

	for ($i=0; $i<sizeof($bb)-1; $i++){$bildesID.=$bb[$i]."/";}
	for ($i=0; $i<sizeof($bb2)-1; $i++){$bildesID2.=$bb2[$i]."/";}

	//dabū lielās bildes
	$aa = explode("/", $bildesID);
	$aa2 = explode("/", $bildesID2);
	for ($i=0; $i<sizeof($aa)-1; $i++){$aaa1.=$aa[$i]."/";if($i==sizeof($aa)-2){$aaa1.="s2000/";};}
	for ($i=0; $i<sizeof($aa2)-1; $i++){$aaa2.=$aa2[$i]."/";if($i==sizeof($aa2)-2){$aaa2.="s2000/";};}

	//Pirmā bilde
	echo '<div class="left-half">';
	if(substr($images[1]["img"], 0, 4) == "http"){
		//Jāpārbauda arī otra (lai pareizi skaitītu...)
		if(substr($images[0]["img"], 0, 4) !== "http"){
			$bildesID = $images[0]["img"];
		}
		//TO-DO: Pārbaudīt, vai arī ar tastarūras pogām lietas strādā...
		echo "<a href='?id=".$images[1]["Id"]."&skatita=".$images[0]["Id"]."'><img class='a' src='".$aaa2."' ></a>";
	}else{    
		if(substr($images[0]["img"], 0, 4) !== "http"){
			$bildesID = $images[0]["img"];
		}
		echo "<a href='?id=".$images[1]["Id"]."&skatita=".$images[0]["Id"]."'><img class='a' src='".getImage($images[1]["img"],$accessToken)."'/></a>";
	}
	echo "<div style='color:white;padding:10px;margin:10px; display:block;'>";
	echo "Albums: <a style='color:white;font-weight:bold;' target='_blank' href='http://lielakeda.lv/albums/?cws_album=".$images[1]["albumID"]."&cws_album_title=".$images[1]["album"]."'>".$images[1]["album"]."</a><br/>";
	if($images[1]["views"]>0){echo "Reitings: ".$images[1]["votes"]/$images[1]["views"]."; ";}else{echo "Reitings: -; ";}
	echo "Balsis: ".$images[1]["votes"]."; ";
	if($images[1]["views"]>0){echo "Skatīta: ".$images[1]["views"]." reizes<br/>";}else{echo "Skatīta: -<br/>";}

	if($tags[1][0]!="nav")
		echo "Atslēgvārdi: ";
	foreach($tags[1] as $tag)
		if($tag != "nav")
			echo "<a style='text-decoration:none;color:white;font-weight:bold;' href='vards.php?v=".$tag."'>".$tag."</a> ";
		if($tags[1][0]!="nav")
			echo"<br/>";
	if(isset($images[1]["exposure"]) && isset($images[1]["exposure"]) && $images[1]["exposure"] != "" && $images[1]["exposure"] < 1 && $images[1]["exposure"] != 0 && $images[1]["exposure"] != 0) {
		$ashspd = "1/".(round(1 / $images[1]["exposure"]));
	} else {
		$ashspd = $images[1]["exposure"];
	}
		
	echo "Uzņemts ar: ".$images[1]["model"]."<br/>";
	echo "ISO: ".$images[1]["iso"]."; ";
	echo "Ekspozīcija: ".$ashspd."s<br/>";
	echo "Diafragmas atvērums: F".$images[1]["fstop"]."<br/>";
	echo "Fokusa attālums: ".$images[1]["focallength"]."mm<br/>";
	echo diena($images[1]["day"]).", ".$images[1]["year"].". gada ".menesis($images[1]["month"])."<br/>";
	echo "</div>";
	echo '</div>';


	//Otrā bilde
	echo '<div class="right-half">';
	if(substr($images[0]["img"], 0, 4) == "http"){
		//Jāpārbauda arī otra (lai pareizi skaitītu...)
		if(substr($images[1]["img"], 0, 4) !== "http"){
			$bildesID2 = $images[1]["img"];
		}
		echo "<a href='?id=".$images[0]["Id"]."&skatita=".$images[1]["Id"]."'><img class='b' src='".$aaa1."' ></a><br style='clear:both;'/>";
	}else{
		if(substr($images[1]["img"], 0, 4) !== "http"){
			$bildesID2 = $images[1]["img"];
		}
		echo "<a href='?id=".$images[0]["Id"]."&skatita=".$images[1]["Id"]."'><img class='b' src='".getImage($images[0]["img"],$accessToken)."'/></a><br style='clear:both;'/>";
	}

	echo "<div style='color:black;padding:10px;margin:10px; display:block;'>";
	echo "Albums: <a style='color:black;font-weight:bold;' target='_blank' href='http://lielakeda.lv/albums/?cws_album=".$images[0]["albumID"]."&cws_album_title=".$images[0]["album"]."'>".$images[0]["album"]."</a><br/>";
	if($images[0]["views"]>0){echo "Reitings: ".$images[0]["votes"]/$images[0]["views"]."; ";}else{echo "Reitings: -; ";}
	echo "Balsis: ".$images[0]["votes"]."; ";
	if($images[0]["views"]>0){echo "Skatīta: ".$images[0]["views"]." reizes<br/>";}else{echo "Skatīta: -<br/>";}

	if($tags[0][0]!="nav")
		echo "Atslēgvārdi: ";
	foreach($tags[0] as $tag)
		if($tag != "nav")
			echo "<a style='text-decoration:none;color:black;font-weight:bold;' href='vards.php?v=".$tag."'>".$tag."</a> ";
		if($tags[0][0] != "nav")
			echo"<br/>";
	if($images[0]["exposure"]<1&&$images[0]["exposure"]!=0&&$images[0]["exposure"]!=""&&isset($images[0]["exposure"])) {$shspd="1/".(round(1/$images[0]["exposure"]));} else {$shspd=$images[0]["exposure"];}
		
	echo "Uzņemts ar: ".$images[0]["model"]."<br/>";
	echo "ISO: ".$images[0]["iso"]."; ";
	echo "Ekspozīcija: ".$shspd."s<br/>";
	echo "Diafragmas atvērums: F".$images[0]["fstop"]."<br/>";
	echo "Fokusa attālums: ".$images[0]["focallength"]."mm<br/>";
	echo diena($images[0]["day"]).", ".$images[0]["year"].". gada ".menesis($images[0]["month"])."<br/>";
	echo "</div>";

	echo '</div>';
	?>
	</section>
	</div>
	<script language = "JavaScript">

	document.addEventListener("keydown", keyDownTextField, false);

	function keyDownTextField(e) {
	  var keyCode = e.keyCode;
	  switch(keyCode){
			case 37:
				window.location.href = "index.php?id=<?php echo $images[1]['Id'];?>&skatita=<?php echo $images[0]['Id'];?>";
				break;
			case 39:
				window.location.href = "index.php?id=<?php echo $images[0]['Id'];?>&skatita=<?php echo $images[1]['Id'];?>";
				break;
		} 
	}
	</script>
	<br style="clear:both;"/>
	<div style="position: fixed; bottom: 0px;  margin: auto auto; width:100%;background-color:lightgrey;text-align:center; opacity:0.85; display:block;">
	<a style="color:black; font-weight:bold; text-decoration:none;" href="index.php">Sākums</a> | 
		<a style="color:black; font-weight:bold; text-decoration:none;" href="topp.php">TOP bildes</a> | 
		<a style="color:black; font-weight:bold; text-decoration:none;" href="topa.php">TOP albumi</a> | 
		<a style="color:black; font-weight:bold; text-decoration:none;" href="stat.php">Statistika</a> | 
		<a style="color:black; font-weight:bold; text-decoration:none;" href="karte.php">Karte</a> | 
		<a style="color:black; font-weight:bold; text-decoration:none;" href="tags.php">Atslēgvārdi</a>
	</div>
</body>
</html>

<?
function diena($d){
	switch ($d) {
		case 1: return "Pirmdiena"; break;
		case 2: return "Otrdiena"; break;
		case 3: return "Trešdiena"; break;
		case 4: return "Ceturtdiena"; break;
		case 5: return "Piektdiena"; break;
		case 6: return "Sestdiena"; break;
		case 7: return "Svētdiena"; break;
	}
}
function menesis($m){
	switch ($m) {
		case 1: return "janvāris"; break;
		case 2: return "februāris"; break;
		case 3: return "marts"; break;
		case 4: return "aprīlis"; break;
		case 5: return "maijs"; break;
		case 6: return "jūnijs"; break;
		case 7: return "jūlijs"; break;
		case 8: return "augusts"; break;
		case 9: return "septembris"; break;
		case 10: return "oktobris"; break;
		case 11: return "novembris"; break;
		case 12: return "decembris"; break;
	}
}