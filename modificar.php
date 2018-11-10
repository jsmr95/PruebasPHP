<!DOCTYPE html>
<html lang="es" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Modificar</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    </head>
    <body>
    <?php
    require 'auxiliar.php';

    try{
    $error = [];
    $pdo = conectar();
    $id = comprobarId();
    $fila = buscarArticulo($pdo, $id);
    comprobarParametros(PAR);
    $valores = array_map('trim', $_POST);
    $flt['articulo'] = compruebaArticulo($error);
    $flt['marca'] = compruebaMarca($error);
    $flt['precio'] = compruebaPrecio($error);
    $flt['descripcion'] = trim(filter_input(INPUT_POST,'descripcion'));
    $flt['genero_id'] = compruebaGeneroId($pdo,$error);
    comprobarErrores($error);
    modificarProducto($pdo, $flt, $id);
    header('Location: index.php');
    } catch (EmptyParamException|ValidationException $e){
         //No hago nada
     } catch (ParamException $e){
       $_SESSION['error'] = 'El producto no ha sido modificado.';
         header('Location: index.php');
     }
     $st = $pdo->query('SELECT * FROM generos');
    ?>
    <div class="container">
      <div class="row">
          <br>
        <div class="panel panel-primary">
          <div class="panel-heading">
              <h3 class="panel-title">Modificar un artículo...</h3>
          </div>
          <div class="panel-body">
            <form action="" method="post">
            <div class="form-group <?= hasError('articulo',$error) ?>" >
                <label for="articulo" class="control-label">Artículo</label>
                <input type="text" name="articulo" class="form-control" id="articulo" value="<?= $fila['articulo'] ?>">
                <?php mensajeError('articulo', $error) ?>
            </div>
            <div class="form-group <?= hasError('marca',$error) ?>">
                <label for="marca" class="control-label">Marca</label>
                <input type="text" name="marca" class="form-control" id="marca" value="<?= $fila['marca'] ?>">
                <?php mensajeError('marca', $error) ?>
            </div>
            <div class="form-group <?= hasError('precio',$error) ?>">
                <label for="precio" class="control-label">Precio</label>
                <input type="text" name="precio" class="form-control" id="precio" value="<?= $fila['precio'] ?>">
                <?php mensajeError('precio', $error) ?>
            </div>
            <div class="form-group">
              <label for="descripcion" class="control-label">Descripción</label>
              <textarea name="descripcion" rows="8" cols="80" class="form-control" id="descripcion"><?= $fila['descripcion'] ?></textarea>
            </div>
            <div class="form-group <?= hasError('genero_id',$error) ?>">
                <label for="genero_id" class="control-label">Género</label>
                <select class="form-control" name="genero_id" id="genero_id">
                  <?php mensajeError('genero_id', $error) ?>

                <!-- Recorremos la sentencia para ir mostrando cada genero en las opciones -->
                <?php while ($genero = $st->fetch()): ?>
                <option value="<?= $genero['id'] ?>" <?= generoSeleccionado($genero['id'],$fila['genero_id'])?>> <?= $genero['genero'] ?> </option>
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
