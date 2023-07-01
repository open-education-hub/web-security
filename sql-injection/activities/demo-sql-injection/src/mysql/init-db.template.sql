CREATE DATABASE IF NOT EXISTS demo;
USE demo;

CREATE TABLE IF NOT EXISTS users (name VARCHAR(20), password VARCHAR(32), descr VARCHAR(100));
INSERT INTO users VALUES ("admin", "admin", "admin");
INSERT INTO users VALUES ("ctf", "ctf", "ctf");


CREATE TABLE flags (k VARCHAR(100), v VARCHAR(100));
INSERT INTO flags VALUES ("1", "not_so_ez");
INSERT INTO flags VALUES ("2", "not_so_ez");
INSERT INTO flags VALUES ("3", "not_so_ez");
INSERT INTO flags VALUES ("4", "not_so_ez");
INSERT INTO flags VALUES ("5", "not_so_ez");
INSERT INTO flags VALUES ("6", "not_so_ez");
INSERT INTO flags VALUES ("7", "__TEMPLATE__");
