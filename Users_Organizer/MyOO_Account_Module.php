<?php
/*
  - Création d'une page "My account"
  - Connexion : $_SESSION['connected'] === true
*/

class MyOO_Account_Module
{
  public function __construct(){
    add_action('init', [$this, 'add_account_page']);
  }

  public function add_account_page(){
    $content = $this->account_page();
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
        $insert_id = wp_insert_post( $page );
    }
  }

  public function account_page(){
    if(isset($_SESSION['connected'])&&$_SESSION['connected']===true){
      return 'Hello you !';
    }
    else{
      return "<form method='post' action=''>
                <input type='text' name='email' placeholder='Votre email'/>
                <input type='password' name='password' placeholder='Mot de passe'/>
              </form>";
    }
  }


} // end class
