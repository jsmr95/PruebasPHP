<?php

function compruebaBuscadoresInformatica($pdo,$buscarArticulo,$buscarMarca){
  if($buscarMarca != '') {
    $st = $pdo->prepare("SELECT p.*, genero
                        FROM productos p
                        JOIN generos g
                        ON genero_id = g.id
                        WHERE position(lower(:articulo) in lower(articulo)) != 0
                        AND position(lower(:marca) in lower(marca)) != 0
                        AND g.genero = 'Informatica' ");
    $st->execute([':articulo' => "$buscarArticulo", ':marca' => "$buscarMarca"]);
  } else {
    $st = $pdo->prepare("SELECT p.*, genero
                        FROM productos p
                        JOIN generos g
                        ON genero_id = g.id
                        WHERE position(lower(:articulo) in lower(articulo)) != 0
                        AND g.genero = 'Informatica' ");                         //position es como mb_substrpos() de php, devuelve 0
                                                                                //si no encuentra nada. ponemos lower() de postgre para
                                                                                //que no distinga entre mayu y minus
    $st->execute([':articulo' => "$buscarArticulo"]);
  }
  return $st;
}
