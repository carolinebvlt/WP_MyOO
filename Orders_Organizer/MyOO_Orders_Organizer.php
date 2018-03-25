<?php
/*
  - Ajout de l'onglet "Orders Organizer" dans le menu du dashboard
  - Include : Orders, Statistics, Settings
*/

class MyOO_Orders_Organizer
{
  public function __construct(){
    add_action('admin_menu', [$this, 'add_admin_menu'], 20);
    include_once plugin_dir_path( __FILE__ ).'/MyOO_Orders.php';
    new MyOO_Orders();
    include_once plugin_dir_path( __FILE__ ).'/MyOO_Statistics.php';
    new MyOO_Statistics();
    include_once plugin_dir_path( __FILE__ ).'/MyOO_Settings.php';
    new MyOO_Settings();
  }
  public function add_admin_menu(){
    add_menu_page('My Orders Organizer', 'Orders Organizer', 'manage_options', 'myoo', [$this, 'home_render'], 'dashicons-book-alt', 26 );
  }

  public function  home_render(){
    echo '<div class="wrap theme-options-page"><h1>'.get_admin_page_title().'</h1></div>';
    ?>
    <h2>Commandes</h2>
    <p>Récapitulatif des commandes du jour</p>
    <h2>Statistiques</h2>
    <p>Statistiques des ventes</p>
    <h2>Paramètres</h2>
    <ul>
      <li>- Portions proposées</li>
      <li>- Prix</li>
    </ul>
    <?php
  }

}
