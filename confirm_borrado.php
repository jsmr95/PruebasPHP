<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <?php
    if (isset($_GET['id'])) {
      $id = $_GET['id'];
    } 
     ?>
    <h3>¿Seguro que quieres borrar el articulo?</h3>
    <form action="index.php" method="post">
      <input type="hidden" name="id" value="<?= $id ?>">
      <input type="submit" value="Si">
      <a href="index.php">No</a>
    </form>
  </body>
</html>
