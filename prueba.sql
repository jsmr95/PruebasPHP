DROP TABLE IF EXISTS generos CASCADE;

CREATE TABLE generos
(
    id     BIGSERIAL    PRIMARY KEY
  , genero VARCHAR(255) NOT NULL UNIQUE
);

DROP TABLE IF EXISTS articulos CASCADE;

CREATE TABLE articulos
(
    id        BIGSERIAL    PRIMARY KEY
  , articulo  VARCHAR(255) NOT NULL
  , marca     VARCHAR(255) NOT NULL
  , precio    NUMERIC(10,2)
  , descripcion  TEXT
  , genero_id BIGINT       NOT NULL
                           REFERENCES generos (id)
                           ON DELETE NO ACTION
                           ON UPDATE CASCADE
);

-- INSERT

INSERT INTO generos (genero)
VALUES ('Informatica')
     , ('Electrodomesticos')
     , ('Limpieza')
     , ('Papeleria')
     , ('Juguetes')
     , ('Moda')
     , ('Bebe')
     , ('Videojuegos')
     , ('Deportes')
     , ('Mascotas')
     , ('Jardin')
     , ('Electronica');

INSERT INTO productos (articulo, marca, precio, descripcion, genero_id)
VALUES ('Portatil Asus ROG','Asus',1050.99,'Portatil Asus 8GB RAM 1Tb Nvidia Geforce GTX',1)
      , ('Disco Duro Multimedia','Toshiba',50,'Disco duro Toshiba 3Tb',1)
      , ('Lavavajillas','Balay',299,'Lavavajillas Balay 3VS 305BP',2)
      , ('Frigorifico doble','Daewoo',679.99,'Frigorifico doble puerta ULTRAFOOST',2)
      , ('Roomba 2000','iRobot',199,'Robot limpiasuelos',3)
      , ('Aspiradora','Cecotec',430,'Aspiradora Cecotec 45HJ sin cables',3)
      , ('Pack Boligrafos','PaperMate',5.90,'Conjunto de Boligrafos PaperMate',4)
      , ('Folios','Auchan',4,'Pack 500 folios',4)
      , ('Spiderman peluche','Bandai',15,'Peluche Spiderman lana 10:1',5)
      , ('Coche Radio control','Hot Wheels', 29.99,'Radio Control Hot wheels furioso',5)
      , ('Sudadera','Nike',45,'Sudadera Nike Mujer Rosa',6)
      , ('Vaquero','Leevis',50,'Vaqueros Leevis Hombre Negro',6)
      , ('Pañales','Baby',18,'Pack 70 Pañales',7)
      , ('Babero silicona','Baby',5,'Babero de silicona lavable',7)
      , ('GTA 5','Rockstar',55,'Grand Theft Auto 5 PS4',8)
      , ('Play Station 4','Play Station', 300,'Play Station 4 Pack Redemption II',8)
      , ('Pelota Liga','Nike',25.90,'Pelota de la Liga Santander',9)
      , ('Guantes','KickOne',13,'Guantes Boxeo Amateur',9)
      , ('Caseta King','PEThouse',38,'Caseta de perro 100x90x90',10)
      , ('Rascador felino','CatItem',24,'Rascador para diversion de tu gato',10)
      , ('Rastrillo','TuJardin',10.99,'Rastrillo mango ancho y alto',11)
      , ('Tijeras Poda','Podator',15.50,'Tijeros ideal para poder setos',11)
      , ('Iphone X','Apple',1090.99,'Iphone X 128GB Gris Espacial',12)
      , ('Television 55"','Sony',999,'Sony Bravia 55" 4K',12);
