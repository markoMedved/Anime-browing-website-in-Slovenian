<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Brskanje</title>
  <link rel="stylesheet" type="text/css" href="/Medved/css/1.css" />
  <link rel="stylesheet" type="text/css" href="/Medved/css/brskaj.css" />
  <script src="/Medved/javaScript/1.js"></script>
  <script src="/Medved/javaScript/brskaj.js"></script>
  <script src="/Medved/javaScript/jquerry.js"> </script>

</head>
<body class="telo">
  <!-- prva vrstica za prikaz ko je stran dovolj velika  --> 
<div id="vrsticaZgoraj" class = "navigacijskaVrstica">
  <a class="trenutna" href="/Medved/podstrani/brskaj.php">BRSKAJ</a>
  <a  href="/Medved/podstrani/isci.php">IŠČI</a>
  <a  href="/Medved/podstrani/priljubljeno.php">SEZNAM</a>
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
  
<!-- centralni div zasega celotno stran razen zgornje vrstice-->
<div id="centerDiv" class="centralniDiv">
  <!--Vrstica z naslovom in obema filtroma -->
  <div class="vrsticaBrskaj">
    <p id="izbranoSortiranje" class="naslov">Najbolje ocenjeno</p>
    <a class="sortiraj" id="sort">SORTIRAJ</a>
    <!--tole je pop up okno  za izbranje sortiranja-->
    <div class="popUp" id="sortirajPopUp">
        <p class="naslovPopUp">SORTIRAJ PO:</p>
        <button id="zapriSortiraj" class="closeButton">X</button>
          <a id="najOcenjeno" class="sortirajGumb">NAJBOLJE OCENJENO</a> <br>
          <a id="najPriljubljeno" class="sortirajGumb">NAJBOLJ POPULARNO</a><br>
          <a id="najPriljubljenoPrihajajoce" class="sortirajGumb">TRENUTNO NAJBOLJŠE</a> 
    
    </div>
    <a class="filtriraj" id="filter">FILTER</a>
    <!--tole je pop up okno  za filtriranje iskanja-->
    <div class="popUp" id="filtrirajPopUp">
      <p class="naslovPopUp">IZBERI ŽANRE</p>
      <button id="zapriFilter" class="closeButton">X</button>
      <div class="checkBox">
        <label for="Action">Akcija</label>
        <input id="Action" type="checkbox"> <br>
        <label for="Comedy">Komedija</label>
        <input id="Comedy" type="checkbox"><br>
        <label for="Drama">Drama</label>
        <input id="Drama" type="checkbox"><br>
        <label for="Fantasy">Fantazija</label>
        <input id="Fantasy" type="checkbox"><br>
        <label for="Sports">Šport</label>
        <input id="Sports" type="checkbox"><br>
        <label for="Romance">Romantika</label>
        <input id="Romance" type="checkbox"><br><br>
        <a id="potrdi" class="potrdiGumb">POTRDI</a>
      </div>
     
  </div>
  <!--tole je pop up okno  za prikaz opisa serije-->
  <div class="popUpAnime" id="AnimePopUp">
    <img id="AnimePopUpImg">
    <p id="AnimePopUpNaslov" class="naslovPopUpAnime"></p>
    <button id="zapriAnime" class="closeButton">X</button>
      <p class="opisVanglescini">Opis v angleščini: </p>
      <p id="opis" class="animeOpis"></p>
</div>


 
</div>
 <!--tole je gumb za prikaz več serij na dnu-->
<button id="vec" class="gumbVec">VEČ ></button>
</div>

<?php 
require "phpRequire/vpisan.php";
?>
  
<?php require 'phpRequire/pridobiTabeloAnimeID.php'; ?>

    <script>
      //pridobi tabelo z anime Id-ji (stringi ne številke)
     var tabelaAnime = <?php echo json_encode($tabelaAnime1); ?>;
    //števec za togglanje dropdowna
    var števec = 0;
    //na začetuku že nastavi kateri stil zgornje vrstice je potreben
    nastaviStilZgornjeVrstice()
    //če se spremeni velikost okna skrij prvo vrsticozgoraj in odpri drugo definicijo vrstice
    window.addEventListener('resize', function() {
      nastaviStilZgornjeVrstice()
    });
  


//url za api 
const apiUrl = 'https://api.jikan.moe/v4/top/anime';
//dodaj takoj parameter ki je vedno prisoten
var url = apiUrl + "?sfw=true";
//parameter za to katero stran anime-jev kličeš
var parStr = "&page="
//števec ki šteje na kateri strani animejeve si
var zacetno = 1;
//pozicijo gumba, ko še ni nobenega anime-ja dodanega 
var zacetnaPozicija =170;
//koliko anime divov bo dodanih z enim api klicem
var dodaniDivi = 0
//koliko je prikazanih trenutno anime divov
var trenutniDivi = 0



//checkboxi, za filtriranje brskanja
var checkboxi = [document.getElementById("Action"), document.getElementById("Comedy") ,document.getElementById("Drama"),document.getElementById("Sports"), document.getElementById("Fantasy"), document.getElementById("Romance")]
//nastavi na začetku vse checkboxe na false, da na začetku ni filtriranja
for(var i = 0;i < checkboxi.length;i++){
  checkboxi[i].checked = false;
}
//boolean za filtriranje(postavi se na true če nek anime vsebuje katerega od nastavljenih žanrov)
var vsebujeZanr;

//kliči api in vrni response v obliki jsona
fetch(url).then(response => {
    return response.json();
  })
  
  .then(data => {
    
    //zapiši vse potrebne podate iz jsona v spremenljivko podatki
    var podatki = data.data
  
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
        h3.innerHTML =podatki[i].title
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
      gumbVnos.id = "gumb" + trenutniDivi + i;
      gumbVnos.className = "gumbZaVnos"
      gumbVnos.innerHTML = "DODAJ V SVOJ SEZNAM"

      var gumbOdstrani = document.createElement("button")
      gumbOdstrani.className = "gumbOdstrani"
      gumbOdstrani.innerHTML = "ODSTRANI S SEZNAMA"
      gumbOdstrani.id = "gumbOdstrani" + trenutniDivi + i;
      
    
      const gumbZaVnosID = "gumb" + trenutniDivi + i;
      const gumbOdstraniId = "gumbOdstrani" + trenutniDivi + i;

      //doaj vse ustvarjene elemnte v anime div
      newEl.appendChild(img)
      newEl.appendChild(h3)
      newEl.appendChild(pocena)
      newEl.appendChild(pgledalci)
      newEl.appendChild(atrailer)
      newEl.appendChild(pgenre)
      newEl.appendChild(pdolzina)
      newEl.appendChild(pdatum)
      //če je uporabnik vpisan dodaj primeren gumb
      if(vpisan) {
        //če je v tabeli z anime Id-ji že ta anime potem prikaži gumb za dodajanje drugače pa za odstranjevanje
        if(tabelaAnime.includes(String(podatki[i].mal_id))){
            gumbVnos.style.display = "none";
           gumbOdstrani.style.display = "block";
        }
      newEl.appendChild(gumbOdstrani);
      newEl.appendChild(gumbVnos);
      }
      //dodaj anime div v centralni div strani
      document.getElementById("centerDiv").appendChild(newEl)
  
      
      var animeNaslov;
      //ker je včasih anleški naslov null   
       if(podatki[i].title_english == null){
            animeNaslov =podatki[i].title
          }
      else {
            animeNaslov = podatki[i].title_english
          }
      //konstante  za popUp okno, da se ne spremenijo 
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
      //konstante jsonov, in idjev, ki se vpišejo v bazo ob kliku na gumb dodaj ali zbrišejo ob gumbu odstrani
      const animeJson = podatki[i];
      const animeApiID = podatki[i].mal_id;
      //ob pritiskku na gumb Dodaj
      gumbVnos.addEventListener("click", function(){
       
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
        xhttp2.send(JSON.stringify(animeJson).replace(/\\n/g, "").replace(/\\"/g, ""));
      }); 
      //ob pritisku na gumb odstrani odstrani anime iz baze(le iz relacijske tabele)
      gumbOdstrani.addEventListener("click", function(){
        //spet zamenjaj gumba
       
        //ajax request
        var xhttp3 = new XMLHttpRequest();
        xhttp3.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
          document.getElementById(gumbZaVnosID).style.display = "block";
          document.getElementById(gumbOdstraniId).style.display = "none"
        }
        }
        //dodaj animeapiID da ve katerega je treba zbrisat
        xhttp3.open("GET", "/Medved/php/odstraniAnime.php?anime_api_ID=" + animeApiID, true);
        xhttp3.send();
      })
      //povečaj vsakič dodaniDiv za 1, pomembno za prikaz gumba spodaj in drugo
      dodaniDivi++;

    }
    //prištej število dodatnih Divov h trenutnem število divov
    trenutniDivi += dodaniDivi
    //premakni gumb za dodajanje prikaza bolj dolj za toliko divov kot jih je trenutno
    document.getElementById("vec").style.top = zacetnaPozicija + trenutniDivi*175 + "px"
    //postavi dodani Divi na 0(vrednost pomembna zaradi filtrov)
    dodaniDivi = 0
    //povečaj za 1, da ob naslednjem klicu kličeš naslednjo stran
    zacetno++;
  })


//s tem klikom na gumb se ponovno zgodi api call
document.getElementById("vec").onclick = function(){

  fetch(url + parStr + zacetno)
  .then(response => {
    return response.json();
  })
  .then(data => {
    var podatki = data.data
  

    for(var i = 0; i <  podatki.length; i++){
      //števec, ki zagotavlja da če ni filtrov označenih prikaže vse aninme-je
      var cnt = 0
      //postavi na false na začetku
      vsebujeZanr = false
      //pojdi skozi celo tabelo podatkov in preglej vse žanre trenutnga anime-ja in preveri če je kateri enak kakšnemu od žanrov označenih v filtru
     zunanjiLoop: for(var j = 0; j< podatki[i].genres.length;j++){
        cnt = 0
        //ta loop vsakič ko je kakšen checkbox ne izpolnjen poveča cnt za 1
        for(var k = 0; k < 6; k++){
          if(checkboxi[k].checked == false){
            cnt++;
          }
          //če so bili vsi neizpoljnjei bo cnt 6, ni filtriranja, pojdi ven z loopa za ta anime
          if(cnt == 6){
            vsebujeZanr = true;
            break zunanjiLoop
          }
          //če ima anime žanr ki je označen v kakšnem checkboxu, daj boolean na true, in bo prikazan na strani
          if(podatki[i].genres[j].name == checkboxi[k].id && checkboxi[k].checked){
            vsebujeZanr = true;
            break zunanjiLoop
          }

        }
      }

      //koda se izvede samo če je bila vsebovana vsaj ena žanra,drugače edina sprememba od zgoraj je le v togglanju barv
      if(vsebujeZanr){

      var newEl =  document.createElement("div")
      newEl.className = "animeDiv"
      //tukaj je sedaj bolj kompleksno togglanje saj je odvisno od tega koliko je bilo dodanih in koliko je trenutnih divov
      if((dodaniDivi + trenutniDivi%2)%2 == 0) newEl.style.backgroundColor = "rgb(85, 85, 85)"
      var img = document.createElement("img")
      img.className = "slikaGlavnaStran"
      img.src = podatki[i].images.jpg.large_image_url
      var h3 = document.createElement("h3")
      h3.className = "naslovAnime"
      h3.innerHTML = podatki[i].title_english
      if(podatki[i].title_english == null){
        h3.innerHTML = podatki[i].title
      }
      var pocena = document.createElement("p")
      pocena.className = "ocena"
      pocena.innerHTML = "Ocena: " + podatki[i].score
      var pgledalci = document.createElement("p")
      pgledalci.className = "gledalci"
      pgledalci.innerHTML = "Število gledalcev: " + podatki[i].members
      var atrailer = document.createElement("a")
      atrailer.className = "trailer"
      atrailer.setAttribute("href",podatki[i].trailer.url)
      atrailer.innerHTML = "Napovednik"
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
      pdolzina.innerHTML = "Dolžina: " + podatki[i].episodes + " Epizod, " +prevediTrajanje(podatki[i].duration) ;

      var gumbVnos = document.createElement("button")
      gumbVnos.id = "gumb" + trenutniDivi + i;
      gumbVnos.className = "gumbZaVnos"
      gumbVnos.innerHTML = "DODAJ V SVOJ SEZNAM"

      var gumbOdstrani = document.createElement("button")
      gumbOdstrani.className = "gumbOdstrani";
      gumbOdstrani.innerHTML = "ODSTRANI S SEZNAMA"
      gumbOdstrani.id = "gumbOdstrani" + trenutniDivi + i;
      

      
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
      h3.addEventListener("click", function(){
        document.getElementById("AnimePopUp").style.display ="block"
        document.getElementById("AnimePopUpNaslov").innerHTML = anmNaslov
        document.getElementById("opis").innerHTML =anmOpis
        document.getElementById("AnimePopUpImg").src =  anmIMGsrc 
      });
      img.addEventListener("click", function(){
        document.getElementById("AnimePopUp").style.display ="block"
        document.getElementById("AnimePopUpNaslov").innerHTML = anmNaslov
        document.getElementById("opis").innerHTML = anmOpis
        document.getElementById("AnimePopUpImg").src =  anmIMGsrc 
      }); 
      const animeJson = podatki[i];
      const animeApiID = podatki[i].mal_id;
      const gumbZaVnosID = "gumb" + trenutniDivi + i;
      const gumbOdstraniId = "gumbOdstrani" + trenutniDivi + i;
      gumbVnos.addEventListener("click", function(){
      
       var xhttp4 = new XMLHttpRequest()
       xhttp4.onreadystatechange = function() {
           //spremeni v gumb odstran
           if (this.readyState == 4 && this.status == 200) {
        document.getElementById(gumbZaVnosID).style.display = "none";
        document.getElementById(gumbOdstraniId).style.display = "block";
           }
        }
        xhttp4.open("POST", "/Medved/php/beleziAnime.php?anime_api_ID=" + animeApiID, true);
        xhttp4.setRequestHeader("Content-Type", "application/json");
        xhttp4.send(JSON.stringify(animeJson).replace(/\\n/g, "").replace(/\\"/g, ""));
      }); 

      gumbOdstrani.addEventListener("click", function(){
        
        var xhttp5 = new XMLHttpRequest();
        xhttp5.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
          document.getElementById(gumbZaVnosID).style.display = "block";
          document.getElementById(gumbOdstraniId).style.display = "none";
          }
        }
       
        xhttp5.open("GET", "/Medved/php/odstraniAnime.php?anime_api_ID=" + animeApiID);
        
        xhttp5.send();
      })
  
      
      newEl.appendChild(img)
      newEl.appendChild(h3)
      newEl.appendChild(pocena)
      newEl.appendChild(pgledalci)
      newEl.appendChild(atrailer)
      newEl.appendChild(pgenre)
      newEl.appendChild(pdolzina)
      newEl.appendChild(pdatum)
      if(vpisan) {
        if(tabelaAnime.includes(String(podatki[i].mal_id))){
            gumbVnos.style.display = "none";
           gumbOdstrani.style.display = "block";
        }
      newEl.appendChild(gumbOdstrani);
      newEl.appendChild(gumbVnos);
      }
      document.getElementById("centerDiv").appendChild(newEl)
      dodaniDivi++;
      }

    }
    trenutniDivi += dodaniDivi
    document.getElementById("vec").style.top = zacetnaPozicija + trenutniDivi*175 + "px"
    dodaniDivi = 0
    zacetno++;
  })

  }


  


//ob kliku na gumb sortiraj odpri okno za odločitev kako hočeš sortirat
document.getElementById("sort").onclick = function(){
  document.getElementById("sortirajPopUp").style.display ="block"
}

//ob kliku na gumb filter odpri okno za nastavljanje filtrov
document.getElementById("filter").onclick = function(){
  document.getElementById("filtrirajPopUp").style.display ="block"
}

//ob pritisku na križec na oknu za sortiranje, zapri le tega
document.getElementById("zapriSortiraj").onclick = function(){
  document.getElementById("sortirajPopUp").style.display ="none"
}

//enako križec za okno za filtriranje
document.getElementById("zapriFilter").onclick = function(){
  document.getElementById("filtrirajPopUp").style.display ="none"
}


//ob pritisku na tipko za sortiranje po oceni
document.getElementById("najOcenjeno").onclick = function(){
  //zapri okno
  document.getElementById("sortirajPopUp").style.display ="none"
  //pobriši vse dive ki so bili trenutno prikazani
  for(var i = 0; i < trenutniDivi; i++){
    document.getElementById("centerDiv").removeChild(document.getElementById("centerDiv").lastChild)
  }
  //vedno potreben parameter
  url = apiUrl  + "?sfw=true"
  //da se bo prikazala spet prva stran 
  zacetno = 1;
  //spremeni naslov kjer piše vrsta sortiranja na najbolje ocenjeno
  document.getElementById("izbranoSortiranje").innerHTML = "Najbolje ocenjeno"
  //trenutno število divov postavi na 0
  trenutniDivi = 0
  //simuliraj klik da se bo izvedel ponovni klic api-ja
   //ker se spremenijo anime-ji dodani ni pa nobenega reloada
var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            //posodobljena tabela dodanih animejev
            tabelaAnime = this.response;
          
          console.log(tabelaAnime)
          document.getElementById("vec").click();
          }
          
        }
        //dodaj animeapiID da ve katerega je treba zbrisat
xhttp.open("GET", "/Medved/php/pridobiTabeloAnime.php", true);
xhttp.send();
 
}
//podobno le za sortiranje po priljubljenosti 
document.getElementById("najPriljubljeno").onclick = function(){
  document.getElementById("sortirajPopUp").style.display ="none"
  for(var i = 0; i < trenutniDivi; i++){
    document.getElementById("centerDiv").removeChild(document.getElementById("centerDiv").lastChild)
  }
  //tukaj je potrebno dodati dodaten parameter, default je nastavljeno da sortirajo po oceni
  url = apiUrl + "?sfw=true" + "&filter=bypopularity"
  zacetno = 1;
  document.getElementById("izbranoSortiranje").innerHTML = "Najbolj popularno"
  trenutniDivi = 0
   //ker se spremenijo anime-ji dodani ni pa nobenega reloada
var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            tabelaAnime = this.response;
          
          console.log(tabelaAnime)
          document.getElementById("vec").click();
          }
          
        }
        //dodaj animeapiID da ve katerega je treba zbrisat
xhttp.open("GET", "/Medved/php/pridobiTabeloAnime.php", true);
xhttp.send();

}
//podobno
document.getElementById("najPriljubljenoPrihajajoce").onclick = function(){
  document.getElementById("sortirajPopUp").style.display ="none"
  for(var i = 0; i < trenutniDivi; i++){
    document.getElementById("centerDiv").removeChild(document.getElementById("centerDiv").lastChild)
  }
  //spet drug nastavljen parameter
  url = apiUrl  + "?sfw=true"+ "&filter=airing"
  zacetno = 1;
  document.getElementById("izbranoSortiranje").innerHTML = "Trenutno najboljše"
  trenutniDivi = 0
  
//ker se spremenijo anime-ji dodani ni pa nobenega reloada
var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            tabelaAnime = this.response;
          
          console.log(tabelaAnime)
          document.getElementById("vec").click();
          }
          
        }
        //dodaj animeapiID da ve katerega je treba zbrisat
xhttp.open("GET", "/Medved/php/pridobiTabeloAnime.php", true);
xhttp.send();
}
  



//ob pritisku na gumb potrdi (okno za filtriranje)
document.getElementById("potrdi").onclick = function(){
//zapri okno za filtre
document.getElementById("filtrirajPopUp").style.display ="none"
//pobriši vse sedaj prikazane dive
for(var i = 0; i < trenutniDivi; i++){
    document.getElementById("centerDiv").removeChild(document.getElementById("centerDiv").lastChild)
}
//postavi na 1 da bo spet request za prvo stran, in št trenutnih divov na 0
zacetno = 1;
trenutniDivi = 0

//ker se spremenijo anime-ji dodani ni pa nobenega reloada
var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
            tabelaAnime = this.response;
          
          console.log(tabelaAnime)
          document.getElementById("vec").click();
          }
          
        }
        //dodaj animeapiID da ve katerega je treba zbrisat
xhttp.open("GET", "/Medved/php/pridobiTabeloAnime.php", true);
xhttp.send();




}

//ob kliku na križec se zapre okno z opisom anime-ja
document.getElementById("zapriAnime").onclick = function(){
  document.getElementById("AnimePopUp").style.display ="none"
}



    </script>
  </body>
</html>

