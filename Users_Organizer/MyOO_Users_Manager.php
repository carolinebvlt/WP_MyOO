<?php
class MyOO_Users_Manager
{
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

  public function get_user($email){
    global $wpdb;
    return $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}tartinette_users WHERE email = '$email' ");
  }

  public function get_users(){
    global $wpdb;
    $data = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}tartinette_users");
    return $data;
  }

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
    }
  }

  public function get_children(){
    global $wpdb;
    $tribu = $_SESSION['user_data']->tribu;
    $children = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}tartinette_children WHERE tribu = '$tribu' ");
    return $children;
  }

  public function add_preferences(){
    global $wpdb;
    $id_child = $wpdb->insert_id;

    $classique      = (isset($_POST['classique']))      ? true : false;
    $dago           = (isset($_POST['dago']))           ? true : false;
    $fromage        = (isset($_POST['fromage']))        ? true : false;
    $autre_fromage  = (isset($_POST['autre_fromage']))  ? true : false;
    $italien        = (isset($_POST['italien']))        ? true : false;
    $halal          = (isset($_POST['halal']))          ? true : false;

    $beurre         = (isset($_POST['beurre']))         ? true : false;
    $salade         = (isset($_POST['salade']))         ? true : false;
    $legume_grille  = (isset($_POST['legume_grille']))  ? true : false;
    $legumaise      = (isset($_POST['legumaise']))      ? true : false;
    $pesto          = (isset($_POST['pesto']))          ? true : false;

    $fruit          = ($_POST['fruit'] === 'oui')       ? true : false;

    $portion = $_POST['portion'];

    $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}tartinette_preferences WHERE id_child = '$id_child' ");
    if (is_null($row)) {
        $wpdb->insert("{$wpdb->prefix}tartinette_preferences", [
          'id_child'  => $id_child,
          'fruit'     => $fruit,
          'portion'   => $portion
        ]);
    }

    $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}tartinette_likes WHERE id_child = '$id_child' ");
    if (is_null($row)) {
        $wpdb->insert("{$wpdb->prefix}tartinette_likes", [
          'id_child'        => $id_child,
          'classique'       => $classique,
          'dago'            => $dago,
          'fromage'         => $fromage,
          'autre_fromage'   => $autre_fromage,
          'italien'         => $italien,
          'halal'           => $halal
        ]);
    }

    $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}tartinette_dislikes WHERE id_child = '$id_child' ");
    if (is_null($row)) {
        $wpdb->insert("{$wpdb->prefix}tartinette_dislikes", [
          'id_child'        => $id_child,
          'beurre'          => $beurre,
          'salade'          => $salade,
          'legume_grille'   => $legume_grille,
          'legumaise'       => $legumaise,
          'pesto'           => $pesto,
        ]);
    }
  }
}
