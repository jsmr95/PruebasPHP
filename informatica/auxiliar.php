<?php

function compruebaBuscadoresInformatica($pdo,$buscarArticulo,$buscarMarca){
  if($buscarMarca != '') {
    $st = $pdo->prepare("SELECT p.*, genero
                        FROM productos p
                        JOIN generos g
                        ON genero_id = g.id
                        WHERE position(lower(:articulo) in lower(articulo)) != 0
                        AND position(lower(:marca) in lower(marca)) != 0
                        AND g.genero = 'Informatica' ");
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

function mostrarFormularioInformatica($pdo,$fila,$error,$accion){
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
