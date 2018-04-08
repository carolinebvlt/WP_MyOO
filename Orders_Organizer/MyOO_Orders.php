<?php
// echo '<pre>';
// var_dump();
// echo '</pre>';
/*
  - Ajout sous-menu "Orders"
*/

class MyOO_Orders
{
  private $orders_manager;

  public function __construct(){
    add_action('admin_menu', [$this, 'add_admin_menu'], 30);
    include_once plugin_dir_path( __FILE__ ).'/MyOO_Orders_Manager.php';
    $this->orders_manager = new MyOO_Orders_Manager();
  }

  public function add_admin_menu(){
    add_submenu_page('myoo', 'Commandes', 'Commandes', 'manage_options', 'myoo_orders', [$this, 'orders_render']);
  }

  public function orders_render(){
    echo $this->my_fieldset();
    if(isset($_POST['submit_display_orders'])){
      switch ($_POST['display_orders']) {
        case 'day' : $this->get_list_day($_POST['date_day'])     ; break;
        case 'week': $this->get_list_week($_POST['date_monday']) ; break;
        case 'name': $this->get_list_name($_POST['search_name']) ; break;
      }
    }
  }

  private function get_list_day($day){
  }

  private function get_list_week($monday){
    // $monday = str "Y-m-d"
    // monday (db) = str "d-m-Y"
    $format_a_modifier = new DateTime($monday);
    $_monday = $format_a_modifier->format('d-m-Y');
    $data = $this->orders_manager->get_orders($_monday);
    $this->display_list_week($data);
  }

  private function get_list_name($name){
  }

  private function my_fieldset(){
    $date = new DateTime();
    return "
      <form method='post' action=''>
        <div style='display:flex;justify-content:space-around;padding:20px;margin:20px;'>
          <div style='width:20%;'>
            <input type='radio' name='display_orders' value='day' style='display:block;margin:auto;' checked/>  <br/>
            <h3 style='text-align:center'>Jour</h3>
            <input type='date' name='date_day' value='".$date->format('Y-m-d')."' style='display:block;margin:auto;'/>
          </div>
          <div style='width:20%'>
            <input type='radio' name='display_orders' value='week' style='display:block;margin:auto;'/> <br/>
            <h3 style='text-align:center'>Semaine</h3>
            <input type='date' name='date_monday' value='".$this->next_monday()."' style='display:block;margin:auto;'/>
            <p style='text-align:center;margin-bottom:0;'>(Sélectionnez le lundi)</p>
          </div>
          <div style='width:20%'>
            <input type='radio' name='display_orders' value='name' style='display:block;margin:auto;'/> <br/>
            <h3 style='text-align:center'>Nom</h3>
            <input type='text' name='search_name' placeholder='Exemple : Bieuvelet' style='display:block;margin:auto;text-align:center;' />
          </div>
        </div>
        <input type='submit' name='submit_display_orders' value='Afficher les commandes'style='display:block;margin:auto;'/>
      </form><br/><br/>";
  }

  private function next_monday(){
    $date = new DateTime();
    $D = $date->format('N');
    switch ($D) {
      case '1': $_interval = 'P0D' ; break;
      case '2': $_interval = 'P6D' ; break;
      case '3': $_interval = 'P5D' ; break;
      case '4': $_interval = 'P4D' ; break;
      case '5': $_interval = 'P3D' ; break;
      case '6': $_interval = 'P2D' ; break;
      case '7': $_interval = 'P1D' ; break;
    }
    $interval = new DateInterval($_interval);
    $next_monday = new DateTime();
    $next_monday->add($interval);
    return $next_monday->format('Y-m-d');
  }

  private function one_day_html(){
    return "<table style='border:solid black 1px'>
      <tr>
        <th >Date : </th>
        <td></td>
      </tr>
      <tr>
        <th >Nbr commandes : </th>
        <td>X commandes</td>
      </tr>
      <tr>
        <th >Nbr écoles : </th>
        <td>X écoles</td>
      </tr>
      <tr>
        <th>Total paiements : </th>
        <td>X €</td>
      </tr>
    </table><br/>

    <div class='wrap theme-options-page'>
      <h3>Commande totale</h3>
      <table>
        <tr>
          <th>Pain blanc : </th>
          <td>X tranches</td>
        </tr>
        <tr>
          <th>Pain 5 céréales : </th>
          <td>X tranches</td>
        </tr>
        <tr>
          <th>Baguette : </th>
          <td>X baguettes</td>
        </tr>
        <tr>
          <th>Fruits : </th>
          <td>X fruits</td>
        </tr>
      </table><br/>

      <h3>Par composition</h3>
      <table>
        <tr>
          <th style='width:10%'></th>
          <th style='width:10%'>Classique</th>
          <th style='width:10%'>Dago</th>
          <th style='width:10%'>Fromage</th>
          <th style='width:10%'>Autre Fromage</th>
          <th style='width:10%'>Italien</th>
          <th style='width:10%'>Halal</th>
        </tr>
        <tr style='text-align:center'>
          <th>Pain blanc<br/><i>(nbr de tranches)</i></th>
          <td>X </td>
          <td>X </td>
          <td>X </td>
          <td>X </td>
          <td>X </td>
          <td>X </td>
        </tr>
        <tr style='text-align:center'>
          <th>Pain 5 céréales<br/><i>(nbr de tranches)</i></th>
          <td>X </td>
          <td>X </td>
          <td>X </td>
          <td>X </td>
          <td>X </td>
          <td>X </td>
        </tr>
        <tr style='text-align:center'>
          <th>1/4 de baguette<br/><i>(nbr portions)</i></th>
          <td>X </td>
          <td>X </td>
          <td>X </td>
          <td>X </td>
          <td>X </td>
          <td>X </td>
        </tr>
        <tr style='text-align:center'>
          <th>1/3 de baguette<br/><i>(nbr portions)</i></th>
          <td>X </td>
          <td>X </td>
          <td>X </td>
          <td>X </td>
          <td>X </td>
          <td>X </td>
        </tr>
        <tr style='text-align:center'>
          <th>1/2 de baguette<br/><i>(nbr portions)</i></th>
          <td>X </td>
          <td>X </td>
          <td>X </td>
          <td>X </td>
          <td>X </td>
          <td>X </td>
        </tr>
      </table><br/>
    </div><br/>";
  }

  private function display_list_week($data){
    // echo '<pre>';
    // print_r($data);
    // echo '</pre>';

    echo "
    <div class='wrap' style='border:solid black 1px; border-radius:10px;'>
      <table style='width:90%;margin:auto;'>
        <tr style='margin-bottom:20px;'>
          <th style='width:15%;text-align:center; border-bottom:solid black 1px;'><h3>Nom</h3></th>
          <th style='width:15%;text-align:center;border-bottom:solid black 1px;'><h3>Prénom</h3></th>
          <th style='width:15%;text-align:center;border-bottom:solid black 1px;'><h3>Ecole</h3></th>
          <th style='width:15%;text-align:center;border-bottom:solid black 1px;'><h3>Pain</h3></th>
          <th style='width:5%;text-align:center;border-bottom:solid black 1px;'><h3>Portion</h3></th>
          <th style='width:5%;text-align:center;border-bottom:solid black 1px;'><h3>Fruits</h3></th>
          <th style='width:5%;text-align:center;border-bottom:solid black 1px;'><h3>Lun</h3></th>
          <th style='width:5%;text-align:center;border-bottom:solid black 1px;'><h3>Mar</h3></th>
          <th style='width:5%;text-align:center;border-bottom:solid black 1px;'><h3>Mer</h3></th>
          <th style='width:5%;text-align:center;border-bottom:solid black 1px;'><h3>Jeu</h3></th>
          <th style='width:5%;text-align:center;border-bottom:solid black 1px;'><h3>Ven</h3></th>
          <th style='width:5%;text-align:center;border-bottom:solid black 1px;'><h3>€</h3></th>
        </tr>
      ";

    foreach ($data as $commande) {
      echo "
        <tr style='line-height:40px;'>
          <td style='width:15%;text-align:center;'>Bieuvelet</td>
          <td style='width:15%;text-align:center;'>Caroline</td>
          <td style='width:15%;text-align:center;'>Sainte-Famille</td>
          <td style='width:15%;text-align:center;'>Baguette</td>
          <td style='width:5%;text-align:center;'>L</td>
          <td style='width:5%;text-align:center;'>1</td>
          <td style='width:5%;text-align:center;'>-</td>
          <td style='width:5%;text-align:center;'>-</td>
          <td style='width:5%;text-align:center;'>X</td>
          <td style='width:5%;text-align:center;'>-</td>
          <td style='width:5%;text-align:center;'>X</td>
          <td style='width:5%;text-align:center;'>10.2 €</td>
        </tr>";
    }

    echo "</table></div>";
  }

  private function hello(){
    return "hello";
  }

}
