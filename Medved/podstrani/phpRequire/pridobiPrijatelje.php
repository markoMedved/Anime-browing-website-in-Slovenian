<?php 
$tabelaPrijatleljev = [];
$tabelaPredlogov = [];
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
  
  //pridobi vse vrstice kjer je uporabnisko ime tisto od trenutnega uporabnika
  $poizvedba = "SELECT UpImePosiljatelja, Predlog FROM Predlogi WHERE upImePrejemnika='$uporabnisko_ime'"; 
  $rezultat = mysqli_query($povezava, $poizvedba);

  
  //pojdi čez vse vrstice kjer je trenutni uporabnik in daj vse prijatlje v tabelo
  while($vrstica = mysqli_fetch_array($rezultat)){
    array_push( $tabelaPrijatleljev, $vrstica["UpImePosiljatelja"]); 
    array_push( $tabelaPredlogov, $vrstica["Predlog"]); 
  
  };
  
  
  mysqli_close($povezava);
}


?>