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
    register_setting( 'portions_settings', 'S_1j' );
    register_setting( 'portions_settings', 'S_2j' );
    register_setting( 'portions_settings', 'S_3j' );
    register_setting( 'portions_settings', 'S_4j' );
    register_setting( 'portions_settings', 'S_5j' );
    register_setting( 'portions_settings', 'M_1j' );
    register_setting( 'portions_settings', 'M_2j' );
    register_setting( 'portions_settings', 'M_3j' );
    register_setting( 'portions_settings', 'M_4j' );
    register_setting( 'portions_settings', 'M_5j' );
    register_setting( 'portions_settings', 'L_1j' );
    register_setting( 'portions_settings', 'L_2j' );
    register_setting( 'portions_settings', 'L_3j' );
    register_setting( 'portions_settings', 'L_4j' );
    register_setting( 'portions_settings', 'L_5j' );
    register_setting( 'portions_settings', 'S_panic' );
    register_setting( 'portions_settings', 'M_panic' );
    register_setting( 'portions_settings', 'L_panic' );
    register_setting( 'portions_settings', 'supplement_fruit' );
  }

/* ---------- RENDERS ---------- */

  public function onglet_render(){
    echo '<h1>'.get_admin_page_title().'</h1>';
  }
  public function params_render(){
    echo '<div class="wrap theme-options-page"><h1>'.get_admin_page_title().'</h1></div>';
    ?>
    <div class="wrap">
      <form method="post" action="options.php">
        <?php
        settings_fields('portions_settings');
        do_settings_sections('portions_settings');
        ?>
        <fieldset>
          <legend><div class="wrap theme-options-page"><h2>Portions</h2></div></legend>
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
        </fieldset>

        <fieldset>
          <legend><div class="wrap theme-options-page"><h2>Prix</h2></div></legend>
          <div class="wrap theme-options-page">
            <table>
              <tr>
                <th></th>
                <th>1 jour</th>
                <th>2 jours</th>
                <th>3 jours</th>
                <th>4 jours</th>
                <th>5 jours</th>
                <th style="color:red">Panic</th>
              </tr>
              <tr>
                <th>Le Benjamin : </th>
                <td><input type="number" step=0.1 min=0 max=20 name="S_1j" value="<?php echo esc_attr( get_option('S_1j') ); ?>" />€</td>
                <td><input type="number" step=0.1 min=0 max=20 name="S_2j" value="<?php echo esc_attr( get_option('S_2j') ); ?>" />€</td>
                <td><input type="number" step=0.1 min=0 max=20 name="S_3j" value="<?php echo esc_attr( get_option('S_3j') ); ?>" />€</td>
                <td><input type="number" step=0.1 min=0 max=20 name="S_4j" value="<?php echo esc_attr( get_option('S_4j') ); ?>" />€</td>
                <td><input type="number" step=0.1 min=0 max=20 name="S_5j" value="<?php echo esc_attr( get_option('S_5j') ); ?>" />€</td>
                <td><input style="color:red" type="number" min=0 max=10 name="S_panic" value="<?php echo esc_attr( get_option('S_panic') ); ?>" />€</td>
              </tr>
              <tr>
                <th>La Cadette : </th>
                <td><input type="number" step=0.1 min=0 max=20 name="M_1j" value="<?php echo esc_attr( get_option('M_1j') ); ?>" />€</td>
                <td><input type="number" step=0.1 min=0 max=20 name="M_2j" value="<?php echo esc_attr( get_option('M_2j') ); ?>" />€</td>
                <td><input type="number" step=0.1 min=0 max=20 name="M_3j" value="<?php echo esc_attr( get_option('M_3j') ); ?>" />€</td>
                <td><input type="number" step=0.1 min=0 max=20 name="M_4j" value="<?php echo esc_attr( get_option('M_4j') ); ?>" />€</td>
                <td><input type="number" step=0.1 min=0 max=20 name="M_5j" value="<?php echo esc_attr( get_option('M_5j') ); ?>" />€</td>
                <td><input style="color:red" type="number" min=0 max=10 name="M_panic" value="<?php echo esc_attr( get_option('M_panic') ); ?>" />€</td>
              </tr>
              <tr>
                <th>L'Ainé : </th>
                <td><input type="number" step=0.1 min=0 max=20 name="L_1j" value="<?php echo esc_attr( get_option('L_1j') ); ?>" />€</td>
                <td><input type="number" step=0.1 min=0 max=20 name="L_2j" value="<?php echo esc_attr( get_option('L_2j') ); ?>" />€</td>
                <td><input type="number" step=0.1 min=0 max=20 name="L_3j" value="<?php echo esc_attr( get_option('L_3j') ); ?>" />€</td>
                <td><input type="number" step=0.1 min=0 max=20 name="L_4j" value="<?php echo esc_attr( get_option('L_4j') ); ?>" />€</td>
                <td><input type="number" step=0.1 min=0 max=20 name="L_5j" value="<?php echo esc_attr( get_option('L_5j') ); ?>" />€</td>
                <td><input style="color:red" type="number" min=0 max=10 name="L_panic" value="<?php echo esc_attr( get_option('L_panic') ); ?>" />€</td>
              </tr>
            </table>
          </div>

          <div class="wrap theme-options-page"><h4>Suppléments</h4>
            <table>
              <tr>
                <th>1 fruit : </th>
                <td><input type="number" step=0.1 min=0 max=2 name="supplement_fruit" value="<?php echo esc_attr( get_option('supplement_fruit') ); ?>" />€</td>
              </tr>
            </table>
          </div>

        </fieldset>

        <?php
        submit_button('Enregistrer', 'primary', 'submit_tartines_settings', true);
        ?>
      </form>
    </div>
    <?php
  }
}
