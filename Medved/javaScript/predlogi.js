//funkcija vrne index najvecje vrednosti v tabeli
function indexNajvecjeVrednosti(tabela){
  var max = 0;
  for(var i = 0; i < tabela.length; i++){
    if(tabela[i] > tabela[max]){
      max = i;
    }
  }
  return max
}


