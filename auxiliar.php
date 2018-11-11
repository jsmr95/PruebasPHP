<?php

class ValidationException extends Exception
{}

class ParamException extends Exception
{}

class EmptyParamException extends Exception
{}

  const PAR = [
      'articulo' => '',
      'marca' => '',
      'precio' => '',
      'descripcion' => '',
      'genero_id' => '',
  ];

  function comprobarErrores(&$error){
    if (!empty($error)) {
      throw new ValidationException();
    }
  }

  function comprobarParametros($par){
    if (empty($_POST)) {
      throw new EmptyParamException();
    }
    if (!empty(array_diff_key($par,$_POST)) ||
        !empty(array_diff_key($_POST, $par))) {
          throw new ParamException();
    }
  }

  function conectar(){
    return new PDO('pgsql:host=localhost;dbname=prueba','prueba','prueba');
  }

  function buscarArticulo($pdo, $id){
    $st = $pdo->prepare('SELECT *
                          FROM productos
                          WHERE id = :id');
    $st->execute(['id' => $id]);
    return $st->fetch();
  }

  function compruebaBuscadores($pdo,$buscarArticulo,$buscarMarca,$buscarGenero){
    if ($buscarGenero != '') {
    $st = $pdo->prepare('SELECT p.*, genero
                        FROM productos p
                        JOIN generos g
                        ON genero_id = g.id
                        WHERE position(lower(:articulo) in lower(articulo)) != 0
                        AND position(lower(:marca) in lower(marca)) != 0
                        AND position(lower(:genero) in lower(genero)) != 0');
    $st->execute([':articulo' => "$buscarArticulo", ':marca' => "$buscarMarca", ":genero" => "$buscarGenero"]);
    } elseif($buscarMarca != '') {
      $st = $pdo->prepare('SELECT p.*, genero
                          FROM productos p
                          JOIN generos g
                          ON genero_id = g.id
                          WHERE position(lower(:articulo) in lower(articulo)) != 0
                          AND position(lower(:marca) in lower(marca)) != 0');
      $st->execute([':articulo' => "$buscarArticulo", ':marca' => "$buscarMarca"]);
    } else {
      $st = $pdo->prepare('SELECT p.*, genero
                          FROM productos p
                          JOIN generos g
                          ON genero_id = g.id
                          WHERE position(lower(:articulo) in lower(articulo)) != 0'); //position es como mb_substrpos() de php, devuelve 0
                                                                                  //si no encuentra nada. ponemos lower() de postgre para
                                                                                  //que no distinga entre mayu y minus
      $st->execute([':articulo' => "$buscarArticulo"]);
    }
    return $st;
  }

  function compruebaBuscadoresSinGenero($pdo,$buscarArticulo,$buscarMarca, $genero){
    if($buscarMarca != '') {
      $st = $pdo->prepare("SELECT p.*, genero
                          FROM productos p
                          JOIN generos g
                          ON genero_id = g.id
                          WHERE position(lower(:articulo) in lower(articulo)) != 0
                          AND position(lower(:marca) in lower(marca)) != 0
                          AND g.genero = '$genero' ");
      $st->execute([':articulo' => "$buscarArticulo", ':marca' => "$buscarMarca"]);
    } else {
      $st = $pdo->prepare("SELECT p.*, genero
                          FROM productos p
                          JOIN generos g
                          ON genero_id = g.id
                          WHERE position(lower(:articulo) in lower(articulo)) != 0
                          AND g.genero = 'Informatica' ");                         //position es como mb_substrpos() de php, devuelve 0
                                                                                  //si no encuentra nada. ponemos lower() de postgre para
                                                                                  //que no distinga entre mayu y minus
      $st->execute([':articulo' => "$buscarArticulo"]);
    }
    return $st;
  }

  function compruebaArticulo(&$error){
    $fltArticulo = trim(filter_input(INPUT_POST,'articulo'));
    if ($fltArticulo === '') {
      $error['articulo'] = 'El titulo es obligatorio.';
    }elseif (mb_strlen($fltArticulo) > 255) {
      $error['articulo'] = 'El nombre del articulo es demasiado largo.';
    }
    return $fltArticulo;
  }

  function compruebaMarca(&$error){
    $fltMarca = trim(filter_input(INPUT_POST,'marca'));
    if ($fltMarca === '') {
      $error['marca'] = 'La marca es obligatoria.';
    } elseif (mb_strlen($fltMarca) > 255) {
      $error['marca'] = 'El nombre de la marca es demasiado largo.';
    }
    return $fltMarca;
  }

  function compruebaGeneroId($pdo, &$error){
    $fltGeneroId = filter_input(INPUT_POST,'genero_id',FILTER_VALIDATE_INT);
    if ($fltGeneroId !== false) {
      $st = $pdo->prepare('SELECT * from generos WHERE id = :id');
      $st->execute([':id' => $fltGeneroId]);
      if ($st->fetch() === false) {
        $error['genero_id'] = 'No existe ese género.';
      }
    } else {
      $error['genero_id'] = 'El género no es correcto.';
    }
    return $fltGeneroId;
  }

  function compruebaPrecio(&$error){
    $fltPrecio = filter_input(INPUT_POST,'precio',FILTER_VALIDATE_FLOAT, ['options' => ['precision' => 2]]);
    if ($fltPrecio === false) {
      $error['precio'] = 'El precio no puede estar vacio. ';
    }elseif ($fltPrecio < 0 || $fltPrecio > 99999) {
      $error['precio'] = 'El precio debe estar entre 0,00 y 99.999';
    }
    return $fltPrecio;
  }

  function comprobarId(){
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($id === null || $id === false) {
      throw new ParamException();
    }
    return $id;
  }

  function insertarProducto($pdo,$flt){
    $st = $pdo->prepare('INSERT INTO productos (articulo, marca, precio, descripcion, genero_id)
                         VALUES (:articulo, :marca, :precio, :descripcion, :genero_id)');
    $st->execute($flt);
  }

  function modificarProducto($pdo,$flt, $id){
    $st = $pdo->prepare('UPDATE productos
                            SET articulo = :articulo
                                , marca = :marca
                                , precio = :precio
                                , descripcion = :descripcion
                                , genero_id = :genero_id
                            WHERE id = :id');
    $st->execute($flt + [':id' => $id]);
  }

  function hasError($key, $error){
    return array_key_exists($key, $error) ? 'has-error' : '';
  }

  function mensajeError($key, $error){
    if (isset($error[$key])) { ?>
      <small class="help-block"> <?= $error[$key] ?></small> <?php
    }
  }

  function generoSeleccionado($genero, $genero_id){

  return $genero == $genero_id ? "selected" : "";
}

function mostrarFormulario($pdo,$fila,$error,$accion){
  $st = $pdo->query('SELECT * FROM generos');
  ?>
  <div class="container">
    <div class="row">
        <br>
      <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><?= $accion?> un artículo...</h3>
        </div>
        <div class="panel-body">
          <form action="" method="post">
          <div class="form-group <?= hasError('articulo',$error) ?>" >
              <label for="articulo" class="control-label">Artículo</label>
              <input type="text" name="articulo" class="form-control" id="articulo" value="<?= h($fila['articulo']) ?>">
              <?php mensajeError('articulo', $error) ?>
          </div>
          <div class="form-group <?= hasError('marca',$error) ?>">
              <label for="marca" class="control-label">Marca</label>
              <input type="text" name="marca" class="form-control" id="marca" value="<?= h($fila['marca']) ?>">
              <?php mensajeError('marca', $error) ?>
          </div>
          <div class="form-group <?= hasError('precio',$error) ?>">
              <label for="precio" class="control-label">Precio</label>
              <input type="text" name="precio" class="form-control" id="precio" value="<?= h($fila['precio']) ?>">
              <?php mensajeError('precio', $error) ?>
          </div>
          <div class="form-group">
            <label for="descripcion" class="control-label">Descripción</label>
            <textarea name="descripcion" rows="8" cols="80" class="form-control" id="descripcion"><?= h($fila['descripcion']) ?></textarea>
          </div>
          <div class="form-group <?= hasError('genero_id',$error) ?>">
              <label for="genero_id" class="control-label">Género</label>
              <select class="form-control" name="genero_id" id="genero_id">
                <?php mensajeError('genero_id', $error) ?>

              <!-- Recorremos la sentencia para ir mostrando cada genero en las opciones -->
              <?php while ($genero = $st->fetch()): ?>
              <option value="<?= $genero['id'] ?>" <?= generoSeleccionado($genero['id'],$fila['genero_id'])?>> <?= h($genero['genero']) ?> </option>
            <?php endwhile ?>
          </select>
          </div>
          <input type="submit" value="<?= $accion ?>" class="btn btn-success">
          <a href="index.php" class="btn btn-info">Volver</a>
        </form>
      </div>
    </div>
  </div>
  <?php
}

function h($cadena){
    return htmlspecialchars($cadena, ENT_QUOTES);
}

function comprobarSession($var, $tipo){
      if (isset($_SESSION["$var"])): ?>
        <div class="row">
            <div class="alert alert-<?=$tipo?>" role="alert">
                <?= $_SESSION["$var"] ?>
            </div>
        </div>
        <?php unset($_SESSION["$var"]); ?>
    <?php endif;
}

function navegadorInicio(){ ?>
<nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand " href="index.php">Bienvenido</a>
                    <a class="navbar-brand " href="./todos/index.php">Todos los articulos</a>
                    <a class="navbar-brand " href="./informatica/index.php">Informática</a>
                </div>
                <div class="navbar-text navbar-right">
                    <a href="" class="btn btn-success" style="margin-right: 100px;">Login</a>
                </div>
            </div>
        </nav>
<?php
}

function navegador(){ ?>
<nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand " href="../index.php">Bienvenido</a>
                    <a class="navbar-brand " href="../todos/index.php">Todos los articulos</a>
                    <a class="navbar-brand " href="../informatica/index.php">Informática</a>

                </div>
                <div class="navbar-text navbar-right">
                    <a href="" class="btn btn-success" style="margin-right: 100px;">Login</a>
                </div>
            </div>
        </nav>
<?php
}
