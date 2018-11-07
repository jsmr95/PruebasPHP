<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Insertar Artículo</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
  <?php
      require './auxiliar.php';

        const PAR = [
            'articulo' => '',
            'marca' => '',
            'precio' => '',
            'descripcion' => '',
            'genero_id' => '',
        ];
         extract(PAR);
         if (isset($_POST['articulo'], $_POST['marca'], $_POST['precio'],
                  $_POST['descripcion'], $_POST['genero_id'])) {
            $error = [];
            $pdo = conectar();
            extract(array_map('trim', $_POST), EXTR_IF_EXISTS);

            $fltArticulo = compruebaArticulo($error);
            $fltMarca = compruebaMarca($error);
            $fltDescripcion = trim(filter_input(INPUT_POST,'descripcion'));
            $fltGeneroId = compruebaGeneroId($pdo,$error);

            if (empty($error)) {
              $st = $pdo->prepare('INSERT INTO productos (articulo, marca, precio, descripcion, genero_id)
                                   VALUES (:articulo, :marca, :precio, :descripcion, :genero_id)');
              $st->execute([
                  ':articulo' => $fltArticulo,
                  ':marca' => $fltMarca,
                  ':precio' => $precio,
                  ':descripcion' => $fltDescripcion,
                  ':genero_id' => $fltGeneroId,
              ]);
              header('Location: index.php');
            }else {
              foreach ($error as $err) {
                echo "<h4>Error: $err</h4>";
                $st = $pdo->prepare('SELECT *
                                    FROM generos');
                $st->execute([]);
              }
            }
         } else {
           $st = $pdo->prepare('SELECT *
                               FROM generos');
           $st->execute([]);
         }

        ?>
        <br>
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Insertar un nuevo artículo...</h3>
                </div>
                <div class="panel-body">
                    <form action="" method="post">
                        <div class="form-group">
                            <label for="articulo">Artículo</label>
                            <input type="text" name="articulo" class="form-control" id="articulo" value="<?= $articulo ?>">
                        </div>
                        <div class="form-group">
                            <label for="marca">Marca</label>
                            <input type="text" name="marca" class="form-control" id="marca" value="<?= $marca ?>">
                        </div>
                        <div class="form-group">
                            <label for="precio">Precio</label>
                            <input type="text" name="precio" class="form-control" id="precio" value="<?= $precio ?>">
                        </div>
                        <div class="form-group">
                          <label for="descripcion">Descripción</label>
                          <textarea name="descripcion" rows="8" cols="80" class="form-control" id="descripcion"><?= $descripcion ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="genero_id">Género</label>
                            <select class="form-control" name="genero_id" id="genero_id">
                            <!-- Recorremos la sentencia para ir mostrando cada genero en las opciones -->
                            <?php while ($fila = $st->fetch()): ?>
                            <option value="<?= $fila['id'] ?>"> <?= $fila['genero'] ?> </option>
                          <?php endwhile ?>
                        </select>
                        </div>
                        <input type="submit" value="Insertar" class="btn btn-success">
                        <a href="index.php" class="btn btn-info">Volver</a>
                    </form>
                </div>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </body>
</html>
