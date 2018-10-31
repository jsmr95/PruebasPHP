<?php

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
