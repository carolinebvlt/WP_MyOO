console.log('Hello !');
console.log(dataUser);

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
