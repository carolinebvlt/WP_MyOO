<?php
class MyOO_Page_Builder
{
  public function __construct(){
    add_action('init', [$this, 'ajout_page_menu']);
  }

/****************  PAGE MENU  *****************/
  public function ajout_page_menu(){

    $_tartines = ['a', 'b', 'c', 'd', 'e']; /*AM*/
    $content = "<h1><u><i>Carte des tartines</i></u></h1>";
    foreach ($_tartines as $tartine) {
      $content = $content.'<p>'.$tartine.'</p>';
    }
    $postTitle = 'Menu';

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

/****************  PAGE ORDER  *****************/


} //end class
