//iz stringa v jsonu ki vsebuje različne stvari izloči št epizod
function pridobiStEpizod(str){
  var numStr = str.substring(0,2)
  //pretvori v številko
  return Number(numStr);
}

//izračuna element v tabeli ki se največkrat ponovi in ga vrne
function maxPonovitev(tabela){
  var mostRepeated;
  var maxcount =0;
  var stevec = 0;
  //dva loopa za iskanje ponovitev vseh žanrov
  for(var i = 0; i < tabela.length; i++){
    for(var j = 0; j < tabela.length; j++){
        //če najdeš da se nekaj ponovi in ni checked(že preverjeno) povečaj števec
       if(tabela[j] == tabela[i] && tabela[i] != "checked"){
        stevec++;
       }
    }
    //če je števec večji od max vrednosti daj max na ta števec in daj največkrat ponovljeno vrednost na ta indeks
    if (stevec > maxcount){
      maxcount = stevec;
      mostRepeated = tabela[i]
    }
    stevec = 0;
  }
 return mostRepeated;
}

//elemente v tabelah za animeji da na checked zato da zgornja funkcija deluje pravilno
function zavrziElemente(el, tabela){
  for(var i = 0; i < tabela.length; i++){
    
    if(tabela[i] == el){
      //zato da je nekaj kar se ne bo več ponavljalo
      tabela[i] = "checked";
    }
  }
}

//funkcija za animacijo slik 
function test(){
  //premakni source vedno za eno naprej
  document.getElementById("animeSlike").src = slikeSrc[count]
  count++;
  if(count >= slikeSrc.length){
    //če smo prišli do konca slik daj nazaj na 0 - spet od začetka
    count = 0;
  }
}


//funkcije za animacijo pikachuja, spreminja se pozicija vedno in določen je čas ki se ga porabi za animacijo
function test2(){
  //obrni pikachu-ja da teče v drugo smer
  document.getElementById("pikachuSlika").src = "/Medved/slike/flippedPikachu.gif"
  $("#pikachuSlika").animate({left: "0px"}, 1000)
  //po določenem času naredi funkcijo test3
  window.setTimeout(test3, 1000)
 
}

function test3(){
  //zamrzni pikachuja v sliko
  document.getElementById("pikachuSlika").src = "/Medved/slike/flippedPikachu.png"
  //povečaj pikachu števec da bo naslednjič druga animacija
  pikachuŠtevec++;
  //postavi na false da se lahko zgodi naslednja animacija
  trenutnoAnimacija = false;
  
}

function test4(){
  document.getElementById("pikachuSlika").src = "/Medved/slike/pikachu.gif"
  $("#pikachuSlika").animate({left: "-1200px", top:"100px"}, 1000)
  window.setTimeout(test5, 1000)
}

function test5(){
  document.getElementById("pikachuSlika").src = "/Medved/slike/pikachu.png"
  pikachuŠtevec++;
  trenutnoAnimacija = false;
}

function test6(){
  document.getElementById("pikachuSlika").src = "/Medved/slike/pikachu.png"
  pikachuŠtevec++;
  trenutnoAnimacija = false;
}