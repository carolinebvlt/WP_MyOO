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
  }
}
new MyOO();
