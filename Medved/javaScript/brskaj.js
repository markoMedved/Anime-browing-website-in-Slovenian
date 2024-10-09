//menjava angleškega teksta ki pride v jsonu z slovenskim (obe funkciji)
function prevediTrajanje(tekst){
  for(var i = 0; i < tekst.length-2; i++){
    if(tekst.substring(i, i+3) == "per"){
      tekst = tekst.substring(0,i) + "na epizodo"
      return tekst;
    }
    
  }
  return tekst
}

function prevediDatum(tekst){
  for(var i = 0; i < tekst.length-3; i++){
    if(tekst.substring(i, i+4) == " to "){
      tekst = tekst.substring(0,i) + " do " + tekst.substring(i+4, tekst.length)
      return tekst;
    }
    
  }
  return tekst
}
