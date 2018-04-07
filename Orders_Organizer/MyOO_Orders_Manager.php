<?php
class MyOO_Orders_Manager
{
  public function save_single_order($order){
    global $wpdb;
    $wpdb->insert("{$wpdb->prefix}tartinette_single_orders", [
      'id_child'    => $order['id_child'],
      'pain'        => $order['pain'],
      'portion'     => $order['portion'],
      'fruit'       => $order['fruit'],
      'monday'      => $order['next_monday'],
      'lun'         => $order['days']['lun'],
      'mar'         => $order['days']['mar'],
      'mer'         => $order['days']['mer'],
      'jeu'         => $order['days']['jeu'],
      'ven'         => $order['days']['ven'],
      'montant'     => $order['montant']
    ]);
    return $wpdb->insert_id;
  }

  public function save_order($ids_order,$montants){
    $ids_str = implode(',',$ids_order);
    $count = 0;
    foreach ($montants as $cout) {
      $count += $cout;
    }
    global $wpdb;
    $date = new DateTime();
    $wpdb->insert("{$wpdb->prefix}tartinette_orders", [
      'ids_orders' => $ids_str,
      'montant'    => $count,
      'date_order' => $date->format('d-m-Y H:i:s')
    ]);
  }
}
