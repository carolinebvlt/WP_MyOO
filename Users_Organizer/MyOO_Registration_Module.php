<?php
/*
  - Création d'une page "Registration" + table DB 'tartinette_users'
  - Création d'un onglet "My Users" dans le menu du dashboard
  - Enregistre les inscriptions
  - noms des variables register_form / DB
    - last_name
    - first_name
    - phone
    - email
    - 'password' => pass_h
    - 'password_check'
    - 'submit_registration'
*/

class MyOO_Registration_Module
{
  public function __construct(){
    add_action('init', [$this, 'add_registration_page']);
    add_action('admin_menu', [$this, 'add_admin_menu'], 20);
    add_action('wp_loaded', [$this, 'save_new_user']);
  }

  public function add_registration_page(){
    $content = $this->register_form();
    $postTitle = 'Inscription';

    global $wpdb;
    $sql = "SELECT * FROM {$wpdb->prefix}posts WHERE post_title = '$postTitle' AND post_status = 'publish' AND post_type = 'page' ";
    $row = $wpdb->get_row($sql);


    if(is_null($row)){
        $page = [
            'post_title'   => $postTitle,
            'post_content' => $content,
            'post_status'  => 'publish',
            'post_author'  => 1,
            'post_type'    => 'page',
            'post_parent'  => 0
        ];
        $insert_id = wp_insert_post( $page );
    }
  }

  public function register_form(){
    return
    '<form action="" method="post">
      <input type="text" name="last_name" placeholder="Nom"/>
      <input type="text" name="first_name" placeholder="Prénom"/>
      <input type="text" name="tribu" placeholder="Nom de la tribu"/>
      <input type="text" name="phone" placeholder="Téléphone (ex : 0400000000)"/>
      <input type="text" name="email" placeholder="Email"/>
      <input type="password" name="password" placeholder="Mot de passe"/>
      <input type="password" name="password_check" placeholder="Confirmation du mot de passe"/>
      <input type="submit" name="submit_registration" value="S\'inscrire">
    </form>'
    ;
  }

  public function add_admin_menu(){
    add_menu_page('My Users', 'My Users', 'manage_options', 'myoo_users', [$this, 'my_users_render'], 'dashicons-groups', 27 );
  }

  public function my_users_render(){
    global $wpdb;
    $data = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}tartinette_users");
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

  public function install_db(){
    global $wpdb;
    $sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tartinette_users (
              `id` INT NOT NULL AUTO_INCREMENT ,
              `last_name` VARCHAR(20) NOT NULL ,
              `first_name` VARCHAR(20) NOT NULL ,
              `phone` VARCHAR(10) NOT NULL ,
              `email` VARCHAR(30) NOT NULL ,
              `pass_h` TEXT NOT NULL ,
              `tribu` VARCHAR(20) NOT NULL ,
              PRIMARY KEY (`id`),
              UNIQUE (`email`)) ENGINE = InnoDB;" ;
    $wpdb->query($sql);
  }

  public function save_new_user(){
    if (
          (isset($_POST['last_name']) && !empty($_POST['last_name'])) &&
          (isset($_POST['first_name']) && !empty($_POST['first_name'])) &&
          (isset($_POST['phone']) && !empty($_POST['phone'])) &&
          (isset($_POST['email']) && !empty($_POST['email'])) &&
          (isset($_POST['password']) && !empty($_POST['password']) && $_POST['password'] === $_POST['password_check']) &&
          (isset($_POST['tribu']) && !empty($_POST['tribu']))
        ){
      global $wpdb;
      $last_name    = $_POST['last_name'];
      $first_name   = $_POST['first_name'];
      $phone        = $_POST['phone'];
      $email        = $_POST['email'];
      $password     = $_POST['password'];
      $tribu        = $_POST['tribu'];

      $row = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}tartinette_users WHERE email = '$email'");
      if (is_null($row)) {
          $wpdb->insert("{$wpdb->prefix}tartinette_users", [
            'last_name'   => $last_name,
            'first_name'  => $first_name,
            'phone'       => $phone,
            'email'       => $email,
            'pass_h'      => sha1($password),
            'tribu'       => $tribu
          ]);
      }
    }
  }

} // end class
