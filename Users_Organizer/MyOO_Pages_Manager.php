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

  public function add_panic_page(){
    $title = 'Panic !';
    $content = $this->panic_html();
    $this->add_page($title,$content);
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
              <h2>Tribu <span id='tribu_name'></span></h2>
              <div id='children_buttons'>
              </div>
              <div style='height:3vh;clear:both;'></div>
              <div style='clear:both'><input type='button' onclick='get_my_form(0)' value='Ajouter un enfant' /></div>
            </div>
            <form id='data_child_form' action='' method='post' style='display:none'>
              <div>
                <input id='last_name' type='text' name='last_name' value='' placeholder='Nom' />
                <input id='first_name' type='text' name='first_name' value='' placeholder='Prénom' />
                <input id='school' type='text' name='school' value='' placeholder='Ecole'/>
                <input id='classroom' type='text' name='classroom' value='' placeholder='Classe'>
              </div>
              <div style='height:3vh;clear:both;'></div>
              <table>
                <tr>
                  <th>
                    Aime :
                  </th>
                  <th>
                    N'aime pas :
                  </th>
                </tr>
                <tr>
                  <td>
                    <input type='checkbox' name='classique' />Classique
                    <input type='checkbox' name='dago' />Dago
                    <input type='checkbox' name='fromage'  />Fromage
                    <input type='checkbox' name='autre_fromage' />L'Autre fromage
                    <input type='checkbox' name='italien'  />Italien
                    <input type='checkbox' name='halal'  />Halal
                  </td>
                  <td>
                    <input type='checkbox' name='beurre' />Beurre
                    <input type='checkbox' name='salade' />Salade
                    <input type='checkbox' name='legume_grille' />Légumes grillés
                    <input type='checkbox' name='legumaise' />Légumaise
                    <input type='checkbox' name='pesto' />Pesto
                  </td>
                </tr>
                <tr>
                  <th>
                    Pain :
                  </th>
                  <th>
                    Appétit :
                  </th>
                </tr>
                <tr>
                  <td>
                    <input type='radio' name='pain' value='blanc' />Blanc
                    <input type='radio' name='pain' value='cereales' />5 céréales
                    <input type='radio' name='pain' value='baguette' />Baguette
                  </td>
                  <td>
                    <input type='radio' name='portion' value='S' />Benjamin <i style='font-size:0.8em'>(2 tartines ou 1/4 de baguette)</i>
                    <input type='radio' name='portion' value='M' />Cadette <i style='font-size:0.8em'>(4 tartines ou 1/3 de baguette)</i>
                    <input type='radio' name='portion' value='L' />Ainé <i style='font-size:0.8em'>(6 tartines ou 1/2 de baguette)</i>
                  </td>
                </tr>
                <tr>
                  <th>
                    Fruit :
                  </th>
                </tr>
                <tr>
                  <td>
                    <input type='radio' name='fruit' value='oui' />Oui
                    <input type='radio' name='fruit' value='non' />Non
                  </td>
                </tr>
              </table>
              <div><input type='submit' name='save_choices' value='Ok'/></div>
            </form>
            <div id='commande'>
              <h1>Ma commande </h1>
              <form id='commande_totale_form' method='post' action=''>
                <table id='table_days' style='display:none'>
                  <tr>
                    <th></th>
                    <th>Lun</th>
                    <th>Mar</th>
                    <th>Mer</th>
                    <th>Jeu</th>
                    <th>Ven</th>
                  </tr>
                </table>
                <div id='prix_total'>
                    <strong>Total : </strong> <span style='font-size:1.3em' id='total'></span> €
                </div><br/>
                <div>
                  <input type='submit' name='commander' value='Commander' />
                </div>
              </form>
            </div>";
  }

  private function tartinette_html(){
    return "
      <form method='post' action='' style='display:flex;justify-content:space-around;'>
        <div>
          <p id='p_abo' style='text-align:center;height:30%;'>
          </p>
          <input type='submit' name='go_abo' value='Abo' style='background-color:chartreuse;width:150px;height:150px;border-radius:50%;font-size:1.4em;display:block;margin:auto;' />
        </div>
        <div>
          <p id='p_panic' style='text-align:center;height:30%;'>
          </p>
          <input type='submit' name='go_panic' value='Panic' style='background-color:red;width:150px;height:150px;border-radius:50%;font-size:1.4em;display:block;margin:auto; ' />
        </div>
      </form>
        ";
  }

  private function panic_html(){
    return "<div id='tribu'>
              <h1 style='color:red'>Tribu <span id='tribu_name'></span> en panic !</h1>
              <div id='children_buttons'>
              </div>
              <div style='height:3vh;clear:both;'></div>
              <div style='clear:both'><input type='button' onclick='get_child_form(0)' value='Ajouter un enfant' /></div>
            </div>
            <div id='panic_form' style='display:none'>
              <form action='' method='post' >
                <div>
                  <input id='last_name' type='text' name='last_name' value='' placeholder='Nom' />
                  <input id='first_name' type='text' name='first_name' value='' placeholder='Prénom' />
                  <input id='school' type='text' name='school' value='' placeholder='Ecole'/>
                  <input id='classroom' type='text' name='classroom' value='' placeholder='Classe'>
                </div>
                <div style='height:3vh;clear:both;'></div>
                <div><input type='submit' name='submit_child' value='Ok' /></div>
                <div style='height:3vh;clear:both;'></div>
              </form>
            </div>
            <div id='commande' style='display:none'>
              <h2 style='color:red'>Ma commande </h2>
              <form id='commande_totale_form' method='post' action=''>
                <table id='my_table'>
                  <tr>
                    <th>
                    </th>
                    <th>
                      Aime :
                    </th>
                    <th>
                      Appétit :
                    </th>
                    <th>
                      Fruit :
                    </th>
                  </tr>
                </table>
                <div id='prix_total'>
                    <strong>Total : </strong> <span style='font-size:1.3em' id='total'></span> €
                </div><br/>
                <div>
                  <input type='submit' name='commander' value='Commander' />
                </div>
              </form>
            </div>";
  }

} // end class
