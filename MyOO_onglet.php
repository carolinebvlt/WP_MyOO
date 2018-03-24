<?php
class MyOO_onglet
{
  public function __construct(){
    add_action('admin_menu', [$this, 'add_admin_menu']);
    add_action('admin_init', [$this, 'register_portions_settings']);
  }
  public function add_admin_menu(){
    add_menu_page('My Orders Organizer', 'Orders Organizer', 'manage_options', 'myoo', [$this, 'onglet_render'], 'dashicons-book-alt', 26 );
    add_submenu_page('myoo', 'Paramètres', 'Paramètres', 'manage_options', 'myoo_params', [$this, 'params_render']);

  }
  public function register_portions_settings(){
    register_setting( 'portions_settings', 'S_tartines' );
    register_setting( 'portions_settings', 'M_tartines' );
    register_setting( 'portions_settings', 'L_tartines' );
    register_setting( 'portions_settings', 'S_baguette' );
    register_setting( 'portions_settings', 'M_baguette' );
    register_setting( 'portions_settings', 'L_baguette' );
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
        settings_fields('portions_settings');
        do_settings_sections('portions_settings');
        ?>
        <table>
          <tr>
            <th><label>Le Benjamin : </label></th>
            <td><input type="number" min=0 max=10 name="S_tartines" value="<?php echo esc_attr( get_option('S_tartines') ); ?>" /> tranches de pain </td>
            <td>ou</td>
            <td>1/<input type="number" min=0 max=5 name="S_baguette" value="<?php echo esc_attr( get_option('S_baguette') ); ?>" /> de baguette</td>
          </tr>
          <tr>
            <th><label>La Cadette : </label></th>
            <td><input type="number" min=0 max=10 name="M_tartines" value="<?php echo esc_attr( get_option('M_tartines') ); ?>" /> tranches de pain </td>
            <td>ou</td>
            <td>1/<input type="number" min=0 max=5 name="M_baguette" value="<?php echo esc_attr( get_option('M_baguette') ); ?>" /> de baguette</td>
          </tr>
          <tr>
            <th><label>L'Ainé : </label></th>
            <td><input type="number" min=0 max=10 name="L_tartines" value="<?php echo esc_attr( get_option('L_tartines') ); ?>" /> tranches de pain </td>
            <td>ou</td>
            <td>1/<input type="number" min=0 max=5 name="L_baguette" value="<?php echo esc_attr( get_option('L_baguette') ); ?>" /> de baguette</td>
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
