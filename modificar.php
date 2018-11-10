<?php session_start(); ?>
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
     mostrarFormulario($pdo, $fila, $error, 'Modificar');
    ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    </body>
</html>
