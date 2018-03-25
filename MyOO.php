<?php
/*
Plugin Name: MyOO
Description: Orders organizer Plugin
Version: 0.1
Author: CeaB
*/
class MyOO
{
  public function __construct(){
    include_once plugin_dir_path( __FILE__ ).'/MyOO_CPT_Tartines.php';
    new MyOO_CPT_Tartines();
    include_once plugin_dir_path( __FILE__ ).'/MyOO_Orders_Organizer.php';
    new MyOO_Orders_Organizer();
    include_once plugin_dir_path( __FILE__ ).'/MyOO_Registration_Module.php';
    new MyOO_Registration_Module();
    include_once plugin_dir_path( __FILE__ ).'/MyOO_Account_Module.php';
    new MyOO_Account_Module();
  }
}
new MyOO();
