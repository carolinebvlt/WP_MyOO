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
    add_action('init', [$this, 'ajout_page']);
  }
  public function ajout_page(){

    $_tartines = ['a', 'b', 'c', 'd'];
    $content = "<h1>Test</h1>";
    foreach ($_tartines as $tartine) {
      $content = $content.'<p>'.$tartine.'</p>';
    }
    $postTitle = 'BLOP';

    $args = [
      'post_status'=> 'publish',
      'post_type'=> 'page',
      'post_title'=> $postTitle
    ];
    $query = new WP_Query( $args );

    if($query->post->post_title !== $postTitle){
        $page = [
            'post_title'   => $postTitle,
            'post_content' => $content,
            'post_status'  => 'publish',
            'post_author'  => 1,
            'post_type'    => 'page',
            'post_parent'  => 0
        ];
        $insert_id = wp_insert_post( $page );
    }
  }
}
new MyOO();
