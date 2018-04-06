console.log('Baaaaaaaaaaaaaaaaaaaananasplit !');
/*
  console.log(dataUser[0]); // children {obj}
  console.log(dataUser[1]); // portions [arr]
  console.log(dataUser[2]); // prix [arr]
  console.log(dataUser[3]); // $_SESSION['user_data'] {obj}
*/

window.onload = function(){
  insert_tribu_name();
  insert_children_buttons();
  day_form();
}

function save_panic(id){
  console.log();
}

function show(show){
  show.style.display = "block";
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
    html += "<form style='float:left' method='post' action=''><input onclick='get_my_form("+ e.id +")' style='whidth:100px; height:50px;' type='button' value='" + e.first_name +"'/></form>";
  });
  children_buttons_box.innerHTML = html;
}

function get_my_form(childId){
  if(childId === 0){

    show(panic_form);

    document.getElementById('last_name').value = "";
    document.getElementById('first_name').value = "";
    document.getElementById('school').value = "";
    document.getElementById('classroom').value = "";

  }
  else{

    document.getElementById('last_name').value = "";
    document.getElementById('first_name').value = "";
    document.getElementById('school').value = "";
    document.getElementById('classroom').value = "";

    var child;
    dataUser[0].forEach(function(e){
      if(Number(e.id) === childId){
        child = e;
      }
    });
    show(panic_form);

    document.getElementById('last_name').value = child.last_name;
    document.getElementById('first_name').value = child.first_name;
    document.getElementById('school').value = child.school;
    document.getElementById('classroom').value = child.classroom;

  }
}

function day_form(){
  if(typeof dataUser[0] !== "undefined"){
    dataUser[0].forEach(function(e){
      var table = document.getElementById('my_table');
      var tr = document.createElement('tr');
      tr.innerHTML = "<th>"+e.first_name+"</th><td><input type='checkbox' name='classique' />Classique <br/><input type='checkbox' name='fromage'  />Fromage <br/><input type='checkbox' name='halal'  />Halal</td><td><input type='radio' name='portion' value='S' />Benjamin <i style='font-size:0.8em'>(2 tartines)</i> <br/><input type='radio' name='portion' value='M' />Cadette <i style='font-size:0.8em'>(4 tartines)</i> <br/><input type='radio' name='portion' value='L' />Ainé <i style='font-size:0.8em'>(6 tartines)</i></td><td><input type='radio' name='fruit' value='oui' />Oui <br/><input type='radio' name='fruit' value='non' />Non</td>";
      table.appendChild(tr);
      show(commande);
    });
  }
}
