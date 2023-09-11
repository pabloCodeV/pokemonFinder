<?php

//** Allows reading environment variables from the .env file
class Security{

  private $dotenv;

    public function __construct() {
      define("PATH", dirname(__FILE__,2));
        require_once __DIR__ . '/../vendor/autoload.php'; // Carga Composer autoloader
        $this->dotenv = Dotenv\Dotenv::createImmutable(PATH);
        $this->dotenv->load();
    }
}
    
//** Class that collects all the pokemon found, inherits from security to keep the environment variables present in all the remaining code
class PokemonFinderAll extends Security{

  private $urlPokemon = []; //recopila todos los nombres de los pokemons

    public function apiRun() {
      $url = $_ENV['allPokemon'];

      $curl = curl_init($url);
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

      //ONLY DEBUG 
      curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

      $resp = curl_exec($curl);
      curl_close($curl);
      $resp = json_decode($resp);
        foreach($resp->results as $value){
          $this->urlPokemon[] = $value->name;
        }
        return $this->urlPokemon;
    }

}

// Class that collects one or more the pokemon found, inherits from security to keep the environment variables present in all the remaining code
  class PokemonFinderOnly{

      public function apiRunOnly($url) {
        $url = $_ENV['onlyPokemon'].$url.'/';

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        //ONLY DEBUG 
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        $resp = json_decode($resp);

        return $resp;
      }

  }

//** Class that collects all the pokemon found, inherits from security to keep the environment variables present in all the remaining code
class PokemonFinder{

  private $allPokemon;
  private $onlyPokemon;
  private $data;

    public function __construct() {
      $this->allPokemon = new PokemonFinderAll();
      $this->onlyPokemon = new PokemonFinderOnly();
    }

    //*** */ RETRIEVE THE REQUESTED POKEMON BY ID OR NAME
    public function apiExecute($val){
      if(!empty($val)){

          if(is_numeric($val)){
            $this->notFound($val);
            $this->data = $this->onlyPokemon->apiRunOnly($val);
            $this->viewCard( $this->data->name);
            $this->view($this->data->name);
              
          }else{ 

              $pokeDiscover = $this->allPokemon->apiRun();
              $count = 0;

              foreach ($pokeDiscover as $pokeNames) {
                if (strpos($pokeNames, $val) !== false && ($val !="" || $val !=NULL)) {
                  $this->data = $this->onlyPokemon->apiRunOnly($pokeNames);
                  $this->viewCard($pokeNames);
                  $this->view($pokeNames); 
                  $count++;    
                }
              }

              $this->notFound($count);
  
          }

      }else{
        $this->notFound($val);
      }

    }
  
  
      //***** */ BOOTSTRAP MODAL VIEW
      public function view($pokeNames){
        echo '<div class="modal fade" id="'.str_replace("-","",$pokeNames).'" tabindex="-1" role="dialog" aria-labelledby="'.str_replace("-","",$pokeNames).'" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    <div class="only-pokedesk">
                      <img src="'.$this->data->sprites->other->{'official-artwork'}->front_default.'">
                    </div>
                    <div class="poke-stats">';
                    $colores = ['#26e11d','#ff4343','#5586ee','#ff74e6','#0d4bcf','#cfbe11'];
                    $count = 0;

                    foreach($this->data->stats as $value){;

                      echo '<p style="background:'.$colores[$count].';text-transform:capitalize;"><span class="es-lg">'.$value->base_stat.'</span>  <i class="fas fa-arrow-right"></i><span class="es-lg"> '.$value->stat->name.'</span></p>';
                      $count++;
                    };

        echo       '</div>
                  </div>
                </div>
              </div>
            </div>';
      }


      //**** POKEMON CARD
      public function viewCard($pokeNames){
        if(!empty($pokeNames)){
          echo  '<div class="pokedesk">
                  <img src="'.$this->data->sprites->other->{'official-artwork'}->front_default.'">
                  <button type="button" class="btn-view btn btn-primary" data-toggle="modal" data-target="#'.str_replace("-","",$pokeNames).'" >'.$pokeNames.'</button>
                </div>';
        }
      }


      //*** POKEMON NOT FOUND
      public function notFound($exist){
        switch($exist){
          case false || "0" || "":
              echo '<div style="border-radius:15px;text-align-last: center;"> 
                      <img style="width:45%" src="assets/image/not-found.png">
                      <h2>Pokémon No Encontrado</h2>
                    </div>';
                  die();
              break;
          case $exist > 1010:
              echo '<div> 
                      <img style="border-radius:15px;" src="assets/image/error.png">
                    </div>';
                      die();
              break;
        }
      }

}

?>