<?php
session_start();

class MyOO_Users_Organizer
{
  private $users_manager;

  public function __construct(){
    include_once plugin_dir_path( __FILE__ ).'/MyOO_Users_Manager.php';
    $this->users_manager = new MyOO_Users_Manager();
    include_once plugin_dir_path( __FILE__ ).'/MyOO_Pages_Manager.php';
    $this->pages_manager = new MyOO_Pages_Manager();
    add_action('admin_menu', [$this, 'add_admin_menu_users'], 20);
    add_action('init', [$this->pages_manager, 'add_tartinette_home_page']);
    add_action('init', [$this->pages_manager, 'add_subscription_page']);
    add_action('init', [$this->pages_manager, 'add_connexion_page']);
    add_action('init', [$this->pages_manager, 'add_account_page']);
    add_action('wp_loaded', [$this, 'routeur']);
    add_action('wp', [$this, 'enqueue_tribu_datascript']);
  }

  public function add_admin_menu_users(){
    add_menu_page('My Users', 'My Users', 'manage_options', 'myoo_users', [$this, 'admin_users_html'], 'dashicons-groups', 27 );
  }

  public function routeur(){
    if (isset($_POST['go_tartinette'])){
      if($_SESSION['connected'] === true){
        wp_redirect('http://localhost/php/wordpress/index.php/mon-compte/');
        exit;
      }
      else{
        wp_redirect('http://localhost/php/wordpress/index.php/connexion/');
        exit;
      }
    }

    elseif (isset($_POST['submit_connexion'])){
      $email = $_POST['email'];
      $password = $_POST['password'];
      $data = $this->users_manager->get_user($email);
      if($data->pass_h === sha1($password)){
        $_SESSION['connected'] = true;
        $_SESSION['user_data'] = $data;
        wp_redirect('http://localhost/php/wordpress/index.php/mon-compte/');
        exit;
      }
    }

    elseif (isset($_POST['go_subscription'])) {
      wp_redirect('http://localhost/php/wordpress/index.php/inscription/');
      exit;
    }

    elseif (isset($_POST['submit_registration'])){
      $done = $this->save_new_user();
      if($done === true){
        wp_redirect('http://localhost/php/wordpress/index.php/connexion/');
        exit;
      }
    }

    elseif (isset($_POST['save_choices'])){
      $id = $this->save_child();
      $this->users_manager->save_preferences($id);
      wp_redirect('http://localhost/php/wordpress/index.php/mon-compte/');
      exit;
    }
  }

  public function enqueue_tribu_datascript(){
    if(is_page('Mon compte')){
      $data_children = $this->get_all_data();
      $_SESSION['data_children'] = $data_children;
      if(wp_script_is('tribu_datascript')){
        wp_localize_script('tribu_datascript', 'dataUser', $_SESSION['data_children']);
      }
      else{
        wp_register_script('tribu_datascript', plugin_dir_url(__FILE__) . '../assets/scripts/tribu_datascript.js');
        wp_localize_script('tribu_datascript', 'dataUser', $_SESSION['data_children']);
        wp_enqueue_script('tribu_datascript');
      }
    }
  }

  private function get_all_data(){
    $tribu_name = $_SESSION['user_data']->tribu;
    $children_objects = $this->users_manager->get_children($tribu_name);
    $children = [];
    foreach ($children_objects as $one_child) {
      $child = [];
      $child [] = $one_child;
      $id = $one_child->id;
      $child [] = $this->users_manager->get_pref($id);
      $child [] = $this->users_manager->get_likes($id);
      $child [] = $this->users_manager->get_dislikes($id);
      $children [] = $child;
    }
    return $children;
  }

  public function save_new_user(){ /*AC*/
    if (
          (isset($_POST['last_name'])   && !empty($_POST['last_name']))   &&
          (isset($_POST['first_name'])  && !empty($_POST['first_name']))  &&
          (isset($_POST['phone'])       && !empty($_POST['phone']))       &&
          (isset($_POST['email'])       && !empty($_POST['email']))       &&
          (isset($_POST['password'])    && !empty($_POST['password'])     &&
            $_POST['password'] === $_POST['password_check'])              &&
          (isset($_POST['tribu'])       && !empty($_POST['tribu']))
        ){
          $this->users_manager->add_user();
          return true;
    }
    else{
      echo 'Erreur : Pas enregistré !';
      return false;
    }
  }

  public function save_child(){
    if (
          (isset($_POST['last_name'])   && !empty($_POST['last_name']))   &&
          (isset($_POST['first_name'])  && !empty($_POST['first_name']))  &&
          (isset($_POST['school'])      && !empty($_POST['school']))      &&
          (isset($_POST['classroom'])   && !empty($_POST['classroom']))
        ){
          $id = $this->users_manager->add_child();
          return $id;
    }
  }



/* ---------------- HTML --------------- */

  public function admin_users_html(){ // dont touch
    $data = $users_manager->get_users();
    echo '<div class="wrap theme-options-page"><h1>'.get_admin_page_title().'</h1></div><br/>';
    echo "<table>";
    echo "<tr>
            <th style='width:10%'>Nom</th>
            <th style='width:10%'>Prénom</th>
            <th style='width:10%'>Téléphone</th>
            <th style='width:10%'>Email</th>
            <th style='width:10%'>Tribu</th>
          </tr>";
    foreach ($data as $user) {
      echo "<tr style='text-align:center'><td>".$user->last_name."</td><td>".$user->first_name."</td><td>".$user->phone."</td><td>".$user->email."</td><td>".$user->tribu."</td></tr>";
    }
    echo "</table>";
  }

} // end class
