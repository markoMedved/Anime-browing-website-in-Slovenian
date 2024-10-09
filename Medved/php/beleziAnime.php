
<?php 
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

//pridobi json iz http POST metode
$animeJson = file_get_contents('php://input');
//treba je escapat ' ker drugače SQL ne dela
$animeJson = str_replace("'", "''",$animeJson);


$servername = "localhost";
$usernameBaza = "root";
$passwordBaza = "";
$baza = "Uporabniki";

$id = $_REQUEST["anime_api_ID"];
$uporabnisko_ime =  $_SESSION['username'];

$povezava = new mysqli($servername, $usernameBaza, $passwordBaza, $baza)or die("Neuspešna povezava: " . mysqli_error($povezava));
mysqli_set_charset($povezava,"utf8");

$pridobiAnime = "SELECT anime_json FROM Anime WHERE anime_api_id=" . $id;
$pridobiAnimeID = "SELECT ID FROM Anime WHERE anime_api_id=" . $id;

$rezultat2=mysqli_query($povezava,$pridobiAnime);
$rezultat4=mysqli_query($povezava,$pridobiAnimeID);
//če je anime že beležen v bazi ga ne beleži ponovno
if (mysqli_num_rows($rezultat2) > 0){
  
  //iz api id-ja pridobi ID 
  $vrstica = mysqli_fetch_array($rezultat4);
  //spravi ID v spremenljivko za relacijsko tabelo
   $ID_Anime = $vrstica["ID"];
    
  
}
//če je že v bazi ga beleži v tabelo anime
else{
  $vstaviAnime = "INSERT INTO Anime (anime_json, anime_api_ID) VALUES ('$animeJson', '$id')";
  $rezultat = mysqli_query($povezava,$vstaviAnime);
  $rezultat5=mysqli_query($povezava,$pridobiAnimeID);
  $vrstica = mysqli_fetch_array($rezultat5);
  //pridbi ID za relacijsko
  $ID_Anime = $vrstica["ID"];
  
}

//za preverjanje če je že v relacijski tabeli (bolj preventiva, saj načeloma ni možno)
$bool = true;
$preveriRelacijskoTabelo = "SELECT ID_Anime FROM Podatki_Anime WHERE uporabniško_ime_Podatki='$uporabnisko_ime'";
$rezultat6 = mysqli_query($povezava,$preveriRelacijskoTabelo);

//če že obstaja vpis tega animeja ga ne beleži
if (mysqli_num_rows($rezultat6) > 0){
  while($vrstica = mysqli_fetch_array($rezultat6)){
      if($vrstica["ID_Anime"] == $ID_Anime){
        $bool = false;
      }
  }
}

if($bool){
  //v relacijsko tabelo vstavi uporabnika in ID animeja
  $posodobiRelacijskoTabelo = "INSERT INTO Podatki_Anime (uporabniško_ime_Podatki, ID_Anime)
  VALUES ('$uporabnisko_ime', '$ID_Anime')";
  $rezultat4 = mysqli_query($povezava, $posodobiRelacijskoTabelo);
}


mysqli_close($povezava);


?>
