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
    elseif (isset($_POST['save_choices'])){
      $this->save_child();
      $this->save_preferences();
      $this->temp_order();
    }
  }

  public function enqueue_my_script(){
    wp_register_script('my_forms_script', plugin_dir_url(__FILE__) . '../assets/my_forms_script.js');
    wp_enqueue_script('my_forms_script');
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

  public function save_child(){
    if (
          (isset($_POST['last_name'])   && !empty($_POST['last_name']))   &&
          (isset($_POST['first_name'])  && !empty($_POST['first_name']))  &&
          (isset($_POST['school'])      && !empty($_POST['school']))      &&
          (isset($_POST['classroom'])   && !empty($_POST['classroom']))
        ){
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
  }

  public function save_preferences(){
    global $wpdb;
    $id_child       = $wpdb->insert_id;

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

  public function get_children(){
    global $wpdb;
    $tribu = $_SESSION['user_data']->tribu;
    $children = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}tartinette_children WHERE tribu = '$tribu' ");
    $children_html;
    foreach ($children as $child) {
      $children_html = $children_html."<input style='whidth:100px; height:50px;' type='button' name='".$child->first_name."' onclick='just_show(my_forms)' value='".$child->first_name."'/>";
    }
    return $children_html;
  }

  public function temp_order(){
    $lundi    = (isset($_POST['lundi']))    ? true : false;
    $mardi    = (isset($_POST['mardi']))    ? true : false;
    $mercredi = (isset($_POST['mercredi'])) ? true : false;
    $jeudi    = (isset($_POST['jeudi']))    ? true : false;
    $vendredi = (isset($_POST['vendredi'])) ? true : false;

    $_SESSION['temp_order'] = [$lundi,$mardi,$mercredi,$jeudi,$vendredi];
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
              <h2 >Tribu ".$_SESSION['user_data']->tribu."</h2>".
              $this->get_children()
              ."<form method='post' action=''>
                <div id='children'>
                </div>
                <div><input onclick='just_show(my_forms)' type='button' name='add_child' value='Ajouter un enfant'/></div>
              </form>
            </div>
            <div style='display:none' id='my_forms'>
            <form action='' method='post'>
              <div>
                <input type='text' name='last_name' placeholder='Nom' />
                <input type='text' name='first_name' placeholder='Prénom' />
                <input type='text' name='school' placeholder='Ecole'/>
                <input type='text' name='classroom' placeholder='Classe'>
              </div><br/>
              <div>
                <h3>Like</h3>
                <input type='checkbox' name='classique' />Classique
                <input type='checkbox' name='dago' />Dago
                <input type='checkbox' name='fromage' />Fromage
                <input type='checkbox' name='autre_fromage' />L'Autre fromage
                <input type='checkbox' name='italien' />Italien
                <input type='checkbox' name='halal' />Halal
              </div>
              <div>
                <h3>Dislike</h3>
                <input type='checkbox' name='beurre' />Beurre
                <input type='checkbox' name='salade' />Salade
                <input type='checkbox' name='legume_grille' />Légume grillé
                <input type='checkbox' name='legumaise' />Légumaise
                <input type='checkbox' name='pesto' />Pesto
              </div>
              <div>
                <h3>Fruit</h3>
                <input type='radio' name='fruit' value='oui' />Oui
                <input type='radio' name='fruit' value='non' />Non
              </div>
              <div>
                <h3>Appétit</h3>
                <input type='radio' name='portion' value='S' />Benjamin <i>(2 tartines ou 1/4 de baguette)</i>
                <input type='radio' name='portion' value='M' />Cadette <i>(4 tartines ou 1/3 de baguette)</i>
                <input type='radio' name='portion' value='L' />Ainé <i>(6 tartines ou 1/2 de baguette)</i>
              </div>
              <div>
                <h3>Commander pour :</h3>
                <input type='checkbox' name='lundi' />Lun
                <input type='checkbox' name='mardi' />Mar
                <input type='checkbox' name='mercredi' />Mer
                <input type='checkbox' name='jeudi' />Jeu
                <input type='checkbox' name='vendredi' />Ven
              </div>
              <input type='submit' name='save_choices' value='Ok'/>
            </form>
            </div>
            <h1>Ma commande </h1>
            <div>
              <div id='resume_commande'>
              </div><br/>
              <div id='prix_total' class='wrap'>
                <strong>Total : </strong>  €
              </div><br/>
              <div>
                <input type='submit' name='commander' value='Commander' />
              </div>
            </div>";
  }

} // end class
