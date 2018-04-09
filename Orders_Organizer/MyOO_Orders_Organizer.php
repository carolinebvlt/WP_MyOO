<?php
/*
  - Ajout de l'onglet "Orders Organizer" dans le menu du dashboard
  - Include : Orders, Statistics, Settings
*/

class MyOO_Orders_Organizer
{
  private $Orders;

  public function __construct(){
    include_once plugin_dir_path( __FILE__ ).'/MyOO_Orders.php';
    $this->$Orders = new MyOO_Orders();
    include_once plugin_dir_path( __FILE__ ).'/MyOO_Statistics.php';
    new MyOO_Statistics();
    include_once plugin_dir_path( __FILE__ ).'/MyOO_Settings.php';
    new MyOO_Settings();
    add_action('admin_menu', [$this, 'add_admin_menu']);

  }
  public function add_admin_menu(){
    add_menu_page('My Orders Organizer', 'Orders Organizer', 'manage_options', 'myoo', [$this, 'orders_render'], 'dashicons-book-alt', 26 );
    add_submenu_page('myoo', 'Commandes', 'Commandes', 'manage_options', 'myoo', [$this, 'orders_render']);
  }

  public function orders_render(){
    echo $this->$Orders->my_fieldset();
    if(isset($_POST['submit_display_orders'])){
      if(isset($_POST['date_day'])){
        $this->$Orders->get_list_day($_POST['date_day']);
      }
      if(isset($_POST['date_monday'])){
        $this->$Orders->get_list_week($_POST['date_monday']);
      }
      if(isset($_POST['search_name'])){
        $this->$Orders->get_list_name($_POST['search_name']);
      }
    }
  }

}
