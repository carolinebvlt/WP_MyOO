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
    include_once plugin_dir_path( __FILE__ ).'/MyOO_onglet.php';
    new MyOO_onglet();
    // include_once plugin_dir_path( __FILE__ ).'/MyOO_Page_Builder.php';
    // new MyOO_Page_Builder();
  }
}
new MyOO();
