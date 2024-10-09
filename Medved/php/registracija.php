<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

//podatki za dostop do baze
$servername = "localhost";
$usernameBaza = "root";
$passwordBaza = "";
$baza = "Uporabniki";


$povezava = new mysqli($servername, $usernameBaza, $passwordBaza, $baza)or die("Neuspešna povezava: " . mysqli_error($povezava));
mysqli_set_charset($povezava,"utf8");


$username = $_REQUEST["username"];
$password = $_REQUEST["password"];
//hashanje gesla
$password = password_hash($password, PASSWORD_DEFAULT);


//preverjanje če obstaja že željeno uporabniško ime
$sqlPoizvedba = mysqli_prepare($povezava,"SELECT uporabnisko_ime FROM Podatki WHERE uporabnisko_ime=?");
mysqli_stmt_bind_param($sqlPoizvedba , "s", $username);
//izvedi in pridobi rezultat
mysqli_stmt_execute($sqlPoizvedba);
$result = mysqli_stmt_get_result($sqlPoizvedba);
//če je število vrstic več kot nič že obstaja
if (mysqli_num_rows($result) > 0){
  echo "Obstaja";
}

//če ne vpiši novega uporabnika
else{
  //stavek za vstavljanje novega uporabnika v bazo
  $sqlVpisiUporabnika= mysqli_prepare($povezava,"INSERT INTO Podatki (uporabnisko_ime, geslo) VALUES (?,?)");
  mysqli_stmt_bind_param($sqlVpisiUporabnika, "ss", $username, $password);
  mysqli_stmt_execute($sqlVpisiUporabnika);
 

    $_SESSION['vpisan'] = True;
    $_SESSION['username'] = $username;
    echo True;

}

mysqli_close($povezava);

?>