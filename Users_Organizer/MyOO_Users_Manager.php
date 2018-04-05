<?php
/*
  - Users
    - add_user()
    - get_user($info) - id ou email
    - get_all_users()
  - Children
    - add_child()
    - get_child($id)
    - child_exists($first_name, $last_name)
    - update_child($id)
    - delete_child($id)
    - get_children() - $_SESSION['tribu']
  - Preferences
    - save_preferences($id) -> add/update -> child_params, likes, dislikes

*/
class MyOO_Users_Manager
{
  private $likes,
          $dislikes,
          $child_params;

  /*--------------- USERS ----------------*/

  public function add_user(){
    global $wpdb;
    $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}tartinette_users WHERE email = '$email'");
    if (is_null($row)) {
        $wpdb->insert("{$wpdb->prefix}tartinette_users", [
          'last_name'   => $_POST['last_name'],
          'first_name'  => $_POST['first_name'],
          'phone'       => $_POST['phone'],
          'email'       => $_POST['email'],
          'pass_h'      => sha1($_POST['password']),
          'tribu'       => $_POST['tribu']
        ]);
    }
  }

  public function get_user($info){
    global $wpdb;
    if(is_int($info)){
      return $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}tartinette_users WHERE id = '$info' ");
    }
    else{
      return $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}tartinette_users WHERE email = '$info' ");
    }
  }

  public function get_all_users(){
    global $wpdb;
    $data = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}tartinette_users");
    return $data;
  }

  /*--------------- CHILDREN ----------------*/

  public function add_child(){
    global $wpdb;
    $last_name = $_POST['last_name'];
    $first_name = $_POST['first_name'];
    $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}tartinette_children WHERE last_name = '$last_name' AND first_name = '$first_name'");
    if (is_null($row)) {
        $wpdb->insert("{$wpdb->prefix}tartinette_children", [
          'last_name'   => $_POST['last_name'],
          'first_name'  => $_POST['first_name'],
          'school'      => $_POST['school'],
          'classroom'   => $_POST['classroom'],
          'tribu'       => $_SESSION['user_data']->tribu
        ]);
        return $wpdb->insert_id;
    }
  }

  public function get_child($id){
    global $wpdb;
    $child = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}tartinette_children WHERE id = '$id' ");
    return $child;
  }

  public function child_exists($first_name, $last_name){
    global $wpdb;
    $child = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}tartinette_children WHERE first_name = '$first_name' AND last_name = '$last_name' ");
    return $child;
  }

  public function update_child($id){
    global $wpdb;
    $data = [
      'last_name'   => $_POST['last_name'],
      'first_name'  => $_POST['first_name'],
      'school'      => $_POST['school'],
      'classroom'   => $_POST['classroom'],
      'tribu'       => $_SESSION['user_data']->tribu
    ];
    $wpdb->update("{$wpdb->prefix}tartinette_children", $data, ['id' => $id]);
  }

  public function delete_child($id){
    global $wpdb;
    $wpdb->delete("{$wpdb->prefix}tartinette_children", ['id' => $id]);
  }

  public function get_children(){
    global $wpdb;
    $tribu = $_SESSION['user_data']->tribu;
    $children = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}tartinette_children WHERE tribu = '$tribu' ");
    return $children;
  }

  /*--------------- PREF ----------------*/

  public function save_preferences($id){
    global $wpdb;
    $id_child = $id;

    $classique      = (isset($_POST['classique']))      ? true : false;
    $dago           = (isset($_POST['dago']))           ? true : false;
    $fromage        = (isset($_POST['fromage']))        ? true : false;
    $autre_fromage  = (isset($_POST['autre_fromage']))  ? true : false;
    $italien        = (isset($_POST['italien']))        ? true : false;
    $halal          = (isset($_POST['halal']))          ? true : false;

    $this->likes = [
      'classique'     => $classique,
      'dago'          => $dago,
      'fromage'       => $fromage,
      'autre_fromage' => $autre_fromage,
      'italien'       => $italien,
      'halal'         => $halal
    ];

    $beurre         = (isset($_POST['beurre']))         ? true : false;
    $salade         = (isset($_POST['salade']))         ? true : false;
    $legume_grille  = (isset($_POST['legume_grille']))  ? true : false;
    $legumaise      = (isset($_POST['legumaise']))      ? true : false;
    $pesto          = (isset($_POST['pesto']))          ? true : false;

    $this->dislikes = [
      'beurre'        => $beurre,
      'salade'        => $salade,
      'legume_grille' => $legume_grille,
      'legumaise'     => $legumaise,
      'pesto'         => $pesto
    ];

    $fruit = ($_POST['fruit'] === 'oui') ? true : false;
    $portion = $_POST['portion'];

    $this->child_params = [
      'fruit'   => $fruit,
      'portion' => $portion
    ];

    $row = $this->get_child($id_child);
    if(is_null($row)){
      //add child_infos
      $this->add_child_params($id_child);
      $this->add_likes($id_child);
      $this->add_dislikes($id_child);
    }
    else{
      //update child_infos
      $this->update_child_params($id_child);
      $this->update_likes($id_child);
      $this->update_dislikes($id_child);
    }
  }

      /* --- child_params --- */

  private function add_child_params($id){
    $wpdb->insert("{$wpdb->prefix}tartinette_child_params", [
      'id_child'  => $id,
      'fruit'     => $this->child_params['fruit'],
      'portion'   => $this->child_params['portion']
    ]);
  }

  public function get_child_params($id){
    global $wpdb;
    $child_params = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}tartinette_child_params WHERE id_child = '$id' ");
    return $child_params;
  }

  private function update_child_params($id){
    global $wpdb;
    $data = [
      'id_child'  => $id,
      'fruit'     => $this->child_params['fruit'],
      'portion'   => $this->child_params['portion']
    ];
    $wpdb->update("{$wpdb->prefix}tartinette_child_params", $data, ['id' => $id]);
  }

      /* --- likes --- */

  private function add_likes($id){
    global $wpdb;
    $wpdb->insert("{$wpdb->prefix}tartinette_likes", [
      'id_child'        => $id,
      'classique'       => $this->likes['classique'],
      'dago'            => $this->likes['dago'],
      'fromage'         => $this->likes['fromage'],
      'autre_fromage'   => $this->likes['autre_fromage'],
      'italien'         => $this->likes['italien'],
      'halal'           => $this->likes['halal']
    ]);
  }

  public function get_likes($id){
    global $wpdb;
    $likes = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}tartinette_likes WHERE id_child = '$id' ");
    return $likes;
  }

  private function update_likes($id){
    global $wpdb;
    $data = [
      'id_child'        => $id,
      'classique'       => $this->likes['classique'],
      'dago'            => $this->likes['dago'],
      'fromage'         => $this->likes['fromage'],
      'autre_fromage'   => $this->likes['autre_fromage'],
      'italien'         => $this->likes['italien'],
      'halal'           => $this->likes['halal']
    ];
    $wpdb->update("{$wpdb->prefix}tartinette_likes", $data, ['id' => $id]);
  }

      /* --- dislikes --- */

  private function add_dislikes($id){
    global $wpdb;
    $wpdb->insert("{$wpdb->prefix}tartinette_dislikes", [
      'id_child'        => $id,
      'beurre'          => $this->dislikes['beurre'],
      'salade'          => $this->dislikes['salade'],
      'legume_grille'   => $this->dislikes['legume_grille'],
      'legumaise'       => $this->dislikes['legumaise'],
      'pesto'           => $this->dislikes['pesto'],
    ]);
  }

  public function get_dislikes($id){
    global $wpdb;
    $likes = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}tartinette_dislikes WHERE id_child = '$id' ");
    return $likes;
  }

  private function update_dislikes($id){
    global $wpdb;
    $data = [
      'id_child'        => $id,
      'beurre'          => $this->dislikes['beurre'],
      'salade'          => $this->dislikes['salade'],
      'legume_grille'   => $this->dislikes['legume_grille'],
      'legumaise'       => $this->dislikes['legumaise'],
      'pesto'           => $this->dislikes['pesto'],
    ];
    $wpdb->update("{$wpdb->prefix}tartinette_dislikes", $data, ['id' => $id]);
  }


}
