<?php
session_start();

class MyOO_Users_Organizer
{
  public function __construct(){
    add_action('init', [$this, 'add_account_page']);
    add_action('admin_menu', [$this, 'add_admin_menu_users'], 20);
    add_action('wp_loaded', [$this, 'which_action']);
    add_action('wp', [$this, 'enqueue_my_script']);
  }

  public function add_admin_menu_users(){
    add_menu_page('My Users', 'My Users', 'manage_options', 'myoo_users', [$this, 'my_users_render'], 'dashicons-groups', 27 );
  }

  public function add_account_page(){
    $content = $this->account_home_html();
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

  public function which_action(){
    if(isset($_POST['submit_registration'])){
      $this->save_new_user();
    }
    elseif(isset($_POST['submit_connexion'])){
      $this->connexion();
    }
  }

  public function enqueue_my_script(){
    wp_register_script('my_forms_script', plugin_dir_url(__FILE__) . '../assets/my_forms_script.js');
    wp_enqueue_script('my_forms_script');
  }

  public function save_new_user(){ /*AC*/
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

  public function connexion(){
    $email = $_POST['email'];
    $password = $_POST['password'];
    global $wpdb;
    $data = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}tartinette_users WHERE email = '$email' ");
    if($data->pass_h === sha1($password)){
      $_SESSION['connected'] = true;
      $_SESSION['user_data'] = $data;
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

/* ---------------- HTML --------------- */

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

  public function account_home_html(){
    // home + register_form + connexion_form
    return '<div id="home_buttons" class="wrap">
              <input style="margin:auto; display:block" onclick="show(connexion_form,home_buttons)" type="button" value="Se connecter" />
              <p style="text-align:center">ou</p>
              <input style="margin:auto; display:block" onclick="show(register_form,home_buttons)" type="button" value="S\'inscrire" />
            </div>
            <div id="register_form" class="wrap" style="display:none">
              <form action="" method="post">
                <input type="text" name="last_name" placeholder="Nom"/>
                <input type="text" name="first_name" placeholder="Prénom"/>
                <input type="text" name="tribu" placeholder="Nom de la tribu"/>
                <input type="text" name="phone" placeholder="Téléphone (ex : 0400000000)"/>
                <input type="text" name="email" placeholder="Email"/>
                <input type="password" name="password" placeholder="Mot de passe"/>
                <input type="password" name="password_check" placeholder="Confirmation du mot de passe"/>
                <input type="submit" name="submit_registration" onclick="show(connexion_form, register_form)" value="S\'inscrire">
              </form>
            </div>
            <div id="connexion_form" class="wrap" style="display:none">
              <form method="post" action="">
                <input type="text" name="email" placeholder="Votre email"/>
                <input type="password" name="password" placeholder="Mot de passe"/>
                <input type="submit" name="submit_connexion" value="Se connecter"/>
              </form>
            </div>';
  }

  public function my_account_html(){
    return "<div>
              <h2 >Tribu ".$_SESSION['user_data']->tribu."</h2>
              <form method='post' action=''>
                <div>
                  <div><input style='float:left' type='button' name='child1' onclick='just_show(my_forms)' value='Jessie'/></div><br/>
                  <div><input type='button' name='child2' onclick='just_show(my_forms)' value='James'/></div><br/>
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

} // end class
