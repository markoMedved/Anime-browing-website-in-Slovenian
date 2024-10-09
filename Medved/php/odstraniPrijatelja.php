<?php 
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


$prijatelj = $_REQUEST["prijatelj"];
//odstrani oba vnosa kjer sta povezana prejemnik in pošiljatelj
$odstraniVnos1 = "DELETE FROM Predlogi  WHERE upImePrejemnika='$prijatelj' AND  upImePosiljatelja='$uporabnisko_ime'";
$odstraniVnos2 = "DELETE FROM Predlogi  WHERE upImePrejemnika='$uporabnisko_ime' AND  upImePosiljatelja='$prijatelj'";
mysqli_query($povezava, $odstraniVnos1);
mysqli_query($povezava, $odstraniVnos2);

mysqli_close($povezava);

?>