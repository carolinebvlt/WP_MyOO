<?php
/*
  - Création d'une page "My account"
  - Connexion form
*/

class MyOO_Account_Module
{
  public function __construct(){
    add_action('init', [$this, 'add_account_page']);
    add_action('wp_loaded', [$this, 'connexion']);
    add_action('wp', [$this, 'enqueue_my_script']);
  }

  public function add_account_page(){
    $content = $this->connexion_form();
    $postTitle = 'Mon compte';

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
        wp_insert_post($page);
    }
  }

  public function connexion(){
    if(isset($_POST['submit_connexion'])){
      $email = $_POST['email'];
      $password = $_POST['password'];
      global $wpdb;
      $data = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}tartinette_users WHERE email = '$email' ");
      if($data->pass_h === sha1($password)){
        $_SESSION['connected'] = true;
        $_SESSION['user_data'] = $data;
      }
    }
    if($_SESSION['connected'] === true){
      global $wpdb;
      $sql = "SELECT * FROM {$wpdb->prefix}posts WHERE post_title = 'Mon compte' AND post_status = 'publish' AND post_type = 'page' ";
      $row = $wpdb->get_row($sql);
      $id_page = $row->ID;
      $html = $this->my_account_html();
      wp_update_post([
        'ID' => $id_page,
        'post_content' => $html
      ]);
    }
  }

  public function connexion_form(){
      return "<form method='post' action=''>
                <input type='text' name='email' placeholder='Votre email'/>
                <input type='password' name='password' placeholder='Mot de passe'/>
                <input type='submit' name='submit_connexion' value='Se connecter'/>
              </form>";
  }

  public function my_account_html(){
    return "<div>
              <span>Tribu ".$_SESSION['user_data']->tribu."</span>
              <form method='post' action=''>
                <input type='button' name='child1' onclick='hop()' value='Child one'/>
                <input type='button' name='child2' onclick='hophop()' value='Child two'/>
                <input type='submit' name='add_child' value='Add'/>
              </form>
            </div>
            <div id='my_forms'></div>
            <h1>Ma commande </h1>";
  }

  public function enqueue_my_script(){
    wp_register_script('my_forms_script', plugin_dir_url(__FILE__) . '../assets/my_forms_script.js');
    wp_enqueue_script('my_forms_script');
  }

} // end class
