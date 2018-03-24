<?php
class MyOO_Orders_Organizer
{
  public function __construct(){
    add_action('admin_menu', [$this, 'add_admin_menu']);
    include_once plugin_dir_path( __FILE__ ).'/MyOO_Settings.php';
    new MyOO_Settings();
  }
  public function add_admin_menu(){
    add_menu_page('My Orders Organizer', 'Orders Organizer', 'manage_options', 'myoo', [$this, 'home_render'], 'dashicons-book-alt', 26 );
  }

  public function  home_render(){
    echo '<h1>'.get_admin_page_title().'</h1>';
  }

}
