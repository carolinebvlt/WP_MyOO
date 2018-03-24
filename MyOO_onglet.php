<?php
class MyOO_onglet
{
  public function __construct(){
    add_action('admin_menu', [$this, 'add_admin_menu']);
    add_action('admin_init', [$this, 'register_tartines_settings']);
  }
  public function add_admin_menu(){
    add_menu_page('My Orders Organizer', 'Orders Organizer', 'manage_options', 'myoo', [$this, 'onglet_render'], 'dashicons-book-alt', 26 );
    add_submenu_page('myoo', 'Paramètres', 'Paramètres', 'manage_options', 'myoo_params', [$this, 'params_render']);

  }
  public function register_tartines_settings(){
    register_setting( 'tartines_settings', 'portions' );
  }

/* ---------- RENDERS ---------- */

  public function onglet_render(){
    echo '<h1>'.get_admin_page_title().'</h1>';
  }
  public function params_render(){
    echo '<h1>'.get_admin_page_title().'</h1>';
    ?>
    <div class="wrap">
      <form method="post" action="options.php">
        <?php
        settings_fields('tartines_settings');
        do_settings_sections('tartines_settings');
        ?>
        <table class="form-table">
            <tr valign="top">
            <th scope="row">Portions</th>
            <td><input type="text" name="portions" value="<?php echo esc_attr( get_option('portions') ); ?>" /></td>
            </tr>
        </table>
        <?php
        submit_button('Enregistrer', 'primary', 'submit_tartines_settings', true);
        ?>
      </form>
    </div>
    <?php
  }
}
