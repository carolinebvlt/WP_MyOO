<?php
/*
Plugin Name: MyOO
Description: Orders organizer pour Tartinette
Version: 0.1
Author: CeaB
*/
class MyOO
{
  public function __construct(){
    include_once plugin_dir_path( __FILE__ ).'/Orders_Organizer/MyOO_Orders_Organizer.php';
    new MyOO_Orders_Organizer();
    include_once plugin_dir_path( __FILE__ ).'/Users_Organizer/MyOO_Users_Organizer.php';
    new MyOO_Users_Organizer();
    include_once plugin_dir_path( __FILE__ ).'MyOO_DB_Installer.php';
    new MyOO_DB_Installer();
    register_activation_hook(__FILE__, ['MyOO_DB_Installer', 'install_db']);
  }
}
new MyOO();
