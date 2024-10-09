<?php 
$stAnimejev = 0;
$tabela = [];
if(isset($_SESSION['username'])){
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }
  
  
  $servername = "localhost";
  $usernameBaza = "root";
  $passwordBaza = "";
  $baza = "Uporabniki";
  
  
  $uporabnisko_ime =  $_SESSION['username'];
  
  
  $povezava = new mysqli($servername, $usernameBaza, $passwordBaza, $baza)or die("Neuspešna povezava: " . mysqli_error($povezava));
  mysqli_set_charset($povezava,"utf8");
  
  //poglej ID_anime-jev iz relacijkse tabele ki jih ima uporabnik dodane
  $preveriAnimeRelacijska = "SELECT ID_Anime FROM Podatki_Anime WHERE uporabniško_ime_Podatki='$uporabnisko_ime'";
  $rezultat = mysqli_query($povezava, $preveriAnimeRelacijska);
  //tabela vseh pridobljenih animejev
  $tabelaAnime = [];
  
  //napiši v tabel vseh ID-jev animejev
  while($vrstica = mysqli_fetch_array($rezultat)){
    array_push($tabelaAnime,$vrstica["ID_Anime"]);
  };
  $stAnimejev = count($tabelaAnime);
  //nato pojdi čez celotno tabelo anime id-jev
  for($i = 0; $i < $stAnimejev; $i++){
    //pridobi json za vsak anime iz id-ja
    $pridobiJSON = "SELECT anime_json FROM Anime WHERE ID=" . $tabelaAnime[$i];
    $rezultat2 = mysqli_query($povezava, $pridobiJSON);
    //vrstica z temi jsoni
    $vrstica2 = mysqli_fetch_array($rezultat2);
    //pošlji json
    $tabela[$i] = $vrstica2["anime_json"];
   
  }
  
  mysqli_close($povezava);
}
?>