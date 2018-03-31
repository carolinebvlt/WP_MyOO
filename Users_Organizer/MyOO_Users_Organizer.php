<?php
session_start();

class MyOO_Users_Organizer
{
  private $users_manager;

  public function __construct(){
    include_once plugin_dir_path( __FILE__ ).'/MyOO_Users_Manager.php';
    $this->users_manager = new MyOO_Users_Manager();
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
    }
    elseif (isset($_POST['add_child'])) {
      $this->update_content_add_child();
    }
    elseif (isset($_POST['show_pref'])) {
      $this->update_content_show_pref();
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
          $this->users_manager->add_user();
    }
  }

  public function connexion(){
    $email = $_POST['email'];
    $password = $_POST['password'];

    $data = $this->users_manager->get_user($email);

    if($data->pass_h === sha1($password)){
      $_SESSION['connected'] = true;
      $_SESSION['user_data'] = $data;
    }
    if($_SESSION['connected'] === true){
      global $wpdb;
      $sql = "SELECT * FROM {$wpdb->prefix}posts WHERE post_title = 'Mon compte' AND post_status = 'publish' AND post_type = 'page' ";
      $row = $wpdb->get_row($sql);
      $id_page = $row->ID;
      $html = $this->div_tribu_html().$this->div_commande_html();
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
          $this->users_manager->add_child();
          $this->update_content_child_added();
    }
  }

  public function save_preferences(){
    $this->users_manager->add_preferences();
  }

  public function get_children_buttons(){
    $children = $this->users_manager->get_children();
    $children_html;
    foreach ($children as $child) {
      $children_html = $children_html."
        <form method='post' action=''>
          <input type='hidden' name='id_child' value='".$child->id."' />
          <input style='whidth:100px; height:50px;' type='submit' name='show_pref' value='".$child->first_name."'/>
        </form>";
    }
    return $children_html;
  }

  public function get_days_form(){
    $children = $this->users_manager->get_children();
    $days_forms;
    foreach ($children as $child) {
      $days_forms = $days_forms.
                    "<tr>
                        <th>".$child->first_name."</th>
                        <td><input type='checkbox' name='lundi' /></td>
                        <td><input type='checkbox' name='mardi' /></td>
                        <td><input type='checkbox' name='mercredi' /></td>
                        <td><input type='checkbox' name='jeudi' /></td>
                        <td><input type='checkbox' name='vendredi' /></td>
                      </tr>";
    }
    return $days_forms;
  }

  public function update_content_add_child(){
    $no_child;
    global $wpdb;
    $sql = "SELECT * FROM {$wpdb->prefix}posts WHERE post_title = 'Mon compte' AND post_status = 'publish' AND post_type = 'page' ";
    $row = $wpdb->get_row($sql);
    $id_page = $row->ID;
    $html = $this->div_tribu_html().$this->div_pref_form_html($no_child).$this->div_commande_html();
    wp_update_post([
      'ID' => $id_page,
      'post_content' => $html
    ]);
  }

  public function update_content_child_added(){
    global $wpdb;
    $sql = "SELECT * FROM {$wpdb->prefix}posts WHERE post_title = 'Mon compte' AND post_status = 'publish' AND post_type = 'page' ";
    $row = $wpdb->get_row($sql);
    $id_page = $row->ID;
    $html = $this->div_tribu_html().$this->div_commande_html();
    wp_update_post([
      'ID' => $id_page,
      'post_content' => $html
    ]);
  }

  public function update_content_show_pref(){
    $id = $_POST['id_child'];
    $child_data = $this->users_manager->get_child($id);

    global $wpdb;
    $sql = "SELECT * FROM {$wpdb->prefix}posts WHERE post_title = 'Mon compte' AND post_status = 'publish' AND post_type = 'page' ";
    $row = $wpdb->get_row($sql);
    $id_page = $row->ID;
    $html = $this->div_tribu_html().$this->div_pref_form_html($child_data).$this->div_commande_html();
    wp_update_post([
      'ID' => $id_page,
      'post_content' => $html
    ]);
  }

/* ---------------- HTML --------------- */

  public function my_users_render(){
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

  public function div_tribu_html(){
    return "<div>
              <h2 >Tribu ".$_SESSION['user_data']->tribu."</h2>".
                $this->get_children_buttons()."
                <form id='children' method='post' action=''>
                  <div><input type='submit' name='add_child' value='Ajouter un enfant'/></div>
                </form>
            </div>
            <div id='preferences_form'>
            </div>";
  }

  public function div_pref_form_html($child_data){
    return "<form action='' method='post'>
              <div>
                <input type='text' name='last_name' value='".$child_data->last_name."' placeholder='Nom' />
                <input type='text' name='first_name' value='".$child_data->first_name."' placeholder='Prénom' />
                <input type='text' name='school' value='".$child_data->school."' placeholder='Ecole'/>
                <input type='text' name='classroom' value='".$child_data->classroom."' placeholder='Classe'>
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
              <input type='submit' name='save_choices' value='Ok'/>
            </form>";
  }

  public function div_commande_html(){
    return "<div id='commande'>
              <h1>Ma commande </h1>
              <form method='post' action=''>
                <div>
                  <table>
                    <tr>
                      <th></th>
                      <th>Lun</th>
                      <th>Mar</th>
                      <th>Mer</th>
                      <th>Jeu</th>
                      <th>Ven</th>
                    </tr>".
                      $this->get_days_form()."
                  </table>
                </div>
                <div id='prix_total' class='wrap'>
                  <strong>Total : </strong> 0 €
                </div><br/>
                <div>
                  <input type='submit' name='commander' value='Commander' />
                </div>
              </form>
            </div>";
  }


} // end class
