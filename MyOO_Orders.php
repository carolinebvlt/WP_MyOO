<?php
class MyOO_Orders
{
  public function __construct(){
    add_action('admin_menu', [$this, 'add_admin_menu'], 30);
  }
  public function add_admin_menu(){
    add_submenu_page('myoo', 'Commandes', 'Commandes', 'manage_options', 'myoo_orders', [$this, 'orders_render']);
  }
  public function orders_render(){
    $date = new DateTime();
    echo '<div class="wrap theme-options-page"><h1>'.get_admin_page_title().'</h1></div><br/>';
    ?>
      <table style="border:solid black 1px">
        <tr>
          <th >Date : </th>
          <td><?=$date->format('d-m-Y')?></td>
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

      <div class="wrap theme-options-page">
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
            <th style="width:10%"></th>
            <th style="width:10%">Classique</th>
            <th style="width:10%">Dago</th>
            <th style="width:10%">Fromage</th>
            <th style="width:10%">Autre Fromage</th>
            <th style="width:10%">Italien</th>
            <th style="width:10%">Halal</th>
          </tr>
          <tr style="text-align:center">
            <th>Pain blanc<br/><i>(nbr de tranches)</i></th>
            <td>X </td>
            <td>X </td>
            <td>X </td>
            <td>X </td>
            <td>X </td>
            <td>X </td>
          </tr>
          <tr style="text-align:center">
            <th>Pain 5 céréales<br/><i>(nbr de tranches)</i></th>
            <td>X </td>
            <td>X </td>
            <td>X </td>
            <td>X </td>
            <td>X </td>
            <td>X </td>
          </tr>
          <tr style="text-align:center">
            <th>1/4 de baguette<br/><i>(nbr portions)</i></th>
            <td>X </td>
            <td>X </td>
            <td>X </td>
            <td>X </td>
            <td>X </td>
            <td>X </td>
          </tr>
          <tr style="text-align:center">
            <th>1/3 de baguette<br/><i>(nbr portions)</i></th>
            <td>X </td>
            <td>X </td>
            <td>X </td>
            <td>X </td>
            <td>X </td>
            <td>X </td>
          </tr>
          <tr style="text-align:center">
            <th>1/2 de baguette<br/><i>(nbr portions)</i></th>
            <td>X </td>
            <td>X </td>
            <td>X </td>
            <td>X </td>
            <td>X </td>
            <td>X </td>
          </tr>
        </table><br/>
      </div><br/>

    <?php
  }

}
