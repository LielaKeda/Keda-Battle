<?php
include('includes/init_sql.php');

if(isset($_GET['id'])&&isset($_GET['skatita'])){
	$bildesID = $_GET['id'];
	$skatita = $_GET['skatita'];
	//dabū bildi, par kuru balsots
	$balsiojumi1 = mysql_query("SELECT * FROM ratings where img LIKE '%$bildesID%'");
	$r1=mysql_fetch_array($balsiojumi1);
	//palielina bildes balsis un skatījumus
	$b_balsis=$r1["votes"];
	$b_skatijumi=$r1["views"];
		$b_balsis++;
		$b_skatijumi++;
		$result = MYSQL_QUERY("update ratings set votes = '$b_balsis',  views = '$b_skatijumi' where img LIKE '%$bildesID%'");
	//dabū otru bildi
	$balsiojumi1 = mysql_query("SELECT * FROM ratings where img LIKE '%$skatita%'");
	$r1=mysql_fetch_array($balsiojumi1);
	//palielina bildes skatījumus
	$s_skatijumi=$r1["views"];
		$s_skatijumi++;
		$result = MYSQL_QUERY("update ratings set views = '$s_skatijumi' where img LIKE '%$skatita%'");
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
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<link rel="stylesheet" type="text/css" href="includes/style.css">
</head>
<body style='margin: 0px; height: 100%;'>
<div style='position: fixed; top: 0; left: 0; width: 50%; height: 400%; background-color: black; z-index: 1;'></div>
<div style='position: absolute; top: 0; left: 0; z-index: 2; width: 100%;'>
<br/>
<h2 style="margin:auto auto;text-align:center;padding:5px;background-color:lightgrey;border-radius:15px;width:250px;opacity:0.7">Kura bilde labāka?</h2>
<div class="gallery" style="margin:auto auto; width:100%;height:100%;">
<?php
$query = mysql_query("select * from ratings ORDER BY RAND() LIMIT 2");
$rrr=mysql_fetch_array($query);
	$random_pic=$rrr["img"];
	$rate1=$rrr["votes"];
	$skatijumi=$rrr["views"];
	$album_title=$rrr["album"];
	$model = $rrr['model'];
	$iso = $rrr['iso'];
	$fstop = $rrr['fstop'];
	$exposure = $rrr['exposure'];
	$focallength = $rrr['focallength'];
	$year = $rrr['year'];
	$month = $rrr['month'];
	$weekday = $rrr['day'];
	$queryX = mysql_query("select distinct tag from tags where img like '%$random_pic%'");
	while($rx=mysql_fetch_array($queryX)){
		$tags[]=$rx["tag"];
	}
	if (sizeof($tags)<1)$tags[0]="nav";
$rrr=mysql_fetch_array($query);
	$arandom_pic=$rrr["img"];
	$rate2=$rrr["votes"];
	$skatijumi2=$rrr["views"];
	$aalbum_title=$rrr["album"];
	$amodel = $rrr['model'];
	$aiso = $rrr['iso'];
	$afstop = $rrr['fstop'];
	$aexposure = $rrr['exposure'];
	$afocallength = $rrr['focallength'];
	$ayear = $rrr['year'];
	$amonth = $rrr['month'];
	$aweekday = $rrr['day'];
	$queryXX = mysql_query("select distinct tag from tags where img like '%$arandom_pic%'");
	while($rxx=mysql_fetch_array($queryXX)){
		$tags1[]=$rxx["tag"];
	}
	if (sizeof($tags1)<1)$tags1[0]="nav";

//izskaitļo bildes ID
$bb = explode("/", $random_pic);
$bb2 = explode("/", $arandom_pic);
if(strcmp($bb[0],"http:")==0||strcmp($bb[0],"https:")==0){$bildesID = "";}else{$bildesID = "http://";}
if(strcmp($bb2[0],"http:")==0||strcmp($bb2[0],"https:")==0){$bildesID2 = "";}else{$bildesID2 = "http://";}

for ($i=0; $i<sizeof($bb)-1; $i++){$bildesID.=$bb[$i]."/";}
for ($i=0; $i<sizeof($bb2)-1; $i++){$bildesID2.=$bb2[$i]."/";}

//dabū lielās bildes
$aa = explode("/", $bildesID);
$aa2 = explode("/", $bildesID2);
for ($i=0; $i<sizeof($aa)-1; $i++){$aaa1.=$aa[$i]."/";if($i==sizeof($aa)-2){$aaa1.="s2000/";};}
for ($i=0; $i<sizeof($aa2)-1; $i++){$aaa2.=$aa2[$i]."/";if($i==sizeof($aa2)-2){$aaa2.="s2000/";};}

echo "<a href='?id=".$bildesID."&skatita=".$bildesID2."'><img style='max-height:85%;max-width:46%;overflow: hidden;float:left;' src='".$aaa1."' ></a>";
echo "<a href='?id=".$bildesID2."&skatita=".$bildesID."'><img style='max-height:85%;max-width:46%;overflow: hidden;float:right;' src='".$aaa2."' ></a><br style='clear:both;'/>";

	echo "<div style='float:left;width:40%;padding:15px;color:white;border:1px lightgrey solid;border-radius:15px;margin:15px;'>";
	echo 'Saite uz bildi:<br/>';
	echo '<input type="text" size="60" value="'.$aaa1.'"  readonly="readonly" /><br/><br/>';
	echo "<b>Par bildi:</b><br/>";
	if($skatijumi>0){echo "Reitings: ".$rate1/$skatijumi."<br/>";}else{echo "Reitings: vēl nav<br/>";}
	echo "Balsis: ".$rate1."<br/>";
	if($skatijumi>0){echo "Skatīta: ".$skatijumi." reizes<br/>";}else{echo "Skatīta: nav<br/>";}
	if($tags[0]!="nav")echo "Atslēgvārdi: ";foreach($tags as $tag)if($tag!="nav")echo "<a style='text-decoration:none;color:white;font-weight:bold;' href='vards.php?v=".$tag."'>".$tag."</a>";if($tags[0]!="nav")echo"<br/>";
	echo "Albums: <a style='color:white;font-weight:bold;' target='_blank' href='http://lielakeda.lv/?album=".$album_title."'>".$album_title."</a><br/><br/>";
	echo "Uzņemts ar: ".$model."<br/>";
	echo "ISO: ".$iso."<br/>";
	echo "Diafragmas atvērums: F".$fstop."<br/>";
	if($exposure<1&&$exposure!=0&&$exposure!=""&&isset($exposure)) {$shspd="1/".(round(1/$exposure));} else {$shspd=$exposure;}
	echo "Ekspozīcija: ".$shspd."s<br/>";
	echo "fokusa attālums: ".$focallength."mm<br/>";
	echo "Gads: ".$year."<br/>";
	switch ($month) {
		case 1: $menesis = "janvāris"; break;
		case 2: $menesis = "februāris"; break;
		case 3: $menesis = "marts"; break;
		case 4: $menesis = "aprīlis"; break;
		case 5: $menesis = "maijs"; break;
		case 6: $menesis = "jūnijs"; break;
		case 7: $menesis = "jūlijs"; break;
		case 8: $menesis = "augusts"; break;
		case 9: $menesis = "septembris"; break;
		case 10: $menesis = "oktobris"; break;
		case 11: $menesis = "novembris"; break;
		case 12: $menesis = "decembris"; break;
	}
	echo "Mēnesis: ".$menesis."<br/>";
	switch ($weekday) {
		case 1: $diena = "pirmdiena"; break;
		case 2: $diena = "otrdiena"; break;
		case 3: $diena = "trešdiena"; break;
		case 4: $diena = "ceturtdiena"; break;
		case 5: $diena = "piektdiena"; break;
		case 6: $diena = "sestdiena"; break;
		case 7: $diena = "svētdiena"; break;
	}
	echo "Diena: ".$diena."<br/>";
	echo "</div>";

	echo "<div style='float:right;width:40%;padding:15px;border:1px lightgrey solid;border-radius:15px;margin:15px;'>";
	echo 'Saite uz bildi:<br/>';
	echo '<input type="text" size="60" value="'.$aaa2.'"  readonly="readonly" /><br/><br/>';
	echo "<b>Par bildi:</b><br/>";
	if($skatijumi2>0){echo "Reitings: ".$rate2/$skatijumi2."<br/>";}else{echo "Reitings: vēl nav<br/>";}
	echo "Balsis: ".$rate2."<br/>";
	if($skatijumi2>0){echo "Skatīta: ".$skatijumi2." reizes<br/>";}else{echo "Skatīta: nav<br/>";}
	if($tags1[0]!="nav")echo "Atslēgvārdi: ";foreach($tags1 as $tag)if($tag!="nav")echo "<a style='text-decoration:none;color:black;font-weight:bold;' href='vards.php?v=".$tag."'>".$tag."</a>";if($tags1[0]!="nav")echo"<br/>";
	echo "Albums: <a style='color:black;font-weight:bold;' target='_blank' href='http://lielakeda.lv/?album=".$aalbum_title."'>".$aalbum_title."</a><br/><br/>";
	echo "Uzņemts ar: ".$amodel."<br/>";
	echo "ISO: ".$aiso."<br/>";
	echo "Diafragmas atvērums: F".$afstop."<br/>";
	if($aexposure<1&&$exposure!=0&&$exposure!=""&&isset($exposure)) {$ashspd="1/".(round(1/$aexposure));} else {$ashspd=$aexposure;}
	echo "Ekspozīcija: ".$ashspd."s<br/>";
	echo "fokusa attālums: ".$afocallength."mm<br/>";
	echo "Gads: ".$ayear."<br/>";
	switch ($amonth) {
		case 1: $menesis = "janvāris"; break;
		case 2: $menesis = "februāris"; break;
		case 3: $menesis = "marts"; break;
		case 4: $menesis = "aprīlis"; break;
		case 5: $menesis = "maijs"; break;
		case 6: $menesis = "jūnijs"; break;
		case 7: $menesis = "jūlijs"; break;
		case 8: $menesis = "augusts"; break;
		case 9: $menesis = "septembris"; break;
		case 10: $menesis = "oktobris"; break;
		case 11: $menesis = "novembris"; break;
		case 12: $menesis = "decembris"; break;
	}
	echo "Mēnesis: ".$menesis."<br/>";
	switch ($aweekday) {
		case 1: $diena = "pirmdiena"; break;
		case 2: $diena = "otrdiena"; break;
		case 3: $diena = "trešdiena"; break;
		case 4: $diena = "ceturtdiena"; break;
		case 5: $diena = "piektdiena"; break;
		case 6: $diena = "sestdiena"; break;
		case 7: $diena = "svētdiena"; break;
	}
	echo "Diena: ".$diena."<br/>";
	echo "</div>";
?>
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
</body>
</html>