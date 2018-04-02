console.log('Yo!!!!');
// console.log(dataUser[0]); // enfants
// console.log(dataUser[1]); // portions dispo
// console.log(dataUser[2]); // prix
// console.log(dataUser[3]); // $_SESSION user_data

var Total;

window.onload = function(){
  insert_tribu_name();
  insert_children_buttons();
  days_form();
}

window.addEventListener('click', function(e){

  if(e.target.value === 'on'){
    Total = 0 ;
    dataUser[0].forEach(function(e){
      test(e);
    })
    console.log(Total);
    document.getElementById('total').innerHTML = Total;
  }
})

function insert_tribu_name(){
  var tribu_name_box = document.getElementById('tribu_name');
  var html = dataUser[3].tribu;
  tribu_name_box.innerHTML = html;
}

function insert_children_buttons(){
  var children_buttons_box = document.getElementById('children_buttons');
  var html ="";
  dataUser[0].forEach(function(e){
    html += "<form style='float:left' method='post' action=''><input onclick='get_my_form("+ e[0].id +")' style='whidth:100px; height:50px;' type='button' value='" + e[0].first_name +"'/></form>";
  });
  children_buttons_box.innerHTML = html;
}

function days_form(){
  if(typeof dataUser[0][0] !== "undefined"){
    var table = document.getElementById('table_days');
    table.style.display = 'table';
    dataUser[0].forEach(function(e){
      // console.log(e[0].first_name);
      var tr = document.createElement('tr');
      var th = document.createElement('th');
      th.innerHTML = e[0].first_name;
      tr.appendChild(th);
      table.appendChild(tr);
      var td1 = document.createElement('td');
      var td2 = document.createElement('td');
      var td3 = document.createElement('td');
      var td4 = document.createElement('td');
      var td5 = document.createElement('td');
      var td6 = document.createElement('td');
      td1.innerHTML = "<input type='checkbox' class='days' id='"+ e[0].id +"_td1' />";
      td2.innerHTML = "<input type='checkbox' class='days' id='"+ e[0].id +"_td2'/>";
      td3.innerHTML = "<input type='checkbox' class='days' id='"+ e[0].id +"_td3'/>";
      td4.innerHTML = "<input type='checkbox' class='days' id='"+ e[0].id +"_td4'/>";
      td5.innerHTML = "<input type='checkbox' class='days' id='"+ e[0].id +"_td5'/>";
      td6.innerHTML = e[1].portion;
      tr.appendChild(td1);
      tr.appendChild(td2);
      tr.appendChild(td3);
      tr.appendChild(td4);
      tr.appendChild(td5);
      tr.appendChild(td6);

    })
  }
}


function test(e){
  // console.log(dataUser[2].L_1j);

  // console.log(document.getElementById(e[0].id + '_td1'));

  var count = 0;
  if(document.getElementById(e[0].id + '_td1').checked === true){
    count += 1;
  }
  if(document.getElementById(e[0].id + '_td2').checked === true){
    count += 1;
  }
  if(document.getElementById(e[0].id + '_td3').checked === true){
    count += 1;
  }
  if(document.getElementById(e[0].id + '_td4').checked === true){
    count += 1;
  }
  if(document.getElementById(e[0].id + '_td5').checked === true){
    count += 1;
  }
  // nombre de tartines et portion => prix
  switch (e[1].portion) {
    case 'S':
      switch (count) {
        case 0: break;
        case 1 : Total = Total + dataUser[2].S_1j; break;
        case 2 : Total = Total + dataUser[2].S_2j; break;
        case 3 : Total = Total + dataUser[2].S_3j; break;
        case 4 : Total = Total + dataUser[2].S_4j; break;
        case 5 : Total = Total + dataUser[2].S_5j; break;
      }
      break;

    case 'M':
      switch (count) {
        case 0: break;
        case 1 : Total = Total + dataUser[2].M_1j; break;
        case 2 : Total = Total + dataUser[2].M_2j; break;
        case 3 : Total = Total + dataUser[2].M_3j; break;
        case 4 : Total = Total + dataUser[2].M_4j; break;
        case 5 : Total = Total + dataUser[2].M_5j; break;
      }
      break;
    case 'L':
      switch (count) {
        case 0: break;
        case 1 : Total = Total + dataUser[2].L_1j; break;
        case 2 : Total = Total + dataUser[2].L_2j; break;
        case 3 : Total = Total + dataUser[2].L_3j; break;
        case 4 : Total = Total + dataUser[2].L_4j; break;
        case 5 : Total = Total + dataUser[2].L_5j; break;
      }
      break;
    default: console.log("OMG pas d'info !");break;
  }

}

function get_my_form(childId){
  if(childId === 0){
    show(data_child_form);

    document.getElementById('last_name').value = "";
    document.getElementById('first_name').value = "";
    document.getElementById('school').value = "";
    document.getElementById('classroom').value = "";

    var node_list = document.getElementsByTagName('input');

    for (var i = 0; i < node_list.length; i++) {
      var node = node_list[i];
      if ( (node.getAttribute('type') == 'checkbox') || (node.getAttribute('type') == 'radio') ) {
        node.checked = false;
      }
    }
  }
  else{
    document.getElementById('last_name').value = "";
    document.getElementById('first_name').value = "";
    document.getElementById('school').value = "";
    document.getElementById('classroom').value = "";

    var node_list = document.getElementsByTagName('input');

    for (var i = 0; i < node_list.length; i++) {
      var node = node_list[i];
      if ( (node.getAttribute('type') == 'checkbox') || (node.getAttribute('type') == 'radio') ) {
        node.checked = false;
      }
    }
    var child;
    dataUser[0].forEach(function(e){
      if(Number(e[0].id) === childId){
        child = e;
      }
    });
    show(data_child_form);
    /*
      child[0] = infos
      child[1] = pref
      child[2] = likes
      child[3] = dislikes
    */
    document.getElementById('last_name').value = child[0].last_name;
    document.getElementById('first_name').value = child[0].first_name;
    document.getElementById('school').value = child[0].school;
    document.getElementById('classroom').value = child[0].classroom;

    for(pref in child[1]){
      if(typeof document.getElementsByName(pref)[0] !== 'undefined'){
        if(pref === "fruit"){
          if(child[1][pref] === "1"){
            document.getElementsByName(pref)[0].checked = true;
          }
          else{
            document.getElementsByName(pref)[1].checked = true;
          }
        }
        if(pref === "portion"){
          document.getElementsByName('portion').forEach(function(e){
            if(e.value === child[1][pref]){
              e.checked = true;
            }
          });
        }
      }
    }

    for(pref in child[2]){
      if(typeof document.getElementsByName(pref)[0] !== 'undefined'){
        if(child[2][pref] === "1"){
          document.getElementsByName(pref)[0].checked = true ;
        }
      }
    }

    for(pref in child[3]){
      if(typeof document.getElementsByName(pref)[0] !== 'undefined'){
        if(child[3][pref] === "1"){
          document.getElementsByName(pref)[0].checked = true ;
        }
      }
    }
  }


}

function show_and_hide(show,hide){
  show.style.display = "block";
  hide.style.display = "none";
}

function show(show){
  show.style.display = "block";
}
