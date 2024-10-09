
<?php 
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}



$servername = "localhost";
$usernameBaza = "root";
$passwordBaza = "";
$baza = "Uporabniki";

$id = $_REQUEST["anime_api_ID"];
$uporabnisko_ime =  $_SESSION['username'];


$povezava = new mysqli($servername, $usernameBaza, $passwordBaza, $baza)or die("Neuspešna povezava: " . mysqli_error($povezava));
mysqli_set_charset($povezava,"utf8");



//pridobi ID kjer je uporabnik pritisnil
$pridobiAnimeID = "SELECT ID FROM Anime WHERE anime_api_ID='$id'";
$rezultat1 = mysqli_query($povezava, $pridobiAnimeID);
$vrstica = mysqli_fetch_array($rezultat1);
$animeID = $vrstica["ID"];


//odstrani samo iz relacijske tabele da sedaj uporabnik ne bo imel tega anime-ja zabeleženega
$odstraniAnimeIzRelacijskeTabel = "DELETE FROM Podatki_Anime WHERE uporabniško_ime_Podatki='$uporabnisko_ime' AND ID_Anime = '$animeID'";
mysqli_query($povezava, $odstraniAnimeIzRelacijskeTabel);


mysqli_close($povezava);
?>
