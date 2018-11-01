<!DOCTYPE html>
<html lang="es" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bases de datos</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    </head>
    <body>
        <?php
        require 'auxiliar.php';

        $pdo = conectar();
        if (isset($_POST['id'])) {
          $id = $_POST['id'];
          if (buscarArticulo($pdo, $id)) {
          $st = $pdo->prepare('DELETE FROM productos WHERE id = :id');
          $st->execute([':id' => "$id"]);
          ?>
                <h3>Articulo borrado correctamente.</h3>
        <?php
          } else {
            ?>
            <h3>Error: El articulo no existe!</h3>
            <?php
          }
        }


        $buscarArticulo = isset($_GET['buscarArticulo'])
                        ? trim($_GET['buscarArticulo'])
                        : '';
        $st = $pdo->prepare('SELECT p.*, genero
                            FROM productos p
                            JOIN generos g
                            ON genero_id = g.id
                            WHERE position(lower(:articulo) in lower(articulo)) != 0'); //position es como mb_substrpos() de php, devuelve 0
                                                                                    //si no encuentra nada. ponemos lower() de postgre para
                                                                                    //que no distinga entre mayu y minus
        //En execute(:titulo => "$valor"), indicamos lo que vale nuestros marcadores de prepare(:titulo)
        $st->execute([':articulo' => "$buscarArticulo"]);
        ?>
        <div>
          <!-- Creamos un buscador de peliculas -->
            <fieldset>
                <legend>Buscar</legend>
                <form action="" method="get">
                    <label for="buscarArticulo">Buscar por nombre:</label>
                    <input id="buscarArticulo" type="text" name="buscarArticulo"
                    value="<?= $buscarArticulo ?>">
                    <input type="submit" value="Buscar">
                </form>
            </fieldset>
        </div>

    <div style="margin-top: 20px">
        <table border="1" style="margin:auto"><!--El style lo centra-->
            <thead>
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
                <tr>
                    <td><?= $fila['articulo'] ?></td>
                    <td><?= $fila['marca'] ?></td>
                    <td><?= $fila['precio'] ?>€</td>
                    <td><?= $fila['descripcion'] ?></td>
                    <td><?= $fila['genero'] ?></td>
                    <td><a href="confirm_borrado.php?id=<?= $fila['id'] ?>">Borrar</a></td>
                </tr>
                <?php endwhile ?>
            </tbody>
        </table>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </body>
</html>
