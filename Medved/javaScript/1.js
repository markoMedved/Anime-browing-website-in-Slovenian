//tukaj so funkcije ki so skupne večim stranem


//sestavljanje zgornje vrstice glede na overflow
function nastaviStilZgornjeVrstice(){
  //vse možne vrstice 
  var vrsticaPrvotna = document.getElementById("vrsticaZgoraj");
  var vrsticaTretja = document.getElementById("vrsticaZgoraj3")

  if(window.innerWidth < 700){
  //če je overflow potem postavi celotno 1. možnost vrstice , da se ne prikazuje
    vrsticaPrvotna.style.display = 'none';

  //postavi drugo možnost vrstice da se 
  vrsticaTretja.style.display = 'block';

}
  //če overflowa ni več potem spremeni spet nazaj na prvotno obliko vrstice po obratnem postopku
  else if (window.innerWidth > 700) {
    vrsticaPrvotna.style.display = 'flex';

  vrsticaTretja.style.display = 'none';
  }

}


//funkcija ki menja da se prikaz dropdowna prikaze ali skrije
function uporabiDropdown() {
  var dropdown = document.getElementById("Dropdown");
  //vse otroke, ki so html elementi pobriši če je števec deljiv z dva(števec je globalna spremenljivka v html straneh), drugače pa jih prikaži vertikalno
  for(var i = 0; i < dropdown.childNodes.length; i++){
    var child = dropdown.childNodes[i];
    if (child.nodeType === Node.ELEMENT_NODE) { 
      if(števec%2==1){
        child.style.display = "none";
      }
      else{
        child.style.display = "block";
      }
    }
  }
 števec++;
}

//funkcija, ki skrije geslo ob pritisku na gumb, in obratno
//tlele je bug notr
function skrijGeslo(){
  //ko je fokus pokaži tekst 
  document.getElementById("geslo").onfocus = function(){
    //na novo popravljeno od oddaje
    if(document.getElementById("prikazGesla").innerHTML == "" && cnt%2==0){
      document.getElementById("prikazGesla").innerHTML = "PRIKAŽI"
    }
    else{
      document.getElementById("prikazGesla").innerHTML = "SKRIJ"
    }

  }
  //ko gre ven iz fokusa, če je prazno teksta ne pokaži
  document.getElementById("geslo").onblur = function(){
  
    if(document.getElementById("geslo").value ==""){
      document.getElementById("prikazGesla").innerHTML = ""
    }
  }
  
  
  //ob pritisku funkcije togglaj skrij/prikaži geslo (cnt je globalna spremenljivka v html-jih)
  document.getElementById("prikazGesla").onclick = function(){
    document.getElementById("geslo").focus()
    if (cnt%2==0){
      document.getElementById("geslo").type = "text"
      document.getElementById("prikazGesla").innerHTML = "SKRIJ"
      
    }
    else{
      document.getElementById("geslo").type = "password"
      document.getElementById("prikazGesla").innerHTML = "PRIKAŽI"
    }
    cnt++;
  }
}