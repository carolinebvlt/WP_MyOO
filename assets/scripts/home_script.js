console.log('hiiii');

var D = new Date(); 
var d = D.getDay();

var lundiPro = new Date();
var vendrediPro = new Date();

switch (d) {
  case 1: lundiPro.setDate(D.getDate()+7); break; // lundi
  case 2: lundiPro.setDate(D.getDate()+6); break; // mardi
  case 3: lundiPro.setDate(D.getDate()+5); break; // mercredi
  case 4: lundiPro.setDate(D.getDate()+4); break; // jeudi
  case 5: lundiPro.setDate(D.getDate()+3); break; // vendredi
  case 6: lundiPro.setDate(D.getDate()+9); break; // samedi
  case 0: lundiPro.setDate(D.getDate()+8); break; // dimanche
}
vendrediPro.setDate(lundiPro.getDate()+4);

var l = get_monthFR(lundiPro.getMonth());
var v = get_monthFR(vendrediPro.getMonth());

var nextPanic = new Date();

switch (d) {
  case 5:
    if (D.getHours() < 8){ nextPanic.setDate(D.getDate()); break; }
    else { nextPanic.setDate(D.getDate()+3); break;}

  case 6:
    nextPanic.setDate(D.getDate()+2) ;
    break;

  case 0:
    nextPanic.setDate(D.getDate()+1) ;
    break;

  case 1: case 2: case 3: case 4:
    if (D.getHours() < 8){ nextPanic.setDate(D.getDate()); break; }
    else { nextPanic.setDate(D.getDate()+1) ; break; }

}
var n_m = get_monthFR(nextPanic.getMonth());
var n_d = get_dayFR(nextPanic.getDay());

window.onload = function(){
  document.getElementById('p_abo').innerHTML = "Commander pour la semaine <br/> <b>du lundi "+lundiPro.getDate()+" "+l+"<br/> au vendredi "+vendrediPro.getDate()+" "+v+"</b>";
  document.getElementById('p_panic').innerHTML = "Vite ! <br/>Un pic-nic pour le <br/> <b>"+ n_d +" "+nextPanic.getDate()+" "+n_m+" !</b>";
}



function get_monthFR(day){
  switch (day) {
    case 0 : return 'janvier' ; break;
    case 1 : return 'février' ; break;
    case 2 : return 'mars' ; break;
    case 3 : return 'avril' ; break;
    case 4 : return 'mai' ; break;
    case 5 : return 'juin' ; break;
    case 6 : return 'juillet' ; break;
    case 7 : return 'août' ; break;
    case 8 : return 'septembre' ; break;
    case 9 : return 'octobre' ; break;
    case 10: return 'novembre' ; break;
    case 11: return 'décembre' ; break;
  }
}

function get_dayFR(day){
  switch (day) {
    case 1: return 'lundi' ; break;
    case 2: return 'mardi' ; break;
    case 3: return 'mercredi' ; break;
    case 4: return 'jeudi' ; break;
    case 5: return 'vendredi' ; break;
    case 6: return 'samedi' ; break;
    case 0: return 'dimanche' ; break;
  }
}
