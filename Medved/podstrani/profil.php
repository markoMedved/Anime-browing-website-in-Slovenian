<!DOCTYPE html>
<html>
  
<head>
  <meta charset="UTF-8">
 
  <title>Profil</title>
  <link rel="stylesheet" type="text/css" href="/Medved/css/1.css" />
  <link rel="stylesheet" type="text/css" href="/Medved/css/profil.css" />
  <script src="/Medved/javaScript/1.js"></script>
  <script src="/Medved/javaScript/registracija.js"></script>
  <script src="/Medved/javaScript/profil.js"></script>
  <script src="/Medved/javaScript/jquerry.js"> </script>
 
</head>
<body class="telo">
  <!-- prva vrstica za prikaz ko je stran dovolj velika  --> 
<div id="vrsticaZgoraj" class = "navigacijskaVrstica">
  <a  href="/Medved/podstrani/brskaj.php">BRSKAJ</a>
  <a  href="/Medved/podstrani/isci.php">IŠČI</a>
  <a  href="/Medved/podstrani/priljubljeno.php">SEZNAM</a>
  <a  href="/Medved/podstrani/predlogi.php">PREDLOGI</a>
  <a class="trenutna" href="/Medved/podstrani/profil.php">PROFIL</a>
</div>



<!-- tretja vrstica za prikaz po overflowu --> 
<div id="vrsticaZgoraj3" class = "navigacijskaVrstica3">
  <a id="navigacijskaVrsticaGumb" class="gumbZaDropdown" onclick="uporabiDropdown()">DEJANJE  ▼</a>
  <div id="Dropdown" class ="DropdownVsebina">
    <a  href="/Medved/podstrani/brskaj.php">BRSKAJ</a>
    <a  href="/Medved/podstrani/isci.php">IŠČI</a>
    <a  href="/Medved/podstrani/priljubljeno.php">SEZNAM</a>
    <a  href="/Medved/podstrani/predlogi.php">PREDLOGI</a>
    
    <a  href="/Medved/podstrani/profil.php">PROFIL</a>
  </div>
</div>
<div class="zgornjiDiv">
  <h2 id="naslovUsername" class="predlogiNaslov">Profil <?php 
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

  echo $_SESSION["username"];
   ?></h2>
  <a class="pojdiNaVpis" id="gumbPojdiNaPrijavo" href="/Medved/index.php">VPIS</a>
</div>

<div id="vseRazenNaslova">
  <div class="stat">
    <h3 class="statNaslov">
      Statistika
    </h3>
    <p id="najbljubsiZanri">Najljubši žanri: </p>
    <p id="povprecnaOcena">Povprečna ocena pogledanih animejev: </p>
    <p id="stAnimejev">Število dodanih animejev: </p>
    <p id="casGledanja">Čas gledanja: </p>
    
  </div>
  <div class="nastavitve">
    <h3 class="nastavitveNaslov">
      Nastavitve
    </h3>
   
    <a id="linkZaSprememboGesla" class="spremeniGeslo">
      Spremeni Geslo
    </a>
    <a id="linkZaIzpis" class="izpis">
      Izpis
    </a>
  </div>
  
  <div id="zamenjaj" class="popUpProfil">
    <button id="zapriPopUp" class="closeButton">X</button>
      <h2 id="naslovPopUp" class="naslovPopUpProfil">
        Spremeni Geslo
      </h2>
      <p id="popUpNapis1" class="inputLabel1">
       Staro geslo
      </p>
      <input id="vnos1" class="input1" type="password"> 
      <p id="popUpNapis2" class="inputLabel2" >
        Novo geslo
      </p>
      <input id="vnos2" class="input2" type="password">
      <p id="popUpNapis3" class="inputLabel3" >
      Potrdi geslo
      </p>
      <input id="vnos3" class="input3" type="password">
      <a id="potrdiGumb" class="potrdi">POTRDI</a>
  
      <p id="opTxt" class="opozorilniTekst">
       
      </p>
  </div>
  <div class="animacija"> 
  <p id="tekstAnimacija" class="tekstAnimacija">
  Animacija slik vseh dodanih animejev ob miški nad njo
  </p>
  <img id="pikachuSlika" class="pikachu" src="/Medved/slike/pikachu.png">
  <img id="animeSlike" class="animacijaSlike">
  </div>
  <div id="prijatelji">
      <h1 id="prijateljiNaslov"> 
        Vpiši uporabniško ime prijatelja
      </h1>
      <p id="opozorilniTekstPredlogi"> 
          
      <p>
      <button id="dodajButton"> 
        Dodaj prijatelja
      </button>
      <input id="iskalnik" class="iskalnaVrstica">
      </input>
      <h1 id="prijateljiPredlogi"> 
       Predlogi prijateljev
      </h1>
     
  </div>
 


  <div id="predlagaj">
  <button id="zapriPredlog" class="closeButton">X</button>
  <h3 id="uporabnikPredlog"> 
  
  <h3>
  <p id="tvojPredlog">
        Tvoj predlog
   </p>
   <input id="predlogInput"> </input>
   <button id="predlagajButton"> 
        Predlagaj
      </button>
      <button id="odstraniButton"> 
        Odstrani prijatelja
      </button>

  </div>
</div>
  

<?php 
require "phpRequire/vpisan.php";

  require "phpRequire/pridobiAnime.php";
  require "phpRequire/pridobiPrijatelje.php";

?>



    <script>
      

        //na začetuku že nastavi kateri stil zgornje vrstice je potreben
        nastaviStilZgornjeVrstice()
    //če se spremeni velikost okna popravi razred vrstice zgoraj
    window.addEventListener('resize', function() {
      nastaviStilZgornjeVrstice()
    });
  
    var števec = 0;


      if(!vpisan){
        //če nihče ni vpisan opozorilo in nič drugega (vse razen naslova div se ne kaže)
        document.getElementById("gumbPojdiNaPrijavo").style.display = "block"
      document.getElementById("naslovUsername").innerHTML = "Za uporabo te strani morate biti vpisani!"
      document.getElementById("vseRazenNaslova").style.display = "none"
      }
      else {
        
      //pridobi tabelo stringov vseh prijatljev
      var tabelaPrijatleljev = <?php echo json_encode($tabelaPrijatleljev);?>;
      //pridobi tabelo vseh predlogov teh prijatljev
      var tabelaPredlogov = <?php echo json_encode($tabelaPredlogov);?>

      var zacetnaPozicija = 0

      //pojdi skozi celotno tabelo prijateljev
      for(var i = 0; i < tabelaPrijatleljev.length; i++){
        //za vsakega naredi now element s imenom in predlogom
        var prijatelj = document.createElement("p");
        prijatelj.className = "predlogIme"
        prijatelj.innerHTML = tabelaPrijatleljev[i] + ": " 
        prijatelj.style.top = zacetnaPozicija + i * 40 + "px"
        const prijateljUsername = tabelaPrijatleljev[i]
        prijatelj.addEventListener("click", function(){
          //z pritiskom na ime prijetelja se odpre opcija odstraniti prijatelja ali pa mu nekaj predlagati(pop up)
          document.getElementById("predlagaj").style.display = "block"
          document.getElementById("uporabnikPredlog").innerHTML = prijateljUsername
          
        })
        document.getElementById("prijatelji").appendChild(prijatelj)
        //ustvati še element za predlog
        var predlog = document.createElement("p")
        predlog.className = "predlog"
        predlog.innerHTML = tabelaPredlogov[i]
        predlog.style.top = zacetnaPozicija + i * 40 + "px"


        document.getElementById("prijatelji").appendChild(predlog)
      }
      
      var jsoni = []



        //pridobi jsone(potrebno za statistiko)
        for( var i = 0; i < <?php echo $stAnimejev; ?>; i++){
          jsoni[i] = JSON.parse(<?php echo json_encode($tabela);?>[i])
        }
      
        var zanri = []
   var zanriPonovitve=[]
   //povprečna ocena pogledanih animjev
   var meanScore = 0;
   //čas gledanja anime-jev
   var casGledanja = 0;
   //viri slik za animacijo
   var slikeSrc= [];


    //counter za prikaz primerene slike
    var count = 0;
    //interval menjanja slik
    var interval;
    //števec za določanje zaporedne animacije
    var pikachuŠtevec = 0;
    //bool za stanje animacije
    var trenutnoAnimacija = false;
   
        //če je vsaj ena dodan anime
       if(jsoni[0] != undefined){
           document.getElementById("animeSlike").src =jsoni[0].images.jpg.large_image_url;
         }
         //če ni dodanih animejev
        else{
            document.getElementById("tekstAnimacija").innerHTML = ""
         }
           

      //čez celotno tabelo jsonov
      for(var i = 0; i < jsoni.length; i++){
          for(var j = 0; j < jsoni[i].genres.length; j++){
            //zapiši vse žanre (tudi ponovitve)
             zanri.push(jsoni[i].genres[j].name)
          }
          //izračunaj povprečno oceno
          meanScore += jsoni[i].score;
          //zmnoži št epizod krat trajanje ene epizode in seštej za vse skupaj
          casGledanja += jsoni[i].episodes * pridobiStEpizod(jsoni[i].duration)
          //zapiši vire slik v tabelo za animacijo
          slikeSrc[i] = jsoni[i].images.jpg.large_image_url

      }

      //animacija slik ob miški hover nad sliko
      document.getElementById("animeSlike").onmouseover = function(){
        //na vsako sekundo izvedi funkcijo test
       interval= window.setInterval(test, 1000)
      
      }
      document.getElementById("animeSlike").onmouseout = function(){
        //ko gre miška izven slike prekini animacijo
        window.clearInterval(interval)
      
      }

      document.getElementById("pikachuSlika").onmouseover = function(){
        //ob miški nad pikachujem
        $(document).ready(function(){ 
          //boolean pomemben da med animacijo ne zažene nove animacije
          if(trenutnoAnimacija == false){
            //prva animacija
            if(pikachuŠtevec%3 == 0){
              trenutnoAnimacija = true;
              //source slike daj na gif da bo tekel
          document.getElementById("pikachuSlika").src = "/Medved/slike/pikachu.gif"
          //animiraj da se premakne na specificiran položaj
          $("#pikachuSlika").animate({left: "800px", top:"-200px"}, 1500)
          //po določenem času naredi funkcijo test2
         window.setTimeout(test2, 1500)
         }
         //naprej zelo podobno
         else if(pikachuŠtevec%3==1){
          trenutnoAnimacija = true;
          document.getElementById("pikachuSlika").src = "/Medved/slike/flippedPikachu.gif"
          $("#pikachuSlika").animate({left: "-1800px", top:"-200px"}, 1000)
         window.setTimeout(test4, 1000)
        }
        else{
          trenutnoAnimacija = true;
          document.getElementById("pikachuSlika").src = "/Medved/slike/pikachu.gif"
          $("#pikachuSlika").animate({left: "-300px", top:"300px"}, 1000)
          window.setTimeout(test6, 1000)
        }
          }
        })
       
        
      }
      
    
     //za določanje najbolj pogostih žanrov(3 najbolj pogoste in jih izpiši na podstran)
      for(var o = 0; o < 3; o++){
        document.getElementById("najbljubsiZanri").innerHTML += maxPonovitev(zanri) + " "
        //v tabelo daj tako da se ne bodo več že določeni max elementi še enkrat izpisovali
        zavrziElemente(maxPonovitev(zanri), zanri)
      }

      //izračun povprečja dokončen
      meanScore /= jsoni.length;
      //zaokroži na 3 decimalke in izpiši
      document.getElementById("povprecnaOcena").innerHTML += meanScore.toFixed(3) 
      //izpišo število pogledanih anime-jev
      document.getElementById("stAnimejev").innerHTML += jsoni.length
      
      //čas gledanja je v minutah
      var ure = Math.floor(casGledanja/60)
      var minute = casGledanja%60;
      document.getElementById("casGledanja").innerHTML += ure + " ur in " + minute + " minut"

    //ob pritisku na link se odpre popup okno za spremembo gesla
    document.getElementById("linkZaSprememboGesla").onclick = function(){
      document.getElementById("zamenjaj").style.display = "block"
    }

    //križec za zapiranje pop up okna
    document.getElementById("zapriPopUp").onclick = function(){
      document.getElementById("zamenjaj").style.display = "none"
      document.getElementById("opTxt").innerHTML = ""
    }
  
    //gumb za potrjevanje spremembe gesla
    document.getElementById("potrdiGumb").onclick = function(){
      //opozorilni tekst daj na rdečno barvo
      document.getElementById("opTxt").style.color = "red";
            //če geslo ni primerno izpiši opozorilni tekst
          if(!preveriGeslo(document.getElementById("vnos2").value)){
            document.getElementById("opTxt").innerHTML = "Geslo mora vsebovati vsaj 1 veliko črko, 1 malo črko in 1 številko"
          }
          //enakost gesel pri potrditvi
          else if(document.getElementById("vnos2").value != document.getElementById("vnos3").value){
            document.getElementById("opTxt").innerHTML = "Gesli nista enaki"
          }
          //če ne je geslo vredu in ga probaj posodobiti
          else{
            //objekt, this se potem nanaša nanj
            var xhttp4 = new XMLHttpRequest();
              xhttp4.onreadystatechange = function(){
                if (this.readyState == 4 && this.status == 200) {
                  //preveri če je bilo staro geslo pravilno vpisano
                  if(this.response == false){
                    document.getElementById("opTxt").style.color = "red";
                    document.getElementById("opTxt").innerHTML = "Napačno staro geslo"
                  }
                  //če je pravilno se bo geslo posobobilo
                  else{
                    document.getElementById("opTxt").style.color = "green";
                    document.getElementById("opTxt").innerHTML = "Geslo posodobljeno"
                  }
                }
              }
              var staroGeslo = document.getElementById("vnos1").value
              var novoGeslo = document.getElementById("vnos2").value
              
              xhttp4.open("GET", "/Medved/php/posodobiGeslo.php?staroGeslo=" + staroGeslo + "&novoGeslo=" + novoGeslo, true);
              xhttp4.send();

          }
        
    }
    //koda da grejo ob Entru stvari naprej
    document.getElementById("vnos1").addEventListener("keypress", function(event){
      if(event.key == "Enter"){
        document.getElementById("vnos2").focus();
        
      }
    })
    document.getElementById("vnos2").addEventListener("keypress", function(event){
      if(event.key == "Enter"){
        document.getElementById("vnos3").focus();
      
        
      }
    })
    //pri zadnjem polju pa se simulira potrdi gumb klik
    document.getElementById("vnos3").addEventListener("keypress", function(event){
      if(event.key == "Enter"){
        document.getElementById("vnos3").blur();
        document.getElementById("potrdiGumb").click()
        
      }
    })

    //izpis ob kliku, preusmerjanje nazaj na vpis
    document.getElementById("linkZaIzpis").onclick = function(){
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
      
            location.replace("/Medved/index.php")
         
       }
      };
      xhttp.open("GET", "/Medved/php/izpis.php", true);
      xhttp.send();
      
    }


    //ob kliku dodaj prijatelja 
    document.getElementById("dodajButton").onclick = function(){
      var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

          if(this.response == 1){
            //uporabnik obstaja na novo se naloži stran
            document.getElementById("opozorilniTekstPredlogi").innerHTML = "";
            location.reload()
          
          }
          else{
            //izpipše se da uporabnik ne obstaja, ali pa da je že dodan 
            document.getElementById("opozorilniTekstPredlogi").innerHTML = this.response;
          }
         
       }
      };

      xhttp.open("GET", "/Medved/php/dodajPrijatelja.php?username=" +document.getElementById("iskalnik").value, true);
      xhttp.send();
    }

    //ob pritisku na enter simuliraj klik na dodaj prijatelja
    document.getElementById("iskalnik").addEventListener("keypress", function(event){
  if(event.key == "Enter"){

    document.getElementById("dodajButton").click()
  }
})

//križec za zapiranje predloga
document.getElementById("zapriPredlog").onclick = function(){
  document.getElementById("predlagaj").style.display = "none"
}


//ob pritisku na gumb odstrani prijatelja
document.getElementById("odstraniButton").onclick = function(){
  //request izbriše tega prijatelja iz baze prijatljev
  var xhttp = new XMLHttpRequest();
 xhttp.onreadystatechange = function(){
    if (this.readyState == 4 && this.status == 200) {
                //osveži lokacijo po odstranitvi
                location.reload()
     }
 }
 xhttp.open("GET", "/Medved/php/odstraniPrijatelja.php?prijatelj="+document.getElementById("uporabnikPredlog").innerHTML, true);
xhttp.send();
  
}

//zapiši v bazo predlog ob kliku na gumb predlagaj
document.getElementById("predlagajButton").onclick = function(){
  var xhttp = new XMLHttpRequest();
 xhttp.open("GET", "/Medved/php/predlagaj.php?predlog=" + document.getElementById("predlogInput").value + "&prijatelj=" + document.getElementById("uporabnikPredlog").innerHTML, true);
xhttp.send();
document.getElementById("predlagaj").style.display = "none"
  
}
   
      }


    



  
   
 
    </script>
  </body>
</html>
