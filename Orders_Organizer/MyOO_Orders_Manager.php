<?php
class MyOO_Orders_Manager
{
  public function save_single_order($order){
    global $wpdb;
    $wpdb->insert("{$wpdb->prefix}tartinette_single_orders", [
      'id_child'    => $order['id_child'],
      'pain'        => $order['pain'],
      'portion'     => $order['portion'],
      'next_monday' => $order['next_monday'],
      'lun'         => $order['days']['lun'],
      'mar'         => $order['days']['mar'],
      'mer'         => $order['days']['mer'],
      'jeu'         => $order['days']['jeu'],
      'ven'         => $order['days']['ven']
    ]);
    // return $wpdb->insert_id;
  }
}
