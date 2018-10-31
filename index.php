<!DOCTYPE html>
<html lang="es" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title>Bases de datos</title>
    </head>
    <body>
        <?php
        $buscarArticulo = isset($_GET['buscarArticulo'])
                        ? trim($_GET['buscarArticulo'])
                        : '';
        $pdo = new PDO('pgsql:host=localhost;dbname=prueba','prueba','prueba');
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
                    <td><?= $fila['precio'] ?></td>
                    <td><?= $fila['descripcion'] ?></td>
                    <td><?= $fila['genero'] ?></td>
                    <td><a>Borrar</a></td>
                </tr>
                <?php endwhile ?>
            </tbody>
        </table>
    </div>
    </body>
</html>
