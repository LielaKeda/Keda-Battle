<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="lv" lang="lv">
<head>
<title>Kedas cīņa - TOP albumi</title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<meta name="description" content="Salīdzini Kedas bildes!"/>
<meta name="keywords" content="Keda, foto, attēli, salīdzinājums"/>
<meta name="author" content="Matīss Rikters"/>
<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon" />
<link rel="stylesheet" type="text/css" href="includes/style.css">
</head>
<body style='margin: 0px; height: 100%;'>
<div style='position: fixed; top: 0; left: 0; width: 50%; height: 813%; background-color: black; z-index: 1;'></div>
<div style='position: absolute; top: 0; left: 0; z-index: 2; width: 100%;'>
<br/>
<h2 style="margin:auto auto;text-align:center;padding:5px;background-color:lightgrey;border-radius:15px;width:250px;opacity:0.7">Albumi</h2>
<h2 style="float:left;text-align:center;padding:5px;margin-left:12%;background-color:lightgrey;border-radius:15px;width:25%;opacity:0.7">Augstākais reitings <small>/ bilžu skaits</small></h2>
<h2 style="float:right;text-align:center;padding:5px;margin-right:12%;background-color:lightgrey;border-radius:15px;width:25%;opacity:0.7">Augstākais reitings</h2>
<br style="clear:both;"/>
<div class="gallery">
<div style="width:49%;float:left;">
<?php
include('includes/init_sql.php');
// visvairāk balsu
// $balsiojumi1 = mysqli_query($connection, "SELECT distinct album, (sum(votes)/sum(views)) skaits, sum(votes) bals, img FROM `ratings` where votes>0 and views>0 group by album ORDER BY bals desc limit 0, 10");
// augstākaie, ņemot vērā bilžu skaitu albumā
$balsiojumi1 = mysqli_query($connection, "SELECT distinct album, albumID, (sum(votes)/sum(views)) skaits, sum(votes) bals, img, ((sum(votes)/sum(views))/count(img)) as izlidzinats FROM `ratings` where votes>0 and views>0 group by album ORDER BY izlidzinats desc, bals desc limit 0, 10");

while($r1=mysqli_fetch_array($balsiojumi1)){
	$url=$r1["img"];
	$balsis=$r1["skaits"];
	$albumID=$r1["album"];
	$album_ID=$r1["albumID"];
	$votes=$r1["bals"];
	echo '<div style="display:bolck;">
	<a target="_blank" href="http://lielakeda.lv/albums/?cws_album='.$album_ID.'&cws_album_title='.$albumID.'">
	<img style="float:left;width:95%;border-radius:25px;" src="'.preg_replace("~\/(?!.*\/)~", "/s2048/", $url).'"/></a><br/>';
	echo '<span style="padding:15px;color:white;">Albums - <b>'.$albumID.'</b>, reitings: '.round($balsis, 2).' ('.$votes.' balsis)</span></div>';
}

?>
</div><div style="width:49%;float:right;">
<?php
//augstākie

$balsiojumi1 = mysqli_query($connection, "SELECT distinct album, albumID, (sum(votes)/sum(views)) skaits, sum(votes) bals, img FROM `ratings` where votes>0 and views>0 group by album ORDER BY skaits desc, bals desc limit 0, 10");
while($r1=mysqli_fetch_array($balsiojumi1)){
	$url=$r1["img"];
	$balsis=$r1["skaits"];
	$albumID=$r1["album"];
	$album_ID=$r1["albumID"];
	$votes=$r1["bals"];
	echo '<div style="display:bolck;">
	<a target="_blank" href="http://lielakeda.lv/albums/?cws_album='.$album_ID.'&cws_album_title='.$albumID.'">
	<img style="float:right;width:95%;border-radius:25px;" src="'.preg_replace("~\/(?!.*\/)~", "/s2048/", $url).'"/></a><br/>';
	echo '<span style="padding:15px;color:black;">Albums - <b>'.$albumID.'</b>, reitings: '.round($balsis, 2).' ('.$votes.' balsis)</span></div>';
}
?>
</div>
</div>
<br style="clear:both;"/>
<div style="position: fixed; bottom: 0px;  margin: auto auto; width:100%;background-color:lightgrey;text-align:center; opacity:0.85;">
	<a style="color:black; font-weight:bold; text-decoration:none;" href="index.php">Sākums</a> | 
	<a style="color:black; font-weight:bold; text-decoration:none;" href="topp.php">TOP bildes</a> | 
	<a style="color:black; font-weight:bold; text-decoration:none;" href="topa.php">TOP albumi</a> | 
	<a style="color:black; font-weight:bold; text-decoration:none;" href="stat.php">Statistika</a> | 
	<a style="color:black; font-weight:bold; text-decoration:none;" href="karte.php">Karte</a> | 
	<a style="color:black; font-weight:bold; text-decoration:none;" href="tags.php">Atslēgvārdi</a>
</div>
</div>
</body>
</html>