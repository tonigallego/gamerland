drop table usuarios cascade;

create table usuarios (
  id 				bigserial constraint pk_usuarios primary key,
  nombre 		varchar(25) not null constraint uq_nombre unique,
  password 	char(32) not null,
  email 		varchar(50) not null constraint uq_email unique,
  estado 		varchar(60),
  fecha_alt date default current_date,
	avatar 		varchar(50)
);

drop table seguimientos cascade;

create table seguimientos (
  id_seguidor bigint not null constraint fk_seguimiento_usuarios1 references usuarios (id),
  id_seguido 	bigint not null constraint fk_seguimiento_usuarios2 references usuarios (id),
  fecha 			timestamp default current_timestamp not null,
  constraint 	pk_seguimiento primary key (id_seguidor, id_seguido),
  constraint 	ck_seguimiento_id_distintos check (id_seguidor <> id_seguido)
);

drop table comentarios cascade;

create table comentarios (
  id 				bigserial constraint pk_comentarios primary key,
  contenido varchar(3000),
  fecha 		timestamp default current_timestamp,
  especial 	boolean   default FALSE,
  emisor 		bigint constraint fk_comentarios_usuarios_emisor references usuarios (id)
  								 on delete cascade on update cascade,
  receptor 	bigint constraint fk_comentarios_usuarios_receptor references usuarios (id)
  								 on delete cascade on update cascade  
);

drop table juegos cascade;

create table juegos (
  id 							bigserial constraint pk_juegos primary key,
  nombre 				 	varchar(30) not null,
  desarrolladora 	varchar(30),
  distribuidora 	varchar(30),
  genero 					varchar(30),
  descripcion 		varchar(500),
  fecha_lanz 			date,
	caratula 				varchar(50)
);

drop table tiene cascade;

create table tiene (
	usuario     bigint constraint fk_tiene_usuarios references usuarios (id)
								     on delete cascade on update cascade,
  juego       bigint constraint fk_pertenece_juegos references juegos (id)
  						       on delete cascade on update cascade,
-- En información habrán 3 posibles datos: "Lo quieres", "Lo tienes pendiente" y "Te lo has pasado"
  informacion varchar(20) not null,
  constraint 	pk_tiene primary key (usuario, juego)
);

drop table pertenece cascade;

create table pertenece (
  juego 			bigint 	constraint fk_pertenece_juegos references juegos (id)
  						 				on delete cascade on update cascade,
  sistema 		bigint 	constraint fk_pertenece_sistemas references sistemas (id)
  							 			on delete cascade on update cascade,
  constraint 	pk_pertenece primary key (juego, sistema)
);

drop table sistemas cascade;

create table sistemas (
  id 					bigserial constraint pk_sistemas primary key,
  nombre 			varchar(30) not null constraint uq_nombre_sistema unique,
  compania 		varchar(30),
  fecha_lanz 	date
);

drop table criticas cascade;

create table criticas (
  id 				bigserial constraint pk_criticas primary key,
  usuario 	bigint 		constraint fk_criticas_usuarios references usuarios (id)
  							 			on delete cascade on update cascade,
  juego 		bigint 		constraint fk_criticas_juegos references juegos (id)
  						 				on delete cascade on update cascade,
  contenido varchar(30000),
  nota 			numeric(2),
  fecha 		date default current_date,
  constraint uq_critica unique (usuario, juego)
);

drop table valoraciones cascade;

create table valoraciones (
  critica bigint constraint fk_puntuaciones_criticas references criticas (id)
  							 on delete cascade on update cascade,
  usuario bigint constraint fk_puntuaciones_usuarios references usuarios (id)
  							 on delete cascade on update cascade,
-- El valor corresponderá así: 1 ->positivo, -1 ->negativo
  valor numeric(1) not null,
  constraint uq_valoracion unique (usuario, critica)
);

drop table aportaciones cascade;

create table aportaciones (
  id 			bigserial constraint pk_aportaciones primary key,
  usuario bigint 		constraint fk_aportaciones_usuarios references usuarios (id)
  							 		on delete cascade on update cascade,
  juego 	bigint 		constraint fk_aportaciones_juegos references juegos (id)
  						 			on delete cascade on update cascade,
  fecha 	date default current_date
--  imagen
--  video
--  texto
);

insert into sistemas (nombre, compania) values ('Playstation 3', 'Sony');
insert into sistemas (nombre, compania) values ('Xbox 360', 'Microsoft');
insert into sistemas (nombre, compania) values ('Wii', 'Nintendo');
insert into sistemas (nombre, compania) values ('Playstation 4', 'Sony');
insert into sistemas (nombre, compania) values ('Xbox One', 'Microsoft');
insert into sistemas (nombre, compania) values ('Wii U', 'Nintendo');

insert into usuarios (nombre, password, email, avatar, estado) 
values ('travis_barker', md5('a'), '*@gmail.com', 'usuarios/antonio.jpg', 'Jugando actualmente a Mass Effect 3');
insert into usuarios (nombre, password, email, avatar, estado) 
values ('shulk', md5('a'), '**@gmail.com', 'usuarios/paco.gif', 'De viaje');
insert into usuarios (nombre, password, email, avatar, estado) 
values ('nathan', md5('a'), '****@gmail.com', 'usuarios/pepe.jpg', '...');
insert into usuarios (nombre, password, email, avatar, estado) 
values ('the_traveller', md5('a'), '****@gmail.com', 'usuarios/luis.jpg', 'Nuevo en la web');

insert into juegos (nombre, desarrolladora, distribuidora, genero, descripcion, caratula, fecha_lanz) 
values ('Mass Effect 3', 'Bioware', 'Electronic Arts', 'Rol', 'Un juego de rol espacial ambientado en el futuro', 'juegos/masseffect3.jpg', '09/03/2012');
insert into juegos (nombre, desarrolladora, distribuidora, genero, descripcion, caratula, fecha_lanz) 
values ('Uncharted 3', 'Naughty Dog', 'Sony', 'Accion', 'Un juego de acción con tiros y sigilo ambientado en el desierto', 'juegos/uncharted3.jpg', '01/11/2011');
insert into juegos (nombre, desarrolladora, distribuidora, genero, descripcion, caratula, fecha_lanz) 
values ('Xenoblade Chronicles X', 'Monolith Soft', 'Nintendo', 'Rol', 'Xenoblade Chronicles X, conocido en Japón como XenobladeX, es un videojuego de rol desarrollado por Monolith Soft y publicado por Nintendo para la consola de videojuegos Wii U.', 'juegos/xenobladex.png', '15/04/2015');
insert into juegos (nombre, desarrolladora, distribuidora, genero, descripcion, caratula, fecha_lanz) 
values ('Final Fantasy XV', 'Square Enix', 'Square Enix', 'Rol', 'La entreg número 15 de la emblemática saga japonesa', 'juegos/finalfantasyxv.jpg', '29/11/2016');
insert into juegos (nombre, desarrolladora, distribuidora, genero, descripcion, caratula, fecha_lanz) 
values ('Gears of War 4', 'Black Tusk Studios', 'Microsoft Studios', 'Acción', 'Gears of War 4 o Gears 4 es un videojuego de acción y shooter en tercera persona desarrollado por The Coalition y distribuido por Microsoft lanzado el 11 de octubre del 2016 para Xbox One y Windows 10.', 'juegos/gearsofwars4.jpg', '11/10/2016');
insert into juegos (nombre, desarrolladora, distribuidora, genero, descripcion, caratula, fecha_lanz) 
values ('Paper Mario: Color Splash', 'Intelligent System', 'Nintendo', 'Rol', 'Paper Mario: Color Splash es un juego de RPG de acción y aventuras en la serie Paper Mario para Wii U. Es la quinta entrega de la serie.', 'juegos/papermariocolorsplash.jpg', '07/10/2016');

insert into pertenece (juego, sistema) values (1,2);
insert into pertenece (juego, sistema) values (2,1);
insert into pertenece (juego, sistema) values (3,3);
insert into pertenece (juego, sistema) values (6,4);
insert into pertenece (juego, sistema) values (4,5);
insert into pertenece (juego, sistema) values (5,6);
insert into pertenece (juego, sistema) values (6,7);

create view karma as 
select u.id, u.nombre, u.avatar, coalesce(sum(v.valor), 0) as karma
from criticas c join valoraciones v on c.id = v.critica right join usuarios u on c.usuario = u.id
group by u.id, u.nombre, u.avatar;

create view juegos_valorados as
select avg(nota) as nota_media, j.* 
from criticas c join juegos j on c.juego = j.id 
group by j.id, j.nombre, j.desarrolladora, j.distribuidora, j.genero, j.descripcion, j.fecha_lanz, j.caratula;
