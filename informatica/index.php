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
          //Comprueba si esta buscando algun articulo
          $buscarArticulo = isset($_GET['buscarArticulo'])
                          ? trim($_GET['buscarArticulo'])
                          : '';
          $buscarMarca = isset($_GET['buscarMarca'])
                          ? trim($_GET['buscarMarca'])
                          : '';
          $st = compruebaBuscadoresSinGenero($pdo,$buscarArticulo,$buscarMarca,'Informatica');

          ?>
        </div>
        <div class="row form-inline" id="busqueda">
            <fieldset>
              <legend>Buscar en 'Informatica'</legend>
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
      <hr>
      <div class="row">
        <div class="col-md-12">
          <table class="table table-bordered table-hover">
              <thead class="fondo">
                  <th>Articulo</th>
                  <th>Marca</th>
                  <th>Precio</th>
                  <th>Descripción</th>
              </thead>
              <tbody>
                  <?php while ($fila = $st->fetch()): ?> <!-- Podemos asignarselo a fila, ya que en la asignación,
                                                          tb devuelve la fila, si la hay, por lo que entra,cuando no hay mas filas, da false y se sale.-->
                  <tr class="fondoTabla">
                      <td><?= filter_var($fila['articulo'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?></td>
                      <td><?= h($fila['marca']) ?></td>
                      <td><?= h($fila['precio']) ?>€</td>
                      <td><?= h($fila['descripcion']) ?></td>
                  </tr>
                  <?php endwhile ?>
              </tbody>
          </table>
        </div>
      </div>
    </div><br>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </body>
</html>
