console.log('Bananasplit !');
/*
  console.log(dataUser[0]); // children {obj}
  console.log(dataUser[1]); // portions [arr]
  console.log(dataUser[2]); // prix [arr]
  console.log(dataUser[3]); // $_SESSION['user_data'] {obj}
*/

window.onload = function(){
  insert_tribu_name();
  insert_children_buttons();
}

function show_and_hide(show,hide){
  show.style.display = "block";
  hide.style.display = "none";
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
