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
    add_action('init', [$this->pages_manager, 'add_panic_page']);
    add_action('wp_loaded', [$this, 'routeur']);
    add_action('wp', [$this, 'enqueue_datascripts']);
  }

  public function add_admin_menu_users(){
    add_menu_page('My Users', 'My Users', 'manage_options', 'myoo_users', [$this, 'admin_users_html'], 'dashicons-groups', 27 );
  }

  public function routeur(){
    if (isset($_POST['go_abo'])){
      $_SESSION['panic'] = false;
      if($_SESSION['connected'] === true){
        wp_redirect('http://localhost/php/wordpress/index.php/mon-compte/');
        exit;
      }
      else{
        wp_redirect('http://localhost/php/wordpress/index.php/connexion/');
        exit;
      }
    }

    elseif(isset($_POST['go_panic'])){
      $_SESSION['panic'] = true;
      if($_SESSION['connected'] === true){
        wp_redirect('http://localhost/php/wordpress/index.php/panic/');
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
        if($_SESSION['panic'] === true){
          wp_redirect('http://localhost/php/wordpress/index.php/panic/');
        }
        else{
          wp_redirect('http://localhost/php/wordpress/index.php/mon-compte/');
        }
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
      $this->save_child();
      wp_redirect('http://localhost/php/wordpress/index.php/mon-compte/');
      exit;
    }
  }

  public function enqueue_datascripts(){
    if(is_page('Mon compte')){
      $data_children = $this->get_all_data();
      $portions = [
        'S_tartines' => (int)get_option('S_tartines'),
        'M_tartines' => (int)get_option('M_tartines'),
        'L_tartines' => (int)get_option('L_tartines'),
        'S_baguette' => (int)get_option('S_baguette'),
        'M_baguette' => (int)get_option('M_baguette'),
        'L_baguette' => (int)get_option('L_baguette')
      ];
      $prix = [
        'S_1j' => (float)get_option('S_1j'),
        'S_2j' => (float)get_option('S_2j'),
        'S_3j' => (float)get_option('S_3j'),
        'S_4j' => (float)get_option('S_4j'),
        'S_5j' => (float)get_option('S_5j'),
        'M_1j' => (float)get_option('M_1j'),
        'M_2j' => (float)get_option('M_2j'),
        'M_3j' => (float)get_option('M_3j'),
        'M_4j' => (float)get_option('M_4j'),
        'M_5j' => (float)get_option('M_5j'),
        'L_1j' => (float)get_option('L_1j'),
        'L_2j' => (float)get_option('L_2j'),
        'L_3j' => (float)get_option('L_3j'),
        'L_4j' => (float)get_option('L_4j'),
        'L_5j' => (float)get_option('L_5j'),
      ];
      $data = [$data_children, $portions, $prix, $_SESSION['user_data']];

      if(wp_script_is('account_datascript')){
        wp_localize_script('account_datascript', 'dataUser', $data);
      }
      else{
        wp_register_script('account_datascript', plugin_dir_url(__FILE__) . '../assets/scripts/account_datascript.js');
        wp_localize_script('account_datascript', 'dataUser', $data);
        wp_enqueue_script('account_datascript');
      }
    } // end script mon compte
    if(is_page('Panic !')){
      $children = $this->get_all_data();

      $portions = [
        'S_tartines' => (int)get_option('S_tartines'),
        'M_tartines' => (int)get_option('M_tartines'),
        'L_tartines' => (int)get_option('L_tartines')
      ];

      $prix = [
        'S_panic' => (int)get_option('S_panic'),
        'M_panic' => (int)get_option('M_panic'),
        'L_panic' => (int)get_option('L_panic'),
        'fruit'   => (int)get_option('supplement_fruit')
      ];

      $data = [$children, $portions, $prix, $_SESSION['user_data']];

      if(wp_script_is('panic_datascript')){
        wp_localize_script('panic_datascript', 'dataUser', $data);
      }
      else{
        wp_register_script('panic_datascript', plugin_dir_url(__FILE__) . '../assets/scripts/panic_datascript.js');
        wp_localize_script('panic_datascript', 'dataUser', $data);
        wp_enqueue_script('panic_datascript');
      }
    }
    if(is_page('Tartinette')){
      if(!wp_script_is('home_script')){
        wp_register_script('home_script', plugin_dir_url(__FILE__) . '../assets/scripts/home_script.js');
        wp_enqueue_script('home_script');
      }
    }
  }

  private function get_all_data(){
    if($_SESSION['panic'] === false){
      $tribu_name = $_SESSION['user_data']->tribu;
      $children_objects = $this->users_manager->get_children($tribu_name);
      $children = [];
      foreach ($children_objects as $one_child) {
        $child = [];
        $child [] = $one_child;
        $id = $one_child->id;
        $child [] = $this->users_manager->get_child_params($id);
        $child [] = $this->users_manager->get_likes($id);
        $child [] = $this->users_manager->get_dislikes($id);
        $children [] = $child;
      }
      return $children;
    }
    elseif($_SESSION['panic'] === true){
      $tribu_name = $_SESSION['user_data']->tribu;
      $children = $this->users_manager->get_children($tribu_name);
      return $children;
    }

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
          $last_name = $_POST['last_name'];
          $first_name = $_POST['first_name'];
          if(is_null($this->users_manager->child_exists($first_name, $last_name))){
            $id = $this->users_manager->add_child();
            $this->users_manager->save_preferences($id);
          }
          else{
            $child = $this->users_manager->child_exists($first_name, $last_name);
            $this->users_manager->update_child($child->id);
            $this->users_manager->save_preferences($child->id);
          }
    }
    else{
      echo 'ERREUR ! Manque des champs !';
    }
  }



/* ---------------- HTML --------------- */

  public function admin_users_html(){ // dont touch
    $data = $users_manager->get_all_users();
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
