<?php session_start();
require '../auxiliar.php';
navegador();?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Productos Informáticos</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <style media="screen">
            #busqueda { margin-top: 1em; }
            .fondo { background: #6ACA2B;}
            .fondoTabla {background: #B0DF91;}
            #fondoTabla {background: #B0DF91;}
        </style>
    </head>
    <body>
      <div class="container">
        <div class="row">
          <?php

          $pdo = conectar();
          if (isset($_POST['id'])) {
            $id = $_POST['id'];
            if (buscarArticulo($pdo, $id)) {
            $st = $pdo->prepare('DELETE FROM productos WHERE id = :id');
            $st->execute([':id' => "$id"]);
            if (buscarArticulo($pdo, $id) === false) {
              $_SESSION['mensaje'] = 'El producto ha sido borrado correctamente.';
              header('Location: index.php');
            }
            } else {
              $_SESSION['error'] = 'El producto no existe.';
              header('Location: index.php');
            }
          }
          //Comprueba si esta buscando algun articulo
          $buscarArticulo = isset($_GET['buscarArticulo'])
                          ? trim($_GET['buscarArticulo'])
                          : '';
          $buscarMarca = isset($_GET['buscarMarca'])
                          ? trim($_GET['buscarMarca'])
                          : '';
          $buscarGenero = isset($_GET['buscarGenero'])
                          ? trim($_GET['buscarGenero'])
                          : '';
          $st = compruebaBuscadores($pdo,$buscarArticulo,$buscarMarca,$buscarGenero);

          ?>
        </div>
        <div class="row form-inline" id="busqueda">
            <fieldset>
              <legend>Buscar</legend>
              <!-- Creamos un buscador de articulos por nombre-->
              <form action="" method="get" class="form-inline">
                <div class="col-md-6">
                  <div class="panel panel-default" id="fondoTabla">
                    <div class="panel-body">
                      <div class="form-group">
                        <label for="buscarArticulo">Buscar por nombre:</label>
                        <input id="buscarArticulo" class="form-control estilo1" type="text" name="buscarArticulo"
                        value="<?= $buscarArticulo ?>" class="form-control">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
              <!-- Creamos un buscador de articulos por marca-->
              <div class="panel panel-default" id="fondoTabla">
                <div class="panel-body">
                  <div class="form-group">
                    <label for="buscarMarca">Buscar por marca:</label>
                    <input id="buscarMarca" type="text" name="buscarMarca"
                    value="<?= $buscarMarca ?>" class="form-control">
                  </div>
                </div>
              </div>
            </div>
                <input type="submit" value="Buscar" class="btn btn-primary">
              </form>
            </fieldset>
          </div>
      <hr> <?php
      comprobarSession('mensaje', 'success');
      comprobarSession('mensaje', 'error');
      ?>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-hover">
              <thead class="fondo">
                  <th>Articulo</th>
                  <th>Marca</th>
                  <th>Precio</th>
                  <th>Descripción</th>
                  <th>Género</th>
                  <th>Acciones</th>
              </thead>
              <tbody>
                  <?php while ($fila = $st->fetch()): ?> <!-- Podemos asignarselo a fila, ya que en la asignación,
                                                          tb devuelve la fila, si la hay, por lo que entra,cuando no hay mas filas, da false y se sale.-->
                  <tr class="fondoTabla">
                      <td><?= filter_var($fila['articulo'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?></td>
                      <td><?= h($fila['marca']) ?></td>
                      <td><?= h($fila['precio']) ?>€</td>
                      <td><?= h($fila['descripcion']) ?></td>
                      <td><?= h($fila['genero']) ?></td>
                      <td><a href="confirm_borrado.php?id=<?= $fila['id'] ?>" class="btn btn-danger btn-xs">
                            Borrar
                          </a>
                          <a href="modificar.php?id=<?= $fila['id'] ?>" class="btn btn-info btn-xs">
                                Modificar
                              </a>
                      </td>
                  </tr>
                  <?php endwhile ?>
              </tbody>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="text-center">
          <a href="insertar.php" class="btn btn-primary">Insertar articulo nuevo</a>
        </div>
      </div>
    </div><br>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </body>
</html>
