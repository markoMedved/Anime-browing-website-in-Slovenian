<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
 
  <title>Predlogi</title>
  <link rel="stylesheet" type="text/css" href="/Medved/css/1.css" />
  <link rel="stylesheet" type="text/css" href="/Medved/css/brskaj.css" />
  <link rel="stylesheet" type="text/css" href="/Medved/css/predlogi.css" />
  <script src="/Medved/javaScript/1.js"></script>
  <script src="/Medved/javaScript/brskaj.js"></script>
  <script src="/Medved/javaScript/predlogi.js"></script>
  

</head>
<body class="telo">
  <!-- prva vrstica za prikaz ko je stran dovolj velika  --> 
<div id="vrsticaZgoraj" class = "navigacijskaVrstica">
  <a  href="/Medved/podstrani/brskaj.php">BRSKAJ</a>
  <a  href="/Medved/podstrani/isci.php">IŠČI</a>
  <a  href="/Medved/podstrani/priljubljeno.php">SEZNAM</a>
  <a class="trenutna" href="/Medved/podstrani/predlogi.php">PREDLOGI</a>
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
  <h2 id="naslovPredlogi" class="predlogiNaslov">Predlogi za vas</h2>
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
  require 'phpRequire/pridobiTabeloAnimeID.php';

?>


  
    <script>
      //probaj vsaj malo boljše extractat javascript
      //dodaj funkcije prepare za sql inject 
      //prijateljski sistem
    
    //na začetuku že nastavi kateri stil zgornje vrstice je potreben
    nastaviStilZgornjeVrstice()
    //če se spremeni velikost okna popravi razred vrstice zgoraj
    window.addEventListener('resize', function() {
      nastaviStilZgornjeVrstice()
    });
  


      //če ni vpisan nihče napiši to
      if(!vpisan){
        document.getElementById("gumbPojdiNaPrijavo").style.display = "block"
        document.getElementById("naslovPredlogi").innerHTML = "Za uporabo te strani morate biti vpisani!"
      }
      //drugače izvedi to
      else{
        var jsoni = []
      for( var i = 0; i < <?php echo $stAnimejev; ?>; i++){
        jsoni[i] = JSON.parse(<?php echo json_encode($tabela);?>[i])
      }

      //vsi dodani anime-ji (ID-ji)
      var tabelaAnime = <?php echo json_encode($tabelaAnime1) ?>;

        var števec = 0;
        var dodaniDivi = 0;
        //povprečno število epizod
        var meanEpisodes = 0;
    
                //izračunaj povprečno število epizod vseh dodanih anime-jev uporabnika
            for(var w = 0; w < jsoni.length; w++){
                  
                  meanEpisodes += jsoni[w].episodes
                  
              }
                meanEpisodes /= jsoni.length
               
                //url za api 
                const apiUrl = 'https://api.jikan.moe/v4/top/anime';
                //dodaj takoj parameter ki je vedno prisoten, in za katero stran
                var url;
                //spremenvljivka ki izbere kasneje katera stran apija bo klicana
                var naključno;
                //tabela točkovanj vseh animejev
                var scores = []
                //tabela indeksov izbranih animejev
                var indeksi= []
                
 
             //3x naredi api call (to je meja kolikokrat na sekundo lahko)
              for(var k = 0; k < 3; k++){
                //pridobi naključno stran in kliči api + 0.02 da je najmanj 1
               naključno = Math.floor((Math.random()+0.02) * 50)
                url = apiUrl + "?sfw=true&filter=bypopularity&page=" + naključno;
                
                  //kliči api in vrni response v obliki jsona
                  fetch(url).then(response => {
                      return response.json();
                    })
                    .then(data => {
                      podatki = data.data
                      
                     //ocena animeja
                      var score = 0;
                      for(var i = 0; i <  podatki.length; i++){
                        //ponastavi
                        score = 0;
                        //najprej prištej score iz apija, več kot ima uporabnik dodanih animejev manj tole vpliva
                        score += podatki[i].score
                        //prištej vpliv popularnosti, več kot ima uporabnik dodanih animejev manj tole vpliva
                        score += podatki[i].members/300000.0                
                        
                        //tole je potrebo če uporabnik še nima nič dodanega
                        if(jsoni.length != 0){
                          //pojde čes celotno tabelo dodanih jsonov
                            for(var q = 0; q < jsoni.length; q++){
                              
                              //prištej 1 če je studio katerega koli dodanega animeja enak trenutnem
                              if(podatki[i].studios[0].name == jsoni[q].studios[0].name) {
                               
                                score++;
                              }
                              //če je enaka vrsta source material dodaj 0.5
                              if(podatki[i].source == jsoni[q].source) {
                                score = score + 0.5;
                               
                              }
                             
                              //če je namenjen anime enakim osebam prištej 0.5
                              if( podatki[i].demographics.length != 0 && jsoni[q].demographics.length != 0){
                                if(podatki[i].demographics[0].name == jsoni[q].demographics[0].name) {
                                  score = score + 0.5;
                                 
                              }
                              }
                              //vsakič ko imata obravnavani anime in kateri koli dodani anime enako žanro 
                              for(var k = 0; k < jsoni[q].genres.length; k++){
                                for(var t = 0; t < podatki[i].genres.length; t++){
                                  if(jsoni[q].genres[k].name == podatki[i].genres[t].name){
                                    score = score + 0.5;
                                   
                                  }
                                }
                              }
                             
                            }
                            //od ocene odštej razliko med številom epizod animeja in povprečnim številom epizod dodanih animejev
                            if(podatki[i].episodes != null){
                            score -= Math.abs(meanEpisodes - podatki[i].episodes) * 0.1
                            
                          }
                        }
                         
                      //če ima uporabnik anime že dodan mu daj zelo nizek score, da ne bo predlagan
                      if(tabelaAnime.includes(String(podatki[i].mal_id))){
                        console.log(podatki[i].title_english)
                        score = - 500;
                        
                      }
                     
                      scores[i] = score;
                      
                      }
                      
                      //najde index največje vrednosti v tabeli in 7 največjih napiše v tabelo indeksi
                      for(var i = 0; i < 7;i++){
                          var max = indexNajvecjeVrednosti(scores)
                          indeksi[i] = max
                          scores[max] -= 1000;
                      }
                      

                      for(var m = 0; m < indeksi.length; m++){
                        //tukaj ta primerjava zato da že dodanih ne prikazuje
                        if(scores[indeksi[m]] > -1200){
                           //naredi nov animeDiv element
                          var newEl =  document.createElement("div")
                          newEl.className = "animeDiv"
                          //barve  ozadja se spreminjajo, vsaka druga
                          if(dodaniDivi%2 == 0) newEl.style.backgroundColor = "rgb(85, 85, 85)"
                          //naredi sliko za v div in ji daj source iz podatkov
                          var img = document.createElement("img")
                          img.className = "slikaGlavnaStran"
                          img.src = podatki[indeksi[m]].images.jpg.large_image_url
                          //naredi naslov za v div, ime iz podatkov
                          var h3 = document.createElement("h3")
                          h3.className = "naslovAnime"
                          h3.innerHTML = podatki[indeksi[m]].title_english
                          if(podatki[indeksi[m]].title_english == null){
                            h3.innerHTML =podatki[indeksi[m]].title
                            }
                          //za vse elemente spodnje elemnte podobno, vsak svoj class
                          var pocena = document.createElement("p")
                          pocena.className = "ocena"
                          pocena.innerHTML = "Ocena: " + podatki[indeksi[m]].score
                          var pgledalci = document.createElement("p")
                          pgledalci.className = "gledalci"
                          pgledalci.innerHTML = "Število gledalcev: " + podatki[indeksi[m]].members
                          //trailer podobno, le še nastavi se mu href, zato da je link do videa
                          var atrailer = document.createElement("a")
                          atrailer.className = "trailer"
                          atrailer.setAttribute("href",podatki[indeksi[m]].trailer.url)
                          atrailer.innerHTML = "Napovednik"
                          //žanrov je več zato je treba skozi celotno tabelo žanrov in dodati primerno vejice
                          var pgenre = document.createElement("p")
                          pgenre.className = "genre"
                          pgenre.innerHTML = "Žanri: "
                          for(var j = 0; j< podatki[indeksi[m]].genres.length;j++){
                          pgenre.innerHTML += podatki[indeksi[m]].genres[j].name
                          if(j != podatki[indeksi[m]].genres.length -1){
                                pgenre.innerHTML += ", "
                              } 
                            }
                            var pdatum = document.createElement("p")
                            pdatum.className = "datum"
                            pdatum.innerHTML = "Datum predvajanja: " + prevediDatum(podatki[indeksi[m]].aired.string);
                            var pdolzina = document.createElement("p")
                            pdolzina.className = "dolzina"
                            pdolzina.innerHTML = "Dolžina: " + podatki[indeksi[m]].episodes + " Epizod, " + prevediTrajanje(podatki[indeksi[m]].duration) ;

                            var gumbVnos = document.createElement("button")
                            gumbVnos.id = "gumb" + dodaniDivi;
                            gumbVnos.className = "gumbZaVnos"
                            gumbVnos.innerHTML = "DODAJ V SVOJ SEZNAM"

                            var gumbOdstrani = document.createElement("button")
                            gumbOdstrani.className = "gumbOdstrani"
                            gumbOdstrani.innerHTML = "ODSTRANI S SEZNAMA"
                            gumbOdstrani.id = "gumbOdstrani" + dodaniDivi;

                            const gumbZaVnosID = "gumb" + dodaniDivi;
                            const gumbOdstraniId = "gumbOdstrani" + dodaniDivi;

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
                            

                          //ustvari konstante ki bodo potrebne za prikaz podatkov v dodatnem pop up oknu vsakega animeja
                          var animeNaslov;
                          //ustvari konstante ki bodo potrebne za prikaz podatkov v dodatnem pop up oknu vsakega animeja
                          if(podatki[indeksi[m]].title_english == null){
                            animeNaslov =podatki[indeksi[m]].title
                          }
                          else {
                            animeNaslov = podatki[indeksi[m]].title_english
                                  }
                          const anmNaslov =animeNaslov;
                          const anmOpis = podatki[indeksi[m]].synopsis
                          const anmIMGsrc = podatki[indeksi[m]].images.jpg.large_image_url
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
                          const animeJson = podatki[indeksi[m]];
                          const animeApiID = podatki[indeksi[m]].mal_id;
                          gumbVnos.addEventListener("click", function(){
                      
                            var xhttp3 = new XMLHttpRequest();
                            xhttp3.onreadystatechange = function() {
                              if (this.readyState == 4 && this.status == 200) {
                              document.getElementById(gumbZaVnosID).style.display = "none";
                            document.getElementById(gumbOdstraniId).style.display = "block";
                              }
                            }
                            xhttp3.open("POST", "/Medved/php/beleziAnime.php?anime_api_ID=" + animeApiID, true);
                            xhttp3.setRequestHeader("Content-Type", "application/json");
                            xhttp3.send(JSON.stringify(animeJson).replace(/\\n/g, "").replace(/\\"/g, ""));
                          }); 
                          gumbOdstrani.addEventListener("click", function(){
                         
                          var xhttp4 = new XMLHttpRequest();
                          xhttp4.onreadystatechange = function() {
                            if (this.readyState == 4 && this.status == 200) {
                            document.getElementById(gumbZaVnosID).style.display = "block";
                          document.getElementById(gumbOdstraniId).style.display = "none";
                            }
                            }
                        
                          xhttp4.open("GET", "/Medved/php/odstraniAnime.php?anime_api_ID=" + animeApiID, true);
                          
                          xhttp4.send();
                        })
                          dodaniDivi++;
                        }

                    }
                        
                })

            }


          
         

      
  //ob kliku na križec se zapre okno z opisom anime-ja
  document.getElementById("zapriAnime").onclick = function(){
  document.getElementById("AnimePopUp").style.display ="none"
  }     
    
}
  

    </script>
  </body>
</html>
