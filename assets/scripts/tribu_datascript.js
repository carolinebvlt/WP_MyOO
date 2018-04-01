console.log('Hello !');
// console.log(dataUser);


window.onload = function get_children_buttons(){
  var children_buttons_box = document.getElementById('children_buttons');
  var html ="";
  dataUser.forEach(function(e){
    html += "<form style='float:left' method='post' action=''><input onclick='get_my_form("+ e[0].id +")' style='whidth:100px; height:50px;' type='button' value='" + e[0].first_name +"'/></form>";
  });
  children_buttons_box.innerHTML = html;
}

function get_my_form(childId)
{
  var child;
  dataUser.forEach(function(e){
    if(Number(e[0].id) === childId){
      child = e[0];
    }
  });
  show(data_child_form);
  document.getElementById('last_name').value = child.last_name;
  document.getElementById('first_name').value = child.first_name;
  document.getElementById('school').value = child.school;
  document.getElementById('classroom').value = child.classroom;
}


function show_and_hide(show,hide){
  show.style.display = "block";
  hide.style.display = "none";
}

function show(show)
{
  show.style.display = "block";
}


// public function get_children_buttons(){
//   $children = $this->users_manager->get_children();
//   $children_html;
//   foreach ($children as $child) {
//     $children_html = $children_html."
//       <form method='post' action=''>
//         <input type='hidden' name='id_child' value='".$child->id."' />
//         <input style='whidth:100px; height:50px;' type='submit' name='show_pref' value='".$child->first_name."'/>
//       </form>";
//   }
//   return $children_html;
// }

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
