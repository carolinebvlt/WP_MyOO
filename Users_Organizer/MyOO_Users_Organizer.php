<?php
// echo '<pre>';
// var_dump();
// echo '</pre>';
session_start();

class MyOO_Users_Organizer
{
  private $pages_manager,
          $users_manager,
          $orders_manager;

  public function __construct(){
    include_once plugin_dir_path( __FILE__ ).'/MyOO_Users_Manager.php';
    $this->users_manager = new MyOO_Users_Manager();
    include_once plugin_dir_path( __FILE__ ).'../MyOO_Pages_Manager.php';
    $this->pages_manager = new MyOO_Pages_Manager();
    include_once plugin_dir_path( __FILE__ ).'../Orders_Organizer/MyOO_Orders_Manager.php';
    $this->orders_manager = new MyOO_Orders_Manager();
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

/*################################## ROUTEUR ######################################*/

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

    elseif (isset($_POST['save_order_week'])){
      $array_orders = $this->get_array_orders();
      $this->save_orders($array_orders);
    }

    elseif (isset($_POST['save_order_panic'])){

    }
  }

/*#################################################################################*/

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
    } // end script panic
    if(is_page('Tartinette')){
      if(!wp_script_is('home_script')){
        wp_register_script('home_script', plugin_dir_url(__FILE__) . '../assets/scripts/home_script.js');
        wp_enqueue_script('home_script');
      }
    }// end script home
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

  private function get_array_orders(){
    $les_regex = ['#^lundi#','#^mardi#','#^mercredi#','#^jeudi#','#^vendredi#'];
    foreach ($_POST as $key => $value) {
      foreach ($les_regex as $regex) {
        if( preg_match($regex,$key) && $value === 'on' ){
          $commandes[] = $key;
        }
      }
    }
    $commandes_par_enfant = [];
    foreach ($commandes as $commande) {
      $pieces = explode('_', $commande);
      if(empty($commandes_par_enfant)){
        $child = ['id' => $pieces[1]];
        $commandes_par_enfant[] = $child;
      }
      else{
        $y_est = false;
        foreach ($commandes_par_enfant as $_child) {
          if($pieces[1] === $_child['id']){
            $y_est = true;
          }
        }
        if($y_est === false){
          $child = ['id' => $pieces[1]];
          $commandes_par_enfant[] = $child;
        }
      }
    }
    $com_par_child = [];
    foreach ($commandes_par_enfant as $_child) {
      foreach ($commandes as $commande) {
        $pieces = explode('_', $commande);
        if($pieces[1]===$_child['id']){
          $_child[] = $commande;
        }
      }
      $com_par_child[] = $_child;
    }

    $commandes_finale = [];
    foreach ($com_par_child as $com_child) {
      $days = [];
      foreach ($com_child as $key => $value) {
        if($key !== 'id'){
          $pieces = explode('_', $value);
          $days[] = $pieces[0];
        }
      }
      $this_com = ['id' => $com_child['id'], $days];
      $commandes_finale[] = $this_com;
    }
    return $commandes_finale;
  }

  private function save_orders($array_orders){
    foreach ($array_orders as $order) {

      $id_child = $order['id'];
      $pain = $this->users_manager->get_child_params($id_child)->pain;
      $portion = $this->users_manager->get_child_params($id_child)->portion;
      $fruit = $this->users_manager->get_child_params($id_child)->fruit;
      $next_monday = $this->next_monday();
      $days = [
        'lun' => false,
        'mar' => false,
        'mer' => false,
        'jeu' => false,
        'ven' => false,
      ];
      foreach ($order[0] as $jour) {
        switch ($jour) {
          case 'lundi': $days['lun'] = true ; break;
          case 'mardi': $days['mar'] = true ; break;
          case 'mercredi': $days['mer'] = true ; break;
          case 'jeudi': $days['jeu'] = true ; break;
          case 'vendredi': $days['ven'] = true ; break;
        }
      }
      $order = [
        'id_child'    => $id_child,
        'pain'        => $pain,
        'portion'     => $portion,
        'fruit'       => $fruit,
        'next_monday' => $next_monday,
        'days'        => $days
      ];
      $montant = $this->get_cost($order);
      $order = [
        'id_child'    => $id_child,
        'pain'        => $pain,
        'portion'     => $portion,
        'fruit'       => $fruit,
        'next_monday' => $next_monday,
        'days'        => $days,
        'montant'     => $montant
      ];
      $id_order = $this->orders_manager->save_single_order($order);
      $ids_order[] = $id_order;
      $montants[] = $montant;
    }
    $this->orders_manager->save_order($ids_order, $montants);
  }

  private function next_monday(){
    $date = new DateTime();
    $D = $date->format('N');
    switch ($D) {
      case '1': $_interval = 'P7D' ; break;
      case '2': $_interval = 'P6D' ; break;
      case '3': $_interval = 'P5D' ; break;
      case '4': $_interval = 'P4D' ; break;
      case '5': $_interval = 'P3D' ; break;
      case '6': $_interval = 'P9D' ; break;
      case '7': $_interval = 'P8D' ; break;
    }
    $interval = new DateInterval($_interval);
    $next_monday = new DateTime();
    $next_monday->add($interval);
    return $next_monday->format('d-m-Y');
  }

  private function get_cost($order){
    $count = 0;
    foreach ($order['days'] as $day){
      if($day === true){
        $count += 1;
      }
    }
    $option_name = $order['portion']."_".$count."j";
    $montant_sans_fruit = get_option($option_name);
    if($order['fruit'] === '1'){
      $montant = $montant_sans_fruit + ((float)get_option('supplement_fruit') * $count);
    }
    else{
      $montant = (float)$montant_sans_fruit;
    }
    return $montant;
  }

  public function admin_users_html(){
    $data = $this->users_manager->get_all_users();
    echo "<table style='font-size:1.2em;width:80%;margin:auto;text-align:left;'>";
    echo "<tr style='line-height:50px;'>
            <th style='width:20%'><h3>Nom</h3></th>
            <th style='width:20%'><h3>Prénom</h3></th>
            <th style='width:20%;'><h3>Téléphone</h3></th>
            <th style='width:25%'><h3>Email</h3></th>
            <th style='width:15%'><h3>Tribu</h3></th>
          </tr>";
    foreach ($data as $user) {
      $a = str_split($user->phone);
      $phoneNbr = $a[0].$a[1].$a[2].$a[3]." / ".$a[4].$a[5]." . ".$a[6].$a[7]." . ".$a[8].$a[9];
      echo "<tr style='line-height:35px;'><td>".$user->last_name."</td><td>".$user->first_name."</td><td>".$phoneNbr."</td><td>".$user->email."</td><td>".$user->tribu."</td></tr>";
    }
    echo "</table>";
  }

} // end class
