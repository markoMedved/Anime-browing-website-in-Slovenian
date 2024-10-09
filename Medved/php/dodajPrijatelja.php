<?php 
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}


$servername = "localhost";
$usernameBaza = "root";
$passwordBaza = "";
$baza = "Uporabniki";


$uporabnisko_ime =  $_SESSION['username'];
$prijatelj = $_REQUEST['username'];

$povezava = new mysqli($servername, $usernameBaza, $passwordBaza, $baza)or die("Neuspešna povezava: " . mysqli_error($povezava));
mysqli_set_charset($povezava,"utf8");

//poizvedba v tabelo vseh uporabnikov če to uporabniško ime obstaja
$poizvedba = "SELECT uporabnisko_ime FROM Podatki WHERE uporabnisko_ime='$prijatelj'";
$rezultat = mysqli_query($povezava, $poizvedba );
//če je 0 vrstic s to poizvedbo potem ne obstaja
if(mysqli_num_rows($rezultat) == 0){
  echo "Ta uporabnik ne obstaja";
}
else {
  //preveri če je ta prijatelj že dodan(torej če obstaja katera koli kombinacija teh dveh up imen v tabeli)
  $zeDodan = "SELECT upImePosiljatelja FROM Predlogi WHERE upImePosiljatelja='$prijatelj' AND  upImePrejemnika='$uporabnisko_ime'";
  $rezultat2 = mysqli_query($povezava, $zeDodan);
  if (mysqli_num_rows($rezultat2) > 0){
    echo "Prijatelj je že dodan";
  }
  else{
    //če še ni dodan napiši obe kombinaciji v tabelo in postavi predloge na ""
    $dodajVnos1 = "INSERT INTO Predlogi (upImePosiljatelja, upImePrejemnika, Predlog) VALUES ('$prijatelj','$uporabnisko_ime', '')";
    $dodajVnos2 = "INSERT INTO Predlogi (upImePosiljatelja, upImePrejemnika, Predlog) VALUES ('$uporabnisko_ime','$prijatelj', '')";
    mysqli_query($povezava, $dodajVnos1);
    mysqli_query($povezava, $dodajVnos2);
    echo True;
  }

}


mysqli_close($povezava);
?>