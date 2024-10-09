<?php 
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}


$servername = "localhost";
$usernameBaza = "root";
$passwordBaza = "";
$baza = "Uporabniki";


$uporabnisko_ime =  $_SESSION['username'];
$predlog = $_REQUEST["predlog"];
$prijatelj = $_REQUEST["prijatelj"];

$povezava = new mysqli($servername, $usernameBaza, $passwordBaza, $baza)or die("Neuspešna povezava: " . mysqli_error($povezava));
mysqli_set_charset($povezava,"utf8");

//napisi v bazo predlog posiljatelja prejemniku 
$predlagaj = "UPDATE Predlogi SET Predlog='$predlog' WHERE upImePosiljatelja='$uporabnisko_ime' AND upImePrejemnika='$prijatelj'";
mysqli_query($povezava, $predlagaj);

mysqli_close($povezava);
?>