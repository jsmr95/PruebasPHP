<!DOCTYPE html>
<html lang="es" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmar borrado</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  </head>
  <body>
    <?php
    require 'auxiliar.php';

    $pdo = conectar();
    if (isset($_GET['id'])) {
      $id = $_GET['id'];
    } else {
      header('Location: index.php');
    }
    if (!buscarArticulo($pdo,$id)){
      header('Location: index.php');
    }
     ?>
     <div class="container">
       <div class="row">
           <h3>¿Seguro que quieres borrar el articulo?</h3>
           <div class="col-mg-4">
             <form action="index.php" method="post" class="form-inline">
               <div class="form-group">
                 <input type="hidden" name="id" value="<?= $id ?>">
                 <input type="submit" value="Si" class="btn btn-danger">
               </div>
               <a href="index.php" class="btn btn-success">No</a>
             </form>
         </div>
       </div>
     </div>
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
 </body>
</html>
