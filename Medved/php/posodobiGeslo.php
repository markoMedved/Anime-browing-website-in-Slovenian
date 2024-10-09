<?php 
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$servername = "localhost";
$usernameBaza = "root";
$passwordBaza = "";
$baza = "Uporabniki";

//povezava
$povezava = new mysqli($servername, $usernameBaza, $passwordBaza, $baza)or die("Neuspešna povezava: " . mysqli_error($povezava));
mysqli_set_charset($povezava,"utf8");


$username = $_SESSION["username"];
$staroGeslo = $_REQUEST["staroGeslo"];
$novoGeslo = $_REQUEST["novoGeslo"];
$novoGeslo = password_hash($novoGeslo, PASSWORD_DEFAULT);


//pridobi geslo iz tabele
$poizvedba = mysqli_prepare($povezava,"SELECT geslo FROM Podatki WHERE uporabnisko_ime=?");
mysqli_stmt_bind_param($poizvedba, "s", $username);
mysqli_stmt_execute($poizvedba);
$rezultat = mysqli_stmt_get_result($poizvedba);
$vrstica = mysqli_fetch_array($rezultat);

//če ni pravilno vpisal staro geslo
if(!password_verify($staroGeslo,$vrstica["geslo"])){
  echo false;
}
//drugače posodobi geslo
else{
  $posodobiGeslo = mysqli_prepare($povezava,"UPDATE Podatki SET geslo=? WHERE uporabnisko_ime=?");
  mysqli_stmt_bind_param($posodobiGeslo, "ss",$novoGeslo, $username);
  mysqli_stmt_execute($posodobiGeslo);
  echo true;
}

mysqli_close($povezava);
?> 

