create database pokemondatabase
    default character set utf8
    collate utf8_unicode_ci;

use pokemondatabase;

create table pokemon (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    weight FLOAT NOT NULL,
    height FLOAT NOT NULL,
    tipo ENUM('Agua', 'Fuego', 'Planta') NOT NULL,
    evolution INT DEFAULT NULL
) engine=innodb default charset=utf8 collate=utf8_unicode_ci;

create user pokemonuser@localhost
    identified by 'root';

grant all
    on pokemondatabase.*
    to pokemonuser@localhost;

flush privileges;