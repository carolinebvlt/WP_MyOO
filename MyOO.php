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
    include_once plugin_dir_path( __FILE__ ).'/MyOO_Menu.php';
    new MyOO_Menu();
    include_once plugin_dir_path( __FILE__ ).'/MyOO_Page_Builder.php';
    new MyOO_Page_Builder();
  }
}
new MyOO();
