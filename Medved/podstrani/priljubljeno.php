
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
 
  <title>Seznam</title>
  <link rel="stylesheet" type="text/css" href="/Medved/css/1.css" />
  <link rel="stylesheet" type="text/css" href="/Medved/css/brskaj.css" />
  <link rel="stylesheet" type="text/css" href="/Medved/css/priljubljeno.css" />
  <script src="/Medved/javaScript/1.js"></script>
  <script src="/Medved/javaScript/brskaj.js"></script>

</head>
<body class="telo">
  <!-- prva vrstica za prikaz ko je stran dovolj velika  --> 
<div id="vrsticaZgoraj" class = "navigacijskaVrstica">
  <a  href="/Medved/podstrani/brskaj.php">BRSKAJ</a>
  <a  href="/Medved/podstrani/isci.php">IŠČI</a>
  <a class="trenutna" href="/Medved/podstrani/priljubljeno.php">SEZNAM</a>
  <a  href="/Medved/podstrani/predlogi.php">PREDLOGI</a>
  <a  href="/Medved/podstrani/profil.php">PROFIL</a>
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
  <h2 id="naslovPriljubljeno" class="priljubljenoNaslov">Seznam dodanih anime-jev, ki so vam bili všeč</h2>
  <a class="pojdiNaVpis" id="gumbPojdiNaPrijavo" href="/Medved/index.php">VPIS</a>
</div>

<div id="centerDiv" class="centralniDiv">
  <!--tole je pop up okno  za prikaz opisa serije-->
  <div class="popUpAnime" id="AnimePopUp">
    <img id="AnimePopUpImg">
    <p id="AnimePopUpNaslov" class="naslovPopUpAnime"></p>
    <button id="zapriAnime" class="closeButton">X</button>
      <p class="opisVanglescini">Opis v angleščini: </p>
      <p id="opis" class="animeOpis"></p>
  </div>
</div>
<?php 
require "phpRequire/vpisan.php";

  require "phpRequire/pridobiAnime.php";


?>
  



    <script>
  

    if(vpisan){
      var jsoni = []
      //zapiši vse jsone v tabelo
      for( var i = 0; i < <?php echo $stAnimejev; ?>; i++){
        //tole bi blo bols dt v spremenljivko
        jsoni[i] = JSON.parse(<?php echo json_encode($tabela);?>[i])
      } 
              var dodaniDivi = 0;
      
              for(var i = 0; i < jsoni.length; i++){
                //naredi nov animeDiv element
                var newEl =  document.createElement("div")
                newEl.className = "animeDiv"
                //barve  ozadja se spreminjajo, vsaka druga
                if(dodaniDivi%2 == 0) newEl.style.backgroundColor = "rgb(85, 85, 85)"
                //naredi sliko za v div in ji daj source iz podatkov
                var img = document.createElement("img")
                img.className = "slikaGlavnaStran"
                img.src = jsoni[i].images.jpg.large_image_url
                //naredi naslov za v div, ime iz podatkov
                var h3 = document.createElement("h3")
                h3.className = "naslovAnime"
                h3.innerHTML = jsoni[i].title_english
                if(jsoni[i].title_english == null){
                  h3.innerHTML=jsoni[i].title
                }
                 //za vse elemente spodnje elemnte podobno, vsak svoj class
                var pocena = document.createElement("p")
                pocena.className = "ocena"
                pocena.innerHTML = "Ocena: " + jsoni[i].score
                var pgledalci = document.createElement("p")
                pgledalci.className = "gledalci"
                pgledalci.innerHTML = "Število gledalcev: " + jsoni[i].members
                //trailer podobno, le še nastavi se mu href, zato da je link do videa
                var atrailer = document.createElement("a")
                atrailer.className = "trailer"
                atrailer.setAttribute("href",jsoni[i].trailer.url)
                atrailer.innerHTML = "Napovednik"
                //žanrov je več zato je treba skozi celotno tabelo žanrov in dodati primerno vejice
                var pgenre = document.createElement("p")
                pgenre.className = "genre"
                pgenre.innerHTML = "Žanri: "
                for(var j = 0; j< jsoni[i].genres.length;j++){
                pgenre.innerHTML += jsoni[i].genres[j].name
                  if(j != jsoni[i].genres.length -1){
                    pgenre.innerHTML += ", "
                  }
                }

                var pdatum = document.createElement("p")
                pdatum.className = "datum"
                pdatum.innerHTML = "Datum predvajanja: " + prevediDatum(jsoni[i].aired.string);
                var pdolzina = document.createElement("p")
                pdolzina.className = "dolzina"
                pdolzina.innerHTML = "Dolžina: " + jsoni[i].episodes + " Epizod, " + prevediTrajanje(jsoni[i].duration );


                var gumbOdstrani = document.createElement("button")
                gumbOdstrani.className = "gumbOdstrani"
                gumbOdstrani.innerHTML = "ODSTRANI S SEZNAMA"
                gumbOdstrani.id = "gumbOdstrani" +  i;
                
                var gumbVnos = document.createElement("button")
                gumbVnos.id = "gumb"  + i;
                gumbVnos.className = "gumbZaVnos"
                gumbVnos.innerHTML = "DODAJ V SVOJ SEZNAM"

                gumbVnos.style.display = "none"

                const gumbZaVnosID = "gumb" + i;
                const gumbOdstraniId = "gumbOdstrani" +  i;
                          

                //doaj vse ustvarjene elemnte v anime div
                newEl.appendChild(img)
                newEl.appendChild(h3)
                newEl.appendChild(pocena)
                newEl.appendChild(pgledalci)
                newEl.appendChild(atrailer)
                newEl.appendChild(pgenre)
                newEl.appendChild(pdolzina)
                newEl.appendChild(pdatum)
                
                newEl.appendChild(gumbVnos);
                newEl.appendChild(gumbOdstrani);

                //dodaj anime div v centralni div strani
                document.getElementById("centerDiv").appendChild(newEl)
                var animeNaslov;
                //ustvari konstante ki bodo potrebne za prikaz podatkov v dodatnem pop up oknu vsakega animeja
                if(jsoni[i].title_english == null){
                  animeNaslov =jsoni[i].title
                }
                else {
                  animeNaslov = jsoni[i].title_english
              }
              const anmNaslov =animeNaslov;
                const anmOpis = jsoni[i].synopsis
                const anmIMGsrc = jsoni[i].images.jpg.large_image_url
                //ustvari funkciji ob kliku na naslov ali sliko prikažeta dodatno okno z opisom anime-ja in mu data primerni vrednosti za ta anime
                h3.addEventListener("click", function(){
                  document.getElementById("AnimePopUp").style.display ="block"
                  document.getElementById("AnimePopUpNaslov").innerHTML = anmNaslov
                  document.getElementById("opis").innerHTML = anmOpis
                  document.getElementById("AnimePopUpImg").src =  anmIMGsrc 
                });
                img.addEventListener("click", function(){
                  document.getElementById("AnimePopUp").style.display ="block"
                  document.getElementById("AnimePopUpNaslov").innerHTML = anmNaslov
                  document.getElementById("opis").innerHTML = anmOpis
                  document.getElementById("AnimePopUpImg").src =  anmIMGsrc 
                }); 
                const animeApiID = jsoni[i].mal_id;

                gumbOdstrani.addEventListener("click", function(){
                //ajax request
                var xhttp3 = new XMLHttpRequest();
                xhttp3.onreadystatechange = function() {
                  if (this.readyState == 4 && this.status == 200) {
                  document.getElementById(gumbZaVnosID).style.display = "block";
                  document.getElementById(gumbOdstraniId).style.display = "none";
                  }
                }
                //dodaj animeapiID da ve katerega je treba zbrisat
                xhttp3.open("GET", "/Medved/php/odstraniAnime.php?anime_api_ID=" + animeApiID, true);
                xhttp3.send();
                })
                const animeJson = jsoni[i];
                     //ob pritiskku na gumb Dodaj
                  gumbVnos.addEventListener("click", function(){
                  
                    console.log(animeJson)
                  //ajax request za beleženje animeja v bazo
                  var xhttp2 = new XMLHttpRequest();
                  xhttp2.onreadystatechange = function() {
                      //spremeni v gumb odstrani
                      if (this.readyState == 4 && this.status == 200) {
                  document.getElementById(gumbZaVnosID).style.display = "none";
                  document.getElementById(gumbOdstraniId).style.display = "block";
                      }
                  }

                  //dodaj animeApiId ker je tudi ta shranjen v bazi
                  xhttp2.open("POST", "/Medved/php/beleziAnime.php?anime_api_ID=" + animeApiID, true);
                  //v glavo napiši da pošiljaš json
                  xhttp2.setRequestHeader("Content-Type", "application/json");
                  //odstrani nepotrebne escape characterje, saj se json drugače uniči
                  xhttp2.send(animeJson);
                }); 



                dodaniDivi++;
              }
       

          
        
 }
 else{
  document.getElementById("gumbPojdiNaPrijavo").style.display = "block"
document.getElementById("naslovPriljubljeno").innerHTML = "Za uporabo te strani morate biti vpisani!"
 }

  //ob kliku na križec se zapre okno z opisom anime-ja
  document.getElementById("zapriAnime").onclick = function(){
  document.getElementById("AnimePopUp").style.display ="none"
}

    var števec = 0;
    //na začetuku že nastavi kateri stil zgornje vrstice je potreben
    nastaviStilZgornjeVrstice()
    //če se spremeni velikost okna popravi razred vrstice zgoraj
    window.addEventListener('resize', function() {
      nastaviStilZgornjeVrstice()
    });

    
  
   
  
    </script>
  </body>
</html>
