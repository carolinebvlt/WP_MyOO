<?php
session_start();

class MyOO_Pages_Manager
{

  public function add_subscription_page(){
    $title = 'Inscription';
    $content = $this->subscription_html();
    $this->add_page($title, $content);
  }

  public function add_connexion_page(){
    $title = 'Connexion';
    $content = $this->connexion_html();
    $this->add_page($title, $content);
  }

  public function add_account_page(){
    $title = 'Mon compte';
    $content = $this->account_html();
    $this->add_page($title, $content);
  }

  public function add_tartinette_home_page(){
    $title = 'Tartinette';
    $content = $this->tartinette_html();
    $this->add_page($title, $content);
  }

  private function add_page($page_title, $page_content){
    global $wpdb;
    $sql = "SELECT * FROM {$wpdb->prefix}posts WHERE post_title = '$page_title' AND post_status = 'publish' AND post_type = 'page' ";
    $row = $wpdb->get_row($sql);
    if(is_null($row)){
      $page = [
            'post_title'   => $page_title,
            'post_content' => $page_content,
            'post_status'  => 'publish',
            'post_author'  => 1,
            'post_type'    => 'page',
            'post_parent'  => 0
      ];
      wp_insert_post( $page );
    }
  }

/*------------------- HTML --------------------*/

  private function subscription_html(){
    return '<div id="register_form" class="wrap">
              <form action="" method="post">
                <input type="text" name="last_name" placeholder="Nom"/>
                <input type="text" name="first_name" placeholder="Prénom"/>
                <input type="text" name="tribu" placeholder="Nom de la tribu"/>
                <input type="text" name="phone" placeholder="Téléphone (ex : 0400000000)"/>
                <input type="text" name="email" placeholder="Email"/>
                <input type="password" name="password" placeholder="Mot de passe"/>
                <input type="password" name="password_check" placeholder="Confirmation du mot de passe"/>
                <input type="submit" name="submit_registration" onclick="show(connexion_form, register_form)" value="S\'inscrire">
              </form>
            </div>';
  }

  private function connexion_html(){
    return '<div id="connexion_form" class="wrap">
              <form method="post" action="">
                <input type="text" name="email" placeholder="Votre email"/>
                <input type="password" name="password" placeholder="Mot de passe"/>
                <input type="submit" name="submit_connexion" value="Se connecter"/>
              </form>
              <p>ou</p>
              <form method="post" action="">
                <input type="submit" name="go_subscription" value="S\'inscrire"/>
              </form>

            </div>';
  }

  private function account_html(){
    return "<div id='tribu'>
              <h2>Tribu</h2>
              <div id='children_buttons'>
              </div>
              <form id='btn_show_empty_form' method='post' action=''>
                <input type='submit' name='show_empty_form' value='Ajouter un enfant'/>
              </form>
            </div>
            <form id='data_child_form' action='' method='post' style='display:none'>
              <div>
                <input type='text' name='last_name' value='".$child_data->last_name."' placeholder='Nom' />
                <input type='text' name='first_name' value='".$child_data->first_name."' placeholder='Prénom' />
                <input type='text' name='school' value='".$child_data->school."' placeholder='Ecole'/>
                <input type='text' name='classroom' value='".$child_data->classroom."' placeholder='Classe'>
              </div><br/>
              <div>
                <h3>Like</h3>
                <input type='checkbox' name='classique' ".checked($likes_data->classique, '1', false)." />Classique
                <input type='checkbox' name='dago' ".checked($likes_data->dago, '1', false)."  />Dago
                <input type='checkbox' name='fromage' ".checked($likes_data->fromage, '1', false)."  />Fromage
                <input type='checkbox' name='autre_fromage' ".checked($likes_data->autre_fromage, '1', false)."  />L'Autre fromage
                <input type='checkbox' name='italien' ".checked($likes_data->italien, '1', false)."  />Italien
                <input type='checkbox' name='halal' ".checked($likes_data->halal, '1', false)."  />Halal
              </div>
              <div>
                <h3>Dislike</h3>
                <input type='checkbox' name='beurre' />Beurre
                <input type='checkbox' name='salade' />Salade
                <input type='checkbox' name='legume_grille' />Légume grillé
                <input type='checkbox' name='legumaise' />Légumaise
                <input type='checkbox' name='pesto' />Pesto
              </div>
              <div>
                <h3>Fruit</h3>
                <input type='radio' name='fruit' value='oui' />Oui
                <input type='radio' name='fruit' value='non' />Non
              </div>
              <div>
                <h3>Appétit</h3>
                <input type='radio' name='portion' value='S' />Benjamin <i>(2 tartines ou 1/4 de baguette)</i>
                <input type='radio' name='portion' value='M' />Cadette <i>(4 tartines ou 1/3 de baguette)</i>
                <input type='radio' name='portion' value='L' />Ainé <i>(6 tartines ou 1/2 de baguette)</i>
              </div>
              <input type='submit' name='save_choices' value='Ok'/>
            </form>
            <div id='commande'>
              <h1>Ma commande </h1>
              <form method='post' action=''>
                <table style='display:none'>
                  <tr>
                    <th></th>
                    <th>Lun</th>
                    <th>Mar</th>
                    <th>Mer</th>
                    <th>Jeu</th>
                    <th>Ven</th>
                  </tr>
                  <div id='days_form'>
                  </div>
                </table>
                <div id='prix_total' class='wrap'>
                    <strong>Total : </strong> <span id='total'>0</span> €
                </div><br/>
                <div>
                  <input type='submit' name='commander' value='Commander' />
                </div>
              </form>
            </div>";
  }

  private function tartinette_html(){
    return "
      <form method='post' action=''>
        <input type='submit' name='go_tartinette' value='Tartinette' />
      </form>
        ";
  }

} // end class
