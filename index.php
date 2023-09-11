<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/css/layout.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <title>Nuestro destino asi es!</title>
</head>
<body>
  <main>
    <section class="finder">
      <h1 class="title">Buscador de pokémons</h1>
      <h3 class="sub-title">Atrapalos ya!</h3>
        <form class="mb-3 form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
          <div class="tm-lg">
            <input type="text" class="form-control" name="pokemon" placeholder="Buscar por ID o Nombre">
          </div>
          <input type="submit" class="btn btn-secondary tm-sm">
        </form>
    </section>

    <section id="container">
      <?php
        require 'controller/PokemonController.php';
          $instancia = new PokemonFinder();
          $instancia->apiExecute(!empty($_POST['pokemon']) ? $_POST['pokemon'] = $_POST['pokemon'] : $_POST['pokemon'] ="Primera Busqueda");
      ?>
    </section>

  </main>


  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>