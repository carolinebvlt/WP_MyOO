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
      'id_chef_tribu' => $_SESSION['user_data']->id,
      'ids_orders'    => $ids_str,
      'montant'       => $count,
      'date_order'    => $date->format('d-m-Y H:i:s')
    ]);
  }

  public function get_orders($monday){
    global $wpdb;
    $data = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}tartinette_single_orders WHERE monday = '$monday' ");
    return $data;
  }

  public function get_orders_by_id_chef($id){
    global $wpdb;
    return  $wpdb->get_results("SELECT * FROM {$wpdb->prefix}tartinette_orders WHERE id_chef_tribu = '$id' ");
  }

  public function hello(){
    echo 'hello';
  }
}
