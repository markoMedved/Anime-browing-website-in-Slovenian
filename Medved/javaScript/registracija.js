//preveri če ima geslo vse potrebne znake
function preveriGeslo(input){
  var velika = false;
  var stevilka = false;
  var mala = false;

  for(var i = 0; i < input.length;i++){
    //če nekaj ni ne-številka 
    if(!isNaN(input[i])){
      stevilka = true;
    }
    else if(input[i].toUpperCase() == input[i]){
        velika= true;
    }
    
    else if(input[i].toLowerCase() == input[i]){
      mala = true;
      
    }
  }
  if(velika == true && mala == true && stevilka == true){
    return true;

  }
  return false;
}


//funcija za stalno preverjanje gesla ob fokusu na input() polje in izpis kakšno je stanje
function preverjajGeslo(){
  document.getElementById("geslo").oninput = function(){
    if(preveriGeslo(document.getElementById("geslo").value) == false){
      document.getElementById("nezadovoljivoGeslo").innerHTML = "Geslo mora vsebovati vsaj 1 veliko črko, 1 malo črko in 1 številko"
      document.getElementById("nezadovoljivoGeslo").style.color = "red"
      
    }
    else{
      document.getElementById("nezadovoljivoGeslo").innerHTML = "Primerno geslo"
      document.getElementById("nezadovoljivoGeslo").style.color = "lightgreen"
        
    }
  }
  
}