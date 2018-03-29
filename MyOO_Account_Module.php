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
              <h2>Tribu ".$_SESSION['user_data']->tribu."</h2>
              <form method='post' action=''>
                <div>
                  <div><input style='float:left' type='button' name='child1' onclick='hop()' value='Jessie'/></div><br/>
                  <div><input type='button' name='child2' onclick='hophop()' value='James'/></div><br/>
                  <div><input type='submit' name='add_child' value='Add a child'/></div>
                </div>
              </form>
            </div>
            <div style='display:none' id='my_forms'>
            <form action='' method='post'>
              <div>
                <input type='text' placeholder='Ecole'/>
                <input type='text' placeholder='Classe'>
              </div><br/>
              <div>
                <h3>Like</h3>
                <input type='checkbox' name='fromage' value='Fromage' />Fromage
                <input type='checkbox' name='italien' value='Italien' />Italien
                <input type='checkbox' name='halal' value='Halal' />Halal
              </div>
              <div>
                <h3>Dislike</h3>
                <input type='checkbox' name='beurre' value='Beurre' />Beurre
                <input type='checkbox' name='salade' value='Salade' />Salade
                <input type='checkbox' name='legumaise' value='Légumaise' />Légumaise
              </div>
              <div>
                <h3>Fruit</h3>
                <input type='radio' name='fruit' value='Oui' />Oui
                <input type='radio' name='fruit' value='Non' />Non
              </div>
              <div>
                <h3>Appétit</h3>
                <input type='radio' name='portion' value='Benjamin' />Benjamin <i>(2 tartines ou 1/4 de baguette)</i>
                <input type='radio' name='portion' value='Cadette' />Cadette <i>(4 tartines ou 1/3 de baguette)</i>
                <input type='radio' name='portion' value='Ainé' />Ainé <i>(6 tartines ou 1/2 de baguette)</i>
              </div>
              <div>
                <h3>Commande pour :</h3>
                <input type='checkbox' name='lun' value='lun' />Lun
                <input type='checkbox' name='mar' value='mar' />Mar
                <input type='checkbox' name='mer' value='mer' />Mer
                <input type='checkbox' name='jeu' value='jeu' />Jeu
                <input type='checkbox' name='ven' value='ven' />Ven
              </div>
              <input type='submit' name='save_choices' value='Ok'/>
            </form>
            </div>
            <h1>Ma commande </h1>
            <div>
              <div>
                Jessie : . x . tartines <br/>
                James : . x 1/x de baguette <br/>
              </div><br/>
              <div class='wrap'>
                <strong>Total : </strong> .,. €
              </div><br/>
              <div>
                <input type='submit' name='commander' value='Commander' />
              </div>
            </div>";
  }

  public function enqueue_my_script(){
    wp_register_script('my_forms_script', plugin_dir_url(__FILE__) . '../assets/my_forms_script.js');
    wp_enqueue_script('my_forms_script');
  }

} // end class
