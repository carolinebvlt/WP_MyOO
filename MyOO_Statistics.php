<?php
/*
  - Ajout sous-menu "Statistics"
*/

class MyOO_Statistics
{
  public function __construct(){
    add_action('admin_menu', [$this, 'add_admin_menu'], 40);
  }
  public function add_admin_menu(){
    add_submenu_page('myoo', 'Statistiques', 'Statistiques', 'manage_options', 'myoo_stats', [$this, 'stats_render']);
  }
  public function stats_render(){
    echo '<div class="wrap theme-options-page"><h1>'.get_admin_page_title().'</h1></div>';
  }
}
