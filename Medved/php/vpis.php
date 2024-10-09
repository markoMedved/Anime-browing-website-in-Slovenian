 <?php 
 //začetek seje
 if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

//podatki za dostop do baze
$servername = "localhost";
$usernameBaza = "root";
$passwordBaza = "";
$baza = "Uporabniki";

//povezava z bazo
$povezava = new mysqli($servername, $usernameBaza, $passwordBaza, $baza) or die("Neuspešna povezava: " . mysqli_error($povezava));
mysqli_set_charset($povezava,"utf8");

//pridobi username in pasword iz ajax requesta
$username = $_REQUEST["username"];
$password = $_REQUEST["password"];

//sql stavek za pridobitev iz tabele podatki, prepare uporabljeno za sql inject preventivo
$poizvedba = mysqli_prepare($povezava, "SELECT geslo FROM Podatki WHERE uporabnisko_ime=?");
mysqli_stmt_bind_param($poizvedba, "s", $username);


//izvedi poizvedbo in pridobi rezultat poizvedbe
mysqli_stmt_execute($poizvedba);
$rezultat = mysqli_stmt_get_result($poizvedba);

//če je kakšna vrstica v bazi s takim uporabniškim imenom
if (mysqli_num_rows($rezultat) > 0) { 
//pridobi vrstico 
 $vrstica = mysqli_fetch_array($rezultat);
    //če je geslo pravilno
     if(password_verify($password,$vrstica["geslo"])){
      //nastavi sejno spremenljivko vpisan na true
      $_SESSION['vpisan'] = True;
      //nastavi sejno spremenljivko na uporabniško ime, saj je bil vpis uspešen
      $_SESSION['username'] = $username;
      //vrni true če je bil vpis uspešen
      echo True;
     }
     //vrni false če je bilo geslo napačno
     else echo False;
  
} 
//če je bil 0 vrstic s takim imenom to ne obstaja
else {
  echo "Uporabniško ime ne obstaja";
}


mysqli_close($povezava);
?> 

