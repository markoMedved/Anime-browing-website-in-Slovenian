<!--VSTOPNA STRAN SPLETNE STRANI, TUKAJ BO PRIJAVA, MOŽNOST NEKATERIH FUNKCIJ BO TUDI BREZ PRIJAVE-->

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Vpis</title>
  <link rel="stylesheet" type="text/css" href="/Medved/css/1.css" />
  <link rel="stylesheet" type="text/css" href="css/index.css" />
  <script src="/Medved/javaScript/1.js"></script>
  <script src="javaScript/jquerry.js"></script>
</head>
<body class="telo">
  <!-- prva vrstica za prikaz ko je stran dovolj velika --> 
  <div id="vrsticaZgoraj" class="navigacijskaVrstica">
    <a href="/Medved/podstrani/brskaj.php">BRSKAJ</a>
    <a href="/Medved/podstrani/isci.php">IŠČI</a>
    <a href="/Medved/podstrani/priljubljeno.php">SEZNAM</a>
    <a href="/Medved/podstrani/predlogi.php">PREDLOGI</a>
    <a href="/Medved/podstrani/profil.php">PROFIL</a>
  </div>

  <!-- tretja vrstica za prikaz po overflowu --> 
  <div id="vrsticaZgoraj3" class="navigacijskaVrstica3">
    <!-- ob pritisku na gumb se sprožii dropdown--> 
    <a id="navigacijskaVrsticaGumb" class="gumbZaDropdown" onclick="uporabiDropdown()">DEJANJE  ▼</a>
    <div id="Dropdown" class="DropdownVsebina">
      <a class="trenutna" href="/Medved/podstrani/brskaj.php">BRSKAJ</a>
      <a href="/Medved/podstrani/isci.php">IŠČI</a>
      <a href="/Medved/podstrani/priljubljeno.php">SEZNAM</a>
      <a href="/Medved/podstrani/predlogi.php">PREDLOGI</a>
      <a href="/Medved/podstrani/profil.php">PROFIL</a>
    </div>
  </div>

  <!-- shema za vpis--> 
  <div class="vpis">
    <h1 class="naslovVpis">Vpis</h1>
    <div class="podatkiZaVpis">
      <form>
        <br>
        <p class="premikajočiTekstUsername" id="napisUsername">Uporabniško ime </p>
        <input class="vpisObmočje" type="text" id="username"><br><br>
        <br>
        <p class="premikajočiTekstGeslo" id="napisGeslo">Geslo</p>
        <p class="prikazigeslo" id="prikazGesla"></p>
        <input class="vpisObmočje" id="geslo" type="password">
        <p id="nezadovoljivoVnos" class="opozorilniTekst"></p>
        <p id="napacniPodatki" class="opozorilniTekst"></p>
      </form>
    </div>
    <button class="vpisGumb" id="gumbZaVpis">VPIS</button>
    <div class="registracijaDiv">
      <p id="nimasRacuna" class="nimasRacunaTekst">Nimaš računa?</p>
      <a class="registracijaTekst" href="podstrani/registracija.php">REGISTRACIJA</a>
    </div>
  </div>

  <?php require "podstrani/phpRequire/vpisan.php"; ?>

  <script>
    if (vpisan) {
      location.replace('/Medved/podstrani/brskaj.php')
    }

    var pozicijaGeslo = "120px";
    // števec za togglanje dropdowna
    var števec = 0;
    // na začetku že nastavi kateri stil zgornje vrstice je potreben
    nastaviStilZgornjeVrstice()
    // če se spremeni velikost okna popravi razred vrstice zgoraj
    window.addEventListener('resize', function() {
      nastaviStilZgornjeVrstice()
    });

    // naredilo bo animacijo tudi če klikneš na tekst, ker bo dal fokusiran input
    document.getElementById("napisGeslo").addEventListener("click", function() {
      document.getElementById("geslo").focus()
    })
    document.getElementById("napisUsername").addEventListener("click", function() {
      document.getElementById("username").focus()
    })

    // animacija za tekst
    $(document).ready(function() {
      // če je input že na začetku prestavi takoj gor tekst, za username in geslo
      if (document.getElementById("username").value != "") {
        $("#napisUsername").animate({ top: "20px" })
      }
      if (document.getElementById("geslo").value != "") {
        $("#napisGeslo").animate({ top: "90px" })
      }

      // ko je fokus na inputu prestavi napis gor in spremeni barvo napisa
      $("#username").focus(function() {
        $("#napisUsername").animate({ top: "20px" })
        $("#napisUsername").css({ color: "rgb(124, 210, 236)" })
        document.getElementById("username").style.borderBottom = "1px solid rgb(124, 210, 236)"
      });
      // ko greš ven iz fokusa in če je prazno postavi napis nazaj(za oba enaako)
      $("#username").focusout(function() {
        if (document.getElementById("username").value == "") {
          $("#napisUsername").animate({ top: "50px" })
        }
        // v vsakem primeru ponastavi barvo
        $("#napisUsername").css({ color: "white" })
        document.getElementById("username").style.borderBottom = "1px solid white"
      });
      $("#geslo").focus(function() {
        $("#napisGeslo").animate({ top: "90px" })
        $("#napisGeslo").css({ color: "rgb(124, 210, 236)" })
        document.getElementById("geslo").style.borderBottom = "1px solid rgb(124, 210, 236)"
      });
      $("#geslo").focusout(function() {
        if (document.getElementById("geslo").value == "") {
          $("#napisGeslo").animate({ top: pozicijaGeslo })
        }
        $("#napisGeslo").css({ color: "white" })
        document.getElementById("geslo").style.borderBottom = "1px solid white"
      });
    });

    // cnt zato da toggla prikaži/skrij
    var cnt = 0
    skrijGeslo()

    // če si v fokusu input fielda za username in pritisneš enter pojdi na input field za geslo
    document.getElementById("username").addEventListener("keypress", function(event) {
      if (event.key == "Enter") {
        document.getElementById("geslo").focus()
      }
    })

    // če si v fokusu input fielda za geslo in pritisneš enter simuliraj pritisk na gumb vpis
    document.getElementById("geslo").addEventListener("keypress", function(event) {
      if (event.key == "Enter") {
        document.getElementById("gumbZaVpis").click();
        document.getElementById("geslo").blur()
      }
    })

    // vpis uporabnika
    document.getElementById("gumbZaVpis").onclick = function() {
      // ob kliku ponastavi tekst za nezadovoljiv vnos
      document.getElementById("nezadovoljivoVnos").innerHTML = ""
      // če oba polja nista prazna pojdi naprej
      if (document.getElementById("username").value != "" && document.getElementById("geslo").value != "") {
        // ajax request za pošiljanje podatkov iz forma na server
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            // če dobiš nazaj true je bil vpis uspešen in preusmeri na brskaj
            console.log(this.response)
            if (this.responseText == true) {
              location.replace("/Medved/podstrani/brskaj.php")
            }
            // če je response false pomeni da je bilo napačno geslo(tako je narejen v php skripti), napiši opozorilni tekst
            else if (this.responseText == false) {
              document.getElementById("nezadovoljivoVnos").innerHTML = "Napačno geslo"
              document.getElementById("napisGeslo").style.color = "red"
              document.getElementById("geslo").style.borderBottom = "1px solid red"
            }
            // če ni nič od tega uporabniško ime ne obstaja (opozorilni tekst)
            else {
              document.getElementById("nezadovoljivoVnos").innerHTML = "Uporabniško ime ne obstaja"
              document.getElementById("napisUsername").style.color = "red"
              document.getElementById("username").style.borderBottom = "1px solid red"
            }
          }
        };
        // v request vpiši parametre iz polj input
        xhttp.open("GET", "/Medved/php/vpis.php?username=" + document.getElementById("username").value + "&password=" + document.getElementById("geslo").value, true);
        xhttp.send();
      }
      // drugače daj opozorilni tekst
      else {
        document.getElementById("nezadovoljivoVnos").innerHTML = "Vsa polja morajo biti izpolnjena"
      }
    }
  </script>
</body>
</html>
