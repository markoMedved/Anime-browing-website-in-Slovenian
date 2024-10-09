<!--VSTOPNA STRAN SPLETNE STRANI, TUKAJ BO PRIJAVA, MOŽNOST NEKATERIH FUNKCIJ BO TUDI BREZ PRIJAVE-->

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Registracijo</title>
    <link rel="stylesheet" type="text/css" href="/Medved/css/1.css" />
    <link rel="stylesheet" type="text/css" href="/Medved/css/index.css" />
    <script src="/Medved/javaScript/1.js"></script>
    <script src="/Medved/javaScript/jquerry.js"></script>
    <script src="/Medved/javaScript/registracija.js"></script>
</head>
<body class="telo">
    <!-- prva vrstica za prikaz ko je stran dovolj velika -->
    <div id="vrsticaZgoraj" class="navigacijskaVrstica">
        <a href="/Medved/podstrani/brskaj.php">BRSKAJ</a>
        <a href="/Medved/podstrani/isci.php">IŠČI</a>
        <a href="/Medved/podstrani/priljubljeno.php">PRILJUBLJENO</a>
        <a href="/Medved/podstrani/predlogi.php">PREDLOGI</a>
        <a href="/Medved/podstrani/profil.php">PROFIL</a>
    </div>

    <!-- tretja vrstica za prikaz po overflowu -->
    <div id="vrsticaZgoraj3" class="navigacijskaVrstica3">
        <a id="navigacijskaVrsticaGumb" class="gumbZaDropdown" onclick="uporabiDropdown()">DEJANJE ▼</a>
        <div id="Dropdown" class="DropdownVsebina">
            <a class="trenutna" href="/Medved/podstrani/brskaj.php">BRSKAJ</a>
            <a href="/Medved/podstrani/isci.php">IŠČI</a>
            <a href="/Medved/podstrani/priljubljeno.php">PRILJUBLJENO</a>
            <a href="/Medved/podstrani/predlogi.php">PREDLOGI</a>
            <a href="/Medved/podstrani/profil.php">PROFIL</a>
        </div>
    </div>

    <div class="vpis">
        <h1 class="naslovVpis">Registracija</h1>
        <div class="podatkiZaVpis">
            <form>
                <br>
                <p class="premikajočiTekstUsername" id="napisUsername">Uporabniško ime</p>
                <input class="vpisObmočje" type="text" id="username"><br><br>
                <br>
                <p class="premikajočiTekstGeslo" id="napisGeslo">Geslo</p>
                <p class="prikazigeslo" id="prikazGesla"></p>
                <input class="vpisObmočje" id="geslo" type="password">
                <p id="nezadovoljivoGeslo" class="opozorilniTekst"></p>
                <p id="nezadovoljivoVnos" class="opozorilniTekst"></p>
                <p id="napacniPodatki" class="opozorilniTekst"></p>
            </form>
        </div>
        <button class="vpisGumb" id="gumbZaVpis">REGISTRACIJA</button>
        <div class="registracijaDiv">
            <p id="zeimasRacun" class="nimasRacunaTekst">Že imaš račun?</p>
            <a class="registracijaTekst" href="/Medved/index.php">VPIS</a>
        </div>
    </div>

    <?php 
    require "phpRequire/vpisan.php";
    ?>

    <script>
        if(vpisan){
            location.replace('/Medved/podstrani/brskaj.php')
        }

        // tole je bilo treba dodati ker je bilo v chromu malo drugače
        var pozicijaGeslo = "120px";
        
        // števec za togglanje dropdowna
        var števec = 0;

        // na začetku že nastavi kateri stil zgornje vrstice je potreben
        nastaviStilZgornjeVrstice();

        // če se spremeni velikost okna popravi razred vrstice zgoraj
        window.addEventListener('resize', function() {
            nastaviStilZgornjeVrstice();
        });

        // naredilo bo animacijo tudi če klikneš na tekst, ker tekst prekriva
        document.getElementById("napisGeslo").addEventListener("click", function(){
            document.getElementById("geslo").focus();
        });
        document.getElementById("napisUsername").addEventListener("click", function(){
            document.getElementById("username").focus();
        });

        // animacija za tekst z jquery
        $(document).ready(function(){
            if(document.getElementById("username").value!=""){
                $("#napisUsername").animate({top: "20px"});
            }
            if(document.getElementById("geslo").value!=""){
                $("#napisGeslo").animate({top: "90px"});
            }

            $("#username").focus(function(){
                $("#napisUsername").animate({top: "20px"});
                $("#napisUsername").css({color:"rgb(124, 210, 236)"});
            });
            $("#username").focusout(function(){
                if(document.getElementById("username").value==""){
                    $("#napisUsername").animate({top: "50px"});
                }
                $("#napisUsername").css({color:"white"});
            });
            $("#geslo").focus(function(){
                $("#napisGeslo").animate({top: "90px"});
                $("#napisGeslo").css({color:"rgb(124, 210, 236)"});
            });
            $("#geslo").focusout(function(){
                if(document.getElementById("geslo").value==""){
                    $("#napisGeslo").animate({top: pozicijaGeslo});
                }
                $("#napisGeslo").css({color:"white"});
            });
        });

        // funkcija ki piše opozorilni tekst če geslo ni primerno
        preverjajGeslo();

        var cnt = 0;

        // funkcija za skrivanje in prikaz gesla
        skrijGeslo();

        // če si v fokusu input fielda za username in pritisneš enter pojdi na input field za geslo
        document.getElementById("username").addEventListener("keypress", function(event){
            if(event.key == "Enter"){
                document.getElementById("geslo").focus();
            }
        });

        // če si v fokusu input fielda za geslo in pritisneš enter simuliraj pritisk na gumb vpis
        document.getElementById("geslo").addEventListener("keypress", function(event){
            if(event.key == "Enter"){
                document.getElementById("gumbZaVpis").click();
                document.getElementById("geslo").blur();
            }
        });

        // ob pritisku na gumb
        document.getElementById("gumbZaVpis").onclick = function(){
            // ponastavi opozorilni tekst
            document.getElementById("nezadovoljivoVnos").innerHTML = "";

            // če imata oba vnos
            if(document.getElementById("username").value != "" && document.getElementById("geslo").value != ""){
                // če je geslo primerno(preverjnje kar z opozorilnega teksta)
                if(document.getElementById("nezadovoljivoGeslo").innerHTML == "Primerno geslo"){
                    // ajax request za registracijo uporabnika
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            console.log(this.response);

                            // če je response true je bila uspešna registracija in spremeni lokacijo na stran brskaj
                            if(this.responseText == true){
                                location.replace("/Medved/podstrani/brskaj.php");
                            }
                            // če je bil ta response to uporabniško ime že obstaja torej bo treba drugega
                            else if(this.responseText == "Obstaja"){
                                document.getElementById("nezadovoljivoVnos").innerHTML = "Uporabniško ime že obstaja";
                            }
                        }
                    };
                    // pošlji request z usernameom in geslom iz inputov
                    xhttp.open("GET", "/Medved/php/registracija.php?username=" + document.getElementById("username").value + "&password=" + document.getElementById("geslo").value, true);
                    xhttp.send();
                }
            }
            // če kateri nima vnosa
            else{
                document.getElementById("nezadovoljivoVnos").innerHTML = "Vsa polja morajo biti izpolnjena";
            }
        };
    </script>
</body>
</html>
