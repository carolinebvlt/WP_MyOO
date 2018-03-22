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

    $content = "mon texte";
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

    // if(!$query->post){
    //   $page = [
    //       'post_title'   => $postTitle,
    //       'post_content' => $content,
    //       'post_status'  => 'publish',
    //       'post_author'  => 1,
    //       'post_type'    => 'page',
    //       'post_parent'  => 0
    //   ];
    //   $insert_id = wp_insert_post( $page );
    }
    // global $wpdb;

    // $query = $wpdb->prepare(
    //     'SELECT ID FROM ' . $wpdb->posts . '
    //         WHERE post_title = %s
    //         AND post_type = \'page\'
    //         AND post_status = \'publish\'',
    //     $postTitle
    // );
    // $wpdb->query( $query );


    // if ( $wpdb->num_rows ) {
    //     // Title already exists
    // }
    // else {
    //   $page = [
    //       'post_title'   => $postTitle,
    //       'post_content' => $content,
    //       'post_status'  => 'publish',
    //       'post_author'  => 1,
    //       'post_type'    => 'page',
    //       'post_parent'  => 0
    //   ];
    //   $insert_id = wp_insert_post( $page );
    // }
  // }
}
new MyOO();
