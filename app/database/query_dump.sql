create database cncverse;

use cncverse;

create table usuarios(
	id int not null primary key AUTO_INCREMENT,
	nome varchar(100) not null,
	sobrenome varchar(60) not null,
	email varchar(150) not null,
	senha varchar(32) not null,
	imagem varchar(200),
	tipo varchar(32) not null default 'Padrão',
	ativo tinyint not null default 1,
	deleted_at datetime default null,
	updated_at datetime default null,
	created_at datetime default current_timestamp
);

create table categorias (
	id int not null primary key AUTO_INCREMENT,
    id_usuario int,
	nome varchar(100) not null,
	ativo tinyint not null default 1,
	deleted_at datetime default null,
	updated_at datetime default null,
	created_at datetime default current_timestamp,
    foreign key (id_usuario) references usuarios(id)
);

create table arquivos (
	id int not null primary key AUTO_INCREMENT,
    id_usuario int not null,	
    id_categoria int not null,	
    titulo varchar(200) not null,
    descricao text not null,
    arquivo varchar(200) not null,
    extensao varchar(10) not null,
    tamanho float not null,
    visualizacoes int,
    ativo tinyint not null default 1,
	deleted_at datetime default null,
	updated_at datetime default null,
	created_at datetime default current_timestamp,
    foreign key (id_usuario) references usuarios(id),
    foreign key (id_categoria) references categorias(id)   
);

create table imagens (
	id int not null primary key AUTO_INCREMENT,
    id_arquivo int not null,	
    nome varchar(200) not null,
    created_at datetime default current_timestamp,
    foreign key (id_arquivo) references arquivos(id)
);

create table downloads (
	id int not null primary key AUTO_INCREMENT,
    id_usuario int not null,
    id_arquivo int not null,
    ip varchar(20) not null,
    data_download datetime default current_timestamp,
    foreign key (id_usuario) references usuarios(id),
    foreign key (id_arquivo) references arquivos(id)
);

create table comentarios (
	id int not null primary key AUTO_INCREMENT,
    id_usuario int not null,
    id_arquivo int not null,
    comentario text not null,
    visivel tinyint default 0,
    data_comentario datetime default current_timestamp,
    foreign key (id_usuario) references usuarios(id),
    foreign key (id_arquivo) references arquivos(id)
);
    


    
    



