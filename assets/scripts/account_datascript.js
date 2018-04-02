console.log('Yo!!!!');
// console.log(dataUser[0]); // enfants
// console.log(dataUser[1]); // portions dispo
// console.log(dataUser[2]); // prix
// console.log(dataUser[3]); // $_SESSION user_data

window.onload = function(){
  insert_tribu_name();
  insert_children_buttons();
  days_form();
}

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
      td1.innerHTML = "<input type='checkbox' />";
      td2.innerHTML = "<input type='checkbox' />";
      td3.innerHTML = "<input type='checkbox' />";
      td4.innerHTML = "<input type='checkbox' />";
      td5.innerHTML = "<input type='checkbox' />";
      tr.appendChild(td1);
      tr.appendChild(td2);
      tr.appendChild(td3);
      tr.appendChild(td4);
      tr.appendChild(td5);
    })
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



// public function get_days_form(){
//   $children = $this->users_manager->get_children();
//   $days_forms;
//   foreach ($children as $child) {
//     $days_forms = $days_forms.
//                   "<tr>
//                       <th>".$child->first_name."</th>
//                       <td><input type='checkbox' name='lundi' /></td>
//                       <td><input type='checkbox' name='mardi' /></td>
//                       <td><input type='checkbox' name='mercredi' /></td>
//                       <td><input type='checkbox' name='jeudi' /></td>
//                       <td><input type='checkbox' name='vendredi' /></td>
//                     </tr>";
//   }
//   return $days_forms;
// }
