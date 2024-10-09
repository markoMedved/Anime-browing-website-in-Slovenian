<?php
 $tabelaAnime1 = [];
if(isset($_SESSION['username'])){
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }


  $servername = "localhost";
  $usernameBaza = "root";
  $passwordBaza = "";
  $baza = "Uporabniki";


  $uporabnisko_ime =  $_SESSION['username'];


  $povezava = new mysqli($servername, $usernameBaza, $passwordBaza, $baza);

  //poglej ID_anime-jev iz relacijkse tabele ki jih ima uporabnik dodane
  $preveriAnimeRelacijska = "SELECT ID_Anime FROM Podatki_Anime WHERE uporabniško_ime_Podatki='$uporabnisko_ime'";
  $rezultat = mysqli_query($povezava, $preveriAnimeRelacijska);
  //tabela vseh pridobljenih animejev
  $tabelaAnime = [];

  //napiši v tabel vseh ID-jev animejev
  while($row = mysqli_fetch_array($rezultat)){
    array_push($tabelaAnime,$row["ID_Anime"]);
  };

  $pridobiMalID = "SELECT  anime_api_ID, ID FROM Anime";
  $rezultat = mysqli_query($povezava, $pridobiMalID);
  //pretvori iz ID-jev v anime_api_ID_je
  while($row = mysqli_fetch_array($rezultat)){
    if(in_array($row["ID"], $tabelaAnime)){
      array_push($tabelaAnime1 ,$row["anime_api_ID"]); 
    }

  }


  mysqli_close($povezava);
}
?>