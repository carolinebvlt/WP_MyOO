<?php

/*
  - création custom_post_type 'tartine' -> génère l'onglet 'Tartines' du dashboard
  - meta box pour définir la composition d'une tartine (max 5 ingrédients)
*/

class MyOO_CPT_Tartines
{
  public function __construct(){
    add_action('init', [$this, 'register_tartine_type'], 0 );
    add_action('add_meta_boxes',[$this, 'init_composition_tartine_box']);
    add_action('save_post',[$this,'save_composition_tartine_box']);
  }

  public function init_composition_tartine_box(){
    add_meta_box('composition_tartine', 'Composition de la tartine', [$this, 'composition_tartine_box_render'], 'tartine');
  }

  public function save_composition_tartine_box($post_ID){
    $ingredients = [
      'i1' => esc_html($_POST['ingredient1']),
      'i2' => esc_html($_POST['ingredient2']),
      'i3' => esc_html($_POST['ingredient3']),
      'i4' => esc_html($_POST['ingredient4']),
      'i5' => esc_html($_POST['ingredient5']),
    ];
    update_post_meta($post_ID, '_tartine', $ingredients);
  }

  public function composition_tartine_box_render($post){
    $ingredients = get_post_meta($post->ID,'_tartine',true);
    echo "<input type='text' name='ingredient1' placeholder='Ingrédient 1' value='".$ingredients['i1']."'/><br/>";
    echo "<input type='text' name='ingredient2' placeholder='Ingrédient 2' value='".$ingredients['i2']."'/><br/>";
    echo "<input type='text' name='ingredient3' placeholder='Ingrédient 3' value='".$ingredients['i3']."'/><br/>";
    echo "<input type='text' name='ingredient4' placeholder='Ingrédient 4' value='".$ingredients['i4']."'/><br/>";
    echo "<input type='text' name='ingredient5' placeholder='Ingrédient 5' value='".$ingredients['i5']."'/><br/>";
  }

/************************  TARTINE_TYPE (custum post type)  *********************************/
// Register Custom Post Type
  function register_tartine_type() {

    $labels = array(
      'name'                  => _x( 'Tartines', 'Post Type General Name', 'text_domain' ),
      'singular_name'         => _x( 'Tartine', 'Post Type Singular Name', 'text_domain' ),
      'menu_name'             => __( 'Tartines', 'text_domain' ),
      'name_admin_bar'        => __( 'Post Type', 'text_domain' ),
      'archives'              => __( 'Item Archives', 'text_domain' ),
      'attributes'            => __( 'Item Attributes', 'text_domain' ),
      'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
      'all_items'             => __( 'Toutes les tartines', 'text_domain' ),
      'add_new_item'          => __( 'Ajouter une tartine au menu', 'text_domain' ),
      'add_new'               => __( 'Ajouter une tartine', 'text_domain' ),
      'new_item'              => __( 'Nouvelle tartine', 'text_domain' ),
      'edit_item'             => __( 'Edit Item', 'text_domain' ),
      'update_item'           => __( 'Update Item', 'text_domain' ),
      'view_item'             => __( 'View Item', 'text_domain' ),
      'view_items'            => __( 'View Items', 'text_domain' ),
      'search_items'          => __( 'Search Item', 'text_domain' ),
      'not_found'             => __( 'Not found', 'text_domain' ),
      'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
      'featured_image'        => __( 'Featured Image', 'text_domain' ),
      'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
      'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
      'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
      'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
      'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
      'items_list'            => __( 'Items list', 'text_domain' ),
      'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
      'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
    );
    $args = array(
      'label'                 => __( 'Tartine', 'text_domain' ),
      'labels'                => $labels,
      'supports'              => array('title'),
      'taxonomies'            => array( ),
      'hierarchical'          => false,
      'public'                => false,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'menu_position'         => 25,
      'menu_icon'             => 'dashicons-store',
      'show_in_admin_bar'     => true,
      'show_in_nav_menus'     => true,
      'can_export'            => true,
      'has_archive'           => true,
      'exclude_from_search'   => false,
      'publicly_queryable'    => true,
      'capability_type'       => 'page',
    );
  register_post_type('tartine', $args );

  }


}
