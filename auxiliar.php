<?php

class ValidationException extends Exception
{}

class ParamException extends Exception
{}

class EmptyParamException extends Exception
{}

  function comprobarErrores(&$error){
    if (!empty($error)) {
      throw new ValidationException();
    }
  }

  function comprobarParametros($par){
    if (emtpy($_POST)) {
      throw new EmptyParamException();
    }
    if (!empty(array_diff_key($par,$_POST)) ||
        !empty(array_diff_key($_POST, $par))) {
          throw new ParamException();
    }
  }

  function conectar(){
    return new PDO('pgsql:host=localhost;dbname=prueba','prueba','prueba');
  }

  function buscarArticulo($pdo, $id){
    $st = $pdo->prepare('SELECT id
                          FROM productos
                          WHERE id = :id');
    $st->execute(['id' => $id]);
    return $st->fetch();
  }

  function compruebaBuscadores($pdo,$buscarArticulo,$buscarMarca,$buscarGenero){
    if ($buscarGenero != '') {
    $st = $pdo->prepare('SELECT p.*, genero
                        FROM productos p
                        JOIN generos g
                        ON genero_id = g.id
                        WHERE position(lower(:articulo) in lower(articulo)) != 0
                        AND position(lower(:marca) in lower(marca)) != 0
                        AND position(lower(:genero) in lower(genero)) != 0');
    $st->execute([':articulo' => "$buscarArticulo", ':marca' => "$buscarMarca", ":genero" => "$buscarGenero"]);
    } elseif($buscarMarca != '') {
      $st = $pdo->prepare('SELECT p.*, genero
                          FROM productos p
                          JOIN generos g
                          ON genero_id = g.id
                          WHERE position(lower(:articulo) in lower(articulo)) != 0
                          AND position(lower(:marca) in lower(marca)) != 0');
      $st->execute([':articulo' => "$buscarArticulo", ':marca' => "$buscarMarca"]);
    } else {
      $st = $pdo->prepare('SELECT p.*, genero
                          FROM productos p
                          JOIN generos g
                          ON genero_id = g.id
                          WHERE position(lower(:articulo) in lower(articulo)) != 0'); //position es como mb_substrpos() de php, devuelve 0
                                                                                  //si no encuentra nada. ponemos lower() de postgre para
                                                                                  //que no distinga entre mayu y minus
      $st->execute([':articulo' => "$buscarArticulo"]);
    }
    return $st;
  }

  function compruebaArticulo(&$error){
    $fltArticulo = trim(filter_input(INPUT_POST,'articulo'));
    if ($fltArticulo === '') {
      $error[] = 'El titulo es obligatorio.';
    }elseif (mb_strlen($fltArticulo) > 255) {
      $error[] = 'El nombre del articulo es demasiado largo.';
    }
    return $fltArticulo;
  }

  function compruebaMarca(&$error){
    $fltMarca = trim(filter_input(INPUT_POST,'marca'));
    if (mb_strlen($fltMarca) > 255) {
      $error[] = 'El nombre de la marca es demasiado largo.';
    }
    return $fltMarca;
  }

  function compruebaGeneroId($pdo, &$error){
    $fltGeneroId = filter_input(INPUT_POST,'genero_id',FILTER_VALIDATE_INT);
    if ($fltGeneroId !== false) {
      $st = $pdo->prepare('SELECT * from generos WHERE id = :id');
      $st->execute([':id' => $fltGeneroId]);
      if ($st->fetch() === false) {
        $error[] = 'No existe ese género.';
      }
    } else {
      $error[] = 'El género no es correcto.';
    }
    return $fltGeneroId;
  }

  function compruebaPrecio(&$error){
    $fltPrecio = filter_input(INPUT_POST,'precio',FILTER_VALIDATE_FLOAT, ['options' => ['precision' => 2]]);
    if ($fltPrecio === false) {
      $error[] = 'El precio debe llevar dos dígitos decimales.';
    }elseif ($fltPrecio < 0 || $fltPrecio > 99999) {
      $error[] = 'El precio debe estar entre 0,00 y 99.999';
    }
    return $fltPrecio;
  }
