drop database pokemondatabase;
create database pokemondatabase
    default character set utf8
    collate utf8_unicode_ci;

use pokemondatabase;

CREATE TABLE `pokemon` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` varchar(50) NOT NULL,
  `ability` varchar(100) DEFAULT NULL,
  `hp` int DEFAULT NULL,
  `attack` int DEFAULT NULL,
  `defense` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `pokemon` (`id`, `name`, `type`, `ability`, `hp`, `attack`, `defense`) VALUES
(1, 'charizard', 'water', 'firebase', 100, 1600, 188),
(3, 'chorizeitor', 'carne', 'fire', 888, 99, 6);

create user 'pokemonuser2'@'localhost'
    identified by 'root';

grant all
    on pokemondatabase.*
    to pokemonuser2@localhost;

flush privileges;