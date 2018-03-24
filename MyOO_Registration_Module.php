<?php
class MyOO_Registration_Module
{
  public function __construct(){
    add_action('init', [$this, 'add_registration_page']);
  }
  public function register_form(){
    return
    '<form action="" method="post">
      <input type="text" name="nom" placeholder="Nom"/>
      <input type="text" name="prenom" placeholder="Prénom"/>
      <input type="text" name="tel" placeholder="Téléphone (ex : 0400000000)"/>
      <input type="text" name="email" placeholder="Email"/>
      <input type="text" name="tribu" placeholder="Nom de la tribu (ex : Dream Team)"/>
      <input type="password" name="password" placeholder="Mot de passe"/>
      <input type="password" name="password_check" placeholder="Confirmation du mot de passe"/>
      <input type="submit" name="submit_inscription" value="S\'inscrire">
    </form>'
    ;
  }
  public function add_registration_page(){
    $content = $this->register_form();
    $postTitle = 'Inscription';

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
