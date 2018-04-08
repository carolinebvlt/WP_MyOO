<?php
// echo '<pre>';
// var_dump();
// echo '</pre>';
/*
  - Ajout sous-menu "Orders"
*/

class MyOO_Orders
{
  private $orders_manager,
          $users_manager;

  public function __construct(){
    add_action('admin_menu', [$this, 'add_admin_menu'], 30);
    include_once plugin_dir_path( __FILE__ ).'/MyOO_Orders_Manager.php';
    $this->orders_manager = new MyOO_Orders_Manager();
    include_once plugin_dir_path( __FILE__ ).'../Users_Organizer/MyOO_Users_Manager.php';
    $this->users_manager = new MyOO_Users_Manager();
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
    $this->display_list_week($data,$_monday);
  }

  private function get_list_name($name){
  }

  private function my_fieldset(){
    $date = new DateTime();
    return "
      <form method='post' action=''>
        <div style='display:flex;justify-content:space-around;padding:20px;margin:20px;'>
          <div style='width:20%;'>
            <input type='radio' name='display_orders' value='day' style='display:block;margin:auto;' />  <br/>
            <h3 style='text-align:center'>Jour</h3>
            <input type='date' name='date_day' value='".$date->format('Y-m-d')."' style='display:block;margin:auto;'/>
          </div>
          <div style='width:20%'>
            <input type='radio' name='display_orders' value='week' style='display:block;margin:auto;' checked/> <br/>
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

  private function display_list_week($data, $monday){
    // echo '<pre>';
    // print_r($data);
    // echo '</pre>';
    $total = 0;
    $count_fruits_lun = 0;
    $count_fruits_mar = 0;
    $count_fruits_mer = 0;
    $count_fruits_jeu = 0;
    $count_fruits_ven = 0;
    $count_lun_baguette = 0;
    $count_mar_baguette = 0;
    $count_mer_baguette = 0;
    $count_jeu_baguette = 0;
    $count_ven_baguette = 0;
    $count_lun_blanc = 0;
    $count_mar_blanc = 0;
    $count_mer_blanc = 0;
    $count_jeu_blanc = 0;
    $count_ven_blanc = 0;
    $count_lun_cereales = 0;
    $count_mar_cereales = 0;
    $count_mer_cereales = 0;
    $count_jeu_cereales = 0;
    $count_ven_cereales = 0;

    foreach ($data as $commande) {
      $total += $commande->montant;
      if($commande->fruit === '1'){

        if($commande->lun === '1'){
          $count_fruits_lun += 1;
        }
        if($commande->mar === '1'){
          $count_fruits_mar += 1;
        }
        if($commande->mer === '1'){
          $count_fruits_mer += 1;
        }
        if($commande->jeu === '1'){
          $count_fruits_jeu += 1;
        }
        if($commande->ven === '1'){
          $count_fruits_ven += 1;
        }
      }

      switch ($commande->pain) {
        case 'blanc':
          $Q = (int)get_option($commande->portion."_tartines");

          if($commande->lun === '1'){
            $count_lun_blanc += $Q;
          }
          if($commande->mar === '1'){
            $count_mar_blanc += $Q;
          }
          if($commande->mer === '1'){
            $count_mer_blanc += $Q;
          }
          if($commande->jeu === '1'){
            $count_jeu_blanc += $Q;
          }
          if($commande->ven === '1'){
            $count_ven_blanc += $Q;
          }
          break;

        case 'cereales':
          $Q = (int)get_option($commande->portion."_tartines");
          if($commande->lun === '1'){
            $count_lun_cereales += $Q;
          }
          if($commande->mar === '1'){
            $count_mar_cereales += $Q;
          }
          if($commande->mer === '1'){
            $count_mer_cereales += $Q;
          }
          if($commande->jeu === '1'){
            $count_jeu_cereales += $Q;
          }
          if($commande->ven === '1'){
            $count_ven_cereales += $Q;
          }
          break;

        case 'baguette':
          $numerateur_lun_baguette = 0;
          $numerateur_mar_baguette = 0;
          $numerateur_mer_baguette = 0;
          $numerateur_jeu_baguette = 0;
          $numerateur_ven_baguette = 0;
          if($commande->lun === '1'){
            $numerateur_lun_baguette += 1;
          }
          if($commande->mar === '1'){
            $numerateur_mar_baguette += 1;
          }
          if($commande->mer === '1'){
            $numerateur_mer_baguette += 1;
          }
          if($commande->jeu === '1'){
            $numerateur_jeu_baguette += 1;
          }
          if($commande->ven === '1'){
            $numerateur_ven_baguette += 1;
          }
          $denominateur_commande = (int)get_option($commande->portion."_baguette");
          $count_lun_baguette = $numerateur_lun_baguette / $denominateur_commande;
          $count_mar_baguette = $numerateur_mar_baguette / $denominateur_commande;
          $count_mer_baguette = $numerateur_mer_baguette / $denominateur_commande;
          $count_jeu_baguette = $numerateur_jeu_baguette / $denominateur_commande;
          $count_ven_baguette = $numerateur_ven_baguette / $denominateur_commande;
          break;
      }
    }

    $count_fruits = $count_fruits_lun + $count_fruits_mar + $count_fruits_mer + $count_fruits_jeu + $count_fruits_ven;
    $count_cereales = $count_lun_cereales + $count_mar_cereales + $count_mer_cereales + $count_jeu_cereales + $count_ven_cereales;
    $count_blanc = $count_lun_blanc + $count_mar_blanc + $count_mer_blanc + $count_jeu_blanc + $count_ven_blanc;
    $count_baguette = $count_lun_baguette + $count_mar_baguette + $count_mer_baguette + $count_jeu_baguette + $count_ven_baguette;

    $count_lun_baguette = ($count_lun_baguette === 0) ? '-' : $count_lun_baguette;
    $count_mar_baguette = ($count_mar_baguette === 0) ? '-' : $count_mar_baguette;
    $count_mer_baguette = ($count_mer_baguette === 0) ? '-' : $count_mer_baguette;
    $count_jeu_baguette = ($count_jeu_baguette === 0) ? '-' : $count_jeu_baguette;
    $count_ven_baguette = ($count_ven_baguette === 0) ? '-' : $count_ven_baguette;
    $count_lun_blanc    = ($count_lun_blanc === 0)    ? '-' : $count_lun_blanc;
    $count_mar_blanc    = ($count_mar_blanc === 0)    ? '-' : $count_mar_blanc;
    $count_mer_blanc    = ($count_mer_blanc === 0)    ? '-' : $count_mer_blanc;
    $count_jeu_blanc    = ($count_jeu_blanc === 0)    ? '-' : $count_jeu_blanc;
    $count_ven_blanc    = ($count_ven_blanc === 0)    ? '-' : $count_ven_blanc ;
    $count_lun_cereales = ($count_lun_cereales === 0) ? '-' : $count_lun_cereales;
    $count_mar_cereales = ($count_mar_cereales === 0) ? '-' : $count_mar_cereales;
    $count_mer_cereales = ($count_mer_cereales === 0) ? '-' : $count_mer_cereales;
    $count_jeu_cereales = ($count_jeu_cereales === 0) ? '-' : $count_jeu_cereales ;
    $count_ven_cereales = ($count_ven_cereales === 0) ? '-' : $count_ven_cereales;
    $count_fruits_lun    = ($count_fruits_lun === 0)    ? '-' : $count_fruits_lun;
    $count_fruits_mar    = ($count_fruits_mar === 0)    ? '-' : $count_fruits_mar;
    $count_fruits_mer    = ($count_fruits_mer === 0)    ? '-' : $count_fruits_mer;
    $count_fruits_jeu    = ($count_fruits_jeu === 0)    ? '-' : $count_fruits_jeu;
    $count_fruits_ven    = ($count_fruits_ven === 0)    ? '-' : $count_fruits_ven ;
    $count_fruits    = ($count_fruits === 0)    ? '-' : $count_fruits ;
    $count_cereales    = ($count_cereales === 0)    ? '-' : $count_cereales." tartines" ;
    $count_baguette    = ($count_baguette === 0)    ? '-' : ceil($count_baguette)." baguette(s)" ;
    $count_blanc    = ($count_blanc === 0)    ? '-' : $count_blanc." tartines" ;

    echo "
    <div class='wrap' style='width:60%;margin:auto;border:solid black 1px; border-radius:10px;padding:20px;margin-bottom:50px;'>
      <h2 style='text-align:center;'>Résumé des commandes de la <strong>semaine du ".$monday."</strong></h2>
      <table style='margin:auto;'>
        <tr>
          <th>Nombre :</th>
          <td>".count($data)."</td>
        </tr>
        <tr>
          <th>Total :</th>
          <td>".$total." €</td>
        </tr>
      </table>
          <table style='width:80%;margin:auto;border:solid chartreuse 1px;border-radius:10px;padding:10px;margin-bottom:10px;'>
            <tr>
              <th></th>
              <th>Lun</th>
              <th>Mar</th>
              <th>Mer</th>
              <th>Jeu</th>
              <th>Ven</th>
              <th>TOTAL</th>
            </tr>
            <tr>
              <th>Baguette </th>
              <td style='text-align:center;'>".$count_lun_baguette."</td>
              <td style='text-align:center;'>".$count_mar_baguette."</td>
              <td style='text-align:center;'>".$count_mer_baguette."</td>
              <td style='text-align:center;'>".$count_jeu_baguette."</td>
              <td style='text-align:center;'>".$count_ven_baguette."</td>
              <td style='text-align:center;'>".$count_baguette."</td>
            </tr>
            <tr>
              <th>Pain blanc </th>
              <td style='text-align:center;'>".$count_lun_blanc."</td>
              <td style='text-align:center;'>".$count_mar_blanc."</td>
              <td style='text-align:center;'>".$count_mer_blanc."</td>
              <td style='text-align:center;'>".$count_jeu_blanc."</td>
              <td style='text-align:center;'>".$count_ven_blanc."</td>
              <td style='text-align:center;'>".$count_blanc."</td>
            </tr>
            <tr>
              <th>5 céréales </th>
              <td style='text-align:center;'>".$count_lun_cereales."</td>
              <td style='text-align:center;'>".$count_mar_cereales."</td>
              <td style='text-align:center;'>".$count_mer_cereales."</td>
              <td style='text-align:center;'>".$count_jeu_cereales."</td>
              <td style='text-align:center;'>".$count_ven_cereales."</td>
              <td style='text-align:center;'>".$count_cereales."</td>
            </tr>
            <tr>
              <th>Fruits </th>
              <td style='text-align:center;'>".$count_fruits_lun."</td>
              <td style='text-align:center;'>".$count_fruits_mar."</td>
              <td style='text-align:center;'>".$count_fruits_mer."</td>
              <td style='text-align:center;'>".$count_fruits_jeu."</td>
              <td style='text-align:center;'>".$count_fruits_ven."</td>
              <td style='text-align:center;'>".$count_fruits."</td>
            </tr>
          </table>
    </div>";

    echo "
    <div class='wrap' style='padding-top:20px;padding-bottom:20px;margin-top:30px;'>
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
      $child = $this->users_manager->get_child($commande->id_child);
      $fruit = ($commande->fruit === '1') ? "1" : "-";
      $lun = ($commande->lun === "1") ? "X" : "-";
      $mar = ($commande->mar === "1") ? "X" : "-";
      $mer = ($commande->mer === "1") ? "X" : "-";
      $jeu = ($commande->jeu === "1") ? "X" : "-";
      $ven = ($commande->ven === "1") ? "X" : "-";
      echo "
        <tr style='line-height:40px;'>
          <td style='width:15%;text-align:center;'>".$child->last_name."</td>
          <td style='width:15%;text-align:center;'>".$child->first_name."</td>
          <td style='width:15%;text-align:center;'>".$child->school."</td>
          <td style='width:15%;text-align:center;'>".$commande->pain."</td>
          <td style='width:5%;text-align:center;'>".$commande->portion."</td>
          <td style='width:5%;text-align:center;'>".$fruit."</td>
          <td style='width:5%;text-align:center;'>".$lun."</td>
          <td style='width:5%;text-align:center;'>".$mar."</td>
          <td style='width:5%;text-align:center;'>".$mer."</td>
          <td style='width:5%;text-align:center;'>".$jeu."</td>
          <td style='width:5%;text-align:center;'>".$ven."</td>
          <td style='width:5%;text-align:center;'>".$commande->montant." €</td>
        </tr>";
    }

    echo "</table></div>";
  }

  private function hello(){
    return "hello";
  }

}
