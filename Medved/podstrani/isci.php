<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
 
  <title>Iskanje</title>
  <link rel="stylesheet" type="text/css" href="/Medved/css/1.css" />
  <link rel="stylesheet" type="text/css" href="/Medved/css/brskaj.css" />
  <link rel="stylesheet" type="text/css" href="/Medved/css/isci.css" />
  <script src="/Medved/javaScript/1.js"></script>
  <script src="/Medved/javaScript/brskaj.js"></script>
  <script src="/Medved/javaScript/jquerry.js"> </script>

</head>
<body class="telo">
  <!-- prva vrstica za prikaz ko je stran dovolj velika  --> 
<div id="vrsticaZgoraj" class = "navigacijskaVrstica">
  <a  href="/Medved/podstrani/brskaj.php">BRSKAJ</a>
  <a class="trenutna" href="/Medved/podstrani/isci.php">IŠČI</a>
  <a  href="/Medved/podstrani/priljubljeno.php">SEZNAM</a>
  <a  href="/Medved/podstrani/predlogi.php">PREDLOGI</a>
  <a  href="/Medved/podstrani/profil.php">PROFIL</a>
</div>


<!-- tretja vrstica za prikaz po overflowu --> 
<div id="vrsticaZgoraj3" class = "navigacijskaVrstica3">
  <a id="navigacijskaVrsticaGumb" class="gumbZaDropdown" onclick="uporabiDropdown()">DEJANJE  ▼</a>
  <div id="Dropdown" class ="DropdownVsebina">
    <a href="/Medved/podstrani/brskaj.php">BRSKAJ</a>
    <a  href="/Medved/podstrani/isci.php">IŠČI</a>
    <a  href="/Medved/podstrani/priljubljeno.php">SEZNAM</a>
    <a  href="/Medved/podstrani/predlogi.php">PREDLOGI</a>
    <a  href="/Medved/podstrani/profil.php">PROFIL</a>
  </div>
</div>

 <!--tole je div ki vsebuje iskalnik-->
<div class="zgornjiDiv">
  <h2 class="iskanjeNaslov">Išči anime</h2>
  <input id="isciVrstica" class="iskalnaVrstica"></input>
  <button id="isci" class="gumbIsci">IŠČI</button>
  <h2 id="tekstNiZadetka" class="niZadetka">Ni zadetkov</h2>
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
?>
  
    <script>
   
    //začetni url za vse klice
    const apiUrl = "https://api.jikan.moe/v4/anime?sfw=true"
    
    //koliko anime divov bo dodanih z enim api klicem
    var dodaniDivi = 0

    //ob kliku na gumb išči
    document.getElementById("isci").onclick = function(){
      //tukaj je potrebno ob vsakem iskanju novo posodobit tabelo dodanih animejev
    var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            tabelaAnime = this.response;
              //na začetku pobriši vse dosedanje anime dive
      for(var j = 0; j < dodaniDivi; j++){
        document.getElementById("centerDiv").removeChild(document.getElementById("centerDiv").lastChild)
      }
      //nato postavi dodani Divi na 0 da bo pravilno
      dodaniDivi = 0
      //popravi url z pravilnim iskanjem
      var url = apiUrl +"&q="+ document.getElementById("isciVrstica").value
    //kliči api in vrni response v obliki jsona
  fetch(url).then(response => {
    return response.json();
  })
  .then(data => {
    //zapiši vse potrebne podate iz jsona v spremenljivko podatki
    var podatki = data.data
    //najprej postavi tekst da ni zadetka brez displaya
    document.getElementById("tekstNiZadetka").style.display = "none"
    //če ni zadetkov napiši tekst
    if(podatki.length == 0){
      document.getElementById("tekstNiZadetka").style.display = "block"
    }


    //klic vedno vrne 25 anime-jev v tabeli zato pojdi čez celo tabelo
    for(var i = 0; i <  podatki.length; i++){
      //naredi nov animeDiv element
      var newEl =  document.createElement("div")
      newEl.className = "animeDiv"
      //barve  ozadja se spreminjajo, vsaka druga
      if(dodaniDivi%2 == 0) newEl.style.backgroundColor = "rgb(85, 85, 85)"
      //naredi sliko za v div in ji daj source iz podatkov
      var img = document.createElement("img")
      img.className = "slikaGlavnaStran"
      img.src = podatki[i].images.jpg.large_image_url
      //naredi naslov za v div, ime iz podatkov
      var h3 = document.createElement("h3")
      h3.className = "naslovAnime"
      h3.innerHTML = podatki[i].title_english
      if(podatki[i].title_english == null){
        h3.innerHTML = podatki[i].title
      }
      //za vse elemente spodnje elemnte podobno, vsak svoj class
      var pocena = document.createElement("p")
      pocena.className = "ocena"
      pocena.innerHTML = "Ocena: " + podatki[i].score
      var pgledalci = document.createElement("p")
      pgledalci.className = "gledalci"
      pgledalci.innerHTML = "Število gledalcev: " + podatki[i].members
      //trailer podobno, le še nastavi se mu href, zato da je link do videa
      var atrailer = document.createElement("a")
      atrailer.className = "trailer"
      atrailer.setAttribute("href",podatki[i].trailer.url)
      atrailer.innerHTML = "Napovednik"
      //žanrov je več zato je treba skozi celotno tabelo žanrov in dodati primerno vejice
      var pgenre = document.createElement("p")
      pgenre.className = "genre"
      pgenre.innerHTML = "Žanri: "
      for(var j = 0; j< podatki[i].genres.length;j++){
      pgenre.innerHTML += podatki[i].genres[j].name
      if(j != podatki[i].genres.length -1){
        pgenre.innerHTML += ", "
      }
    }
      
      var pdatum = document.createElement("p")
      pdatum.className = "datum"
      pdatum.innerHTML = "Datum predvajanja: " + prevediDatum(podatki[i].aired.string);
      var pdolzina = document.createElement("p")
      pdolzina.className = "dolzina"
      pdolzina.innerHTML = "Dolžina: " + podatki[i].episodes + " Epizod, " + prevediTrajanje(podatki[i].duration) ;

     
      var gumbVnos = document.createElement("button")
      gumbVnos.id = "gumb" + i;
      gumbVnos.className = "gumbZaVnos"
      gumbVnos.innerHTML = "DODAJ V SVOJ SEZNAM"

      var gumbOdstrani = document.createElement("button")
      gumbOdstrani.className = "gumbOdstrani";
      gumbOdstrani.innerHTML = "ODSTRANI S SEZNAMA"
      gumbOdstrani.id = "gumbOdstrani" + i;

      //treba neke unikatne id-je za vsak anime
      const gumbZaVnosID = "gumb" +  i;
      const gumbOdstraniId = "gumbOdstrani" +  i

      //doaj vse ustvarjene elemnte v anime div
      newEl.appendChild(img)
      newEl.appendChild(h3)
      newEl.appendChild(pocena)
      newEl.appendChild(pgledalci)
      newEl.appendChild(atrailer)
      newEl.appendChild(pgenre)
      newEl.appendChild(pdolzina)
      newEl.appendChild(pdatum)
      //če je uporabnik vpisan dodaj gumbe, opisano že v brskaj
      if(vpisan) {
        if(tabelaAnime.includes(String(podatki[i].mal_id))){
            gumbVnos.style.display = "none";
           gumbOdstrani.style.display = "block";
        }
      newEl.appendChild(gumbOdstrani);
      newEl.appendChild(gumbVnos);
      }
      //dodaj anime div v centralni div strani
      document.getElementById("centerDiv").appendChild(newEl)
  
      //ustvari konstante ki bodo potrebne za prikaz podatkov v dodatnem pop up oknu vsakega animeja
      var animeNaslov;
                //ustvari konstante ki bodo potrebne za prikaz podatkov v dodatnem pop up oknu vsakega animeja
                if(podatki[i].title_english == null){
                  animeNaslov =podatki[i].title
                }
                else {
                  animeNaslov = podatki[i].title_english
              }
      const anmNaslov =animeNaslov;
      const anmOpis = podatki[i].synopsis
      const anmIMGsrc = podatki[i].images.jpg.large_image_url
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
      //kot pri brskaj eneka funkcija gumba
      const animeJson = podatki[i];
      const animeApiID = podatki[i].mal_id;
      gumbVnos.addEventListener("click", function(){
        
        
       var xhttp2 = new XMLHttpRequest();
         xhttp2.onreadystatechange = function() {
           //spremeni v gumb odstrani
           if (this.readyState == 4 && this.status == 200) {
        document.getElementById(gumbZaVnosID).style.display = "none";
        document.getElementById(gumbOdstraniId).style.display = "block";
           }
        }
       
        xhttp2.open("POST", "/Medved/php/beleziAnime.php?anime_api_ID=" + animeApiID, true);
        xhttp2.setRequestHeader("Content-Type", "application/json");
        xhttp2.send(JSON.stringify(animeJson).replace(/\\n/g, "").replace(/\\"/g, ""));
      }); 

      gumbOdstrani.addEventListener("click", function(){
        var xhttp3 = new XMLHttpRequest();
        xhttp3.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
          document.getElementById(gumbZaVnosID).style.display = "block";
          document.getElementById(gumbOdstraniId).style.display = "none";
          }
        }
        
        xhttp3.open("GET", "/Medved/php/odstraniAnime.php?anime_api_ID=" + animeApiID, true);
        xhttp3.send();
      })
      
      
      //povečaj vsakič dodaniDiv za 1, pomembno za prikaz gumba spodaj in drugo
      dodaniDivi++;

    }
   
  
  })
       
          }
          
        }
    
  xhttp.open("GET", "/Medved/php/pridobiTabeloAnime.php", true);
  xhttp.send();
      
    
    }

    var števec = 0;
    //na začetuku že nastavi kateri stil zgornje vrstice je potreben
    nastaviStilZgornjeVrstice()
    //če se spremeni velikost okna popravi razred vrstice zgoraj
    window.addEventListener('resize', function() {
      nastaviStilZgornjeVrstice()
    });

    //ob kliku na križec se zapre okno z opisom anime-ja
  document.getElementById("zapriAnime").onclick = function(){
    document.getElementById("AnimePopUp").style.display ="none"
}
  

//če si v fokusu input fielda in pritisneš enter simuliraj pritisk na gumb išči
document.getElementById("isciVrstica").addEventListener("keypress", function(event){
  if(event.key == "Enter"){
    document.getElementById("isci").click();
  }
})
  
 

  
    </script>
  </body>
</html>

