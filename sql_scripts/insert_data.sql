USE alumniClub;

INSERT INTO `users` 
(`id`, `username`, `password`, `firstName`, `lastName`, `email`, `role`,
`speciality`, `graduationYear`, `groupUni`, `faculty`, `longitude`, `latitude`) 
VALUES (1, 'vladislav_spasov', 'vladislav', 'Vladislav', 'Spasov', 'vladislav_spasov@abv.bg',
'user', 'computer sciences', '2021', '3', 'FMI', '24.5144297', '33.1243341');

INSERT INTO `users` 
(`id`, `username`, `password`, `firstName`, `lastName`, `email`, `role`,
`speciality`, `graduationYear`, `groupUni`, `faculty`, `longitude`, `latitude`) 
VALUES (2, 'kristiqn_kornazov', 'kristiqn', 'Kristiqn', 'Kornazov', 'kristiqn_kornazov@abv.bg', 
'user', 'informatics', '2021', '3', 'FMI', '24.143524245', '33.1422124135');

INSERT INTO `users` 
(`id`, `username`, `password`, `firstName`, `lastName`, `email`, `role`, 
`speciality`, `graduationYear`, `groupUni`, `faculty`, `longitude`, `latitude`) 
VALUES (3, 'alexandra_yovkova', 'alexandra', 'Alexandra', 'Yovkova', 'alexandra_yovkova@gmail.com',
'user', 'mathematics', '2024', '2', 'FMI', "23.234313414", "36.4252424");

INSERT INTO `users` 
(`id`, `username`, `password`, `firstName`, `lastName`, `email`, `role`, 
`speciality`, `graduationYear`, `groupUni`, `faculty`, `longitude`, `latitude`)
VALUES (4, 'stoqn_papazov', 'stoqn', 'Stoqn', 'Papazov', 'stoqn_papazov@gmail.com', 
'user', 'software engineering', '2024', '1', 'FMI', "23.1241241", "37.133141431");

INSERT INTO `users` 
(`id`, `username`, `password`, `firstName`, `lastName`, `email`, `role`, 
`speciality`, `graduationYear`, `groupUni`, `faculty`, `longitude`, `latitude`)
VALUES (5, 'pavel_stoqnov', 'pavel', 'Pavel', 'Stoqnov', 'pavel_stoqnov@gmail.com', 
'user', 'applied math', '2023', '2', 'FMI', NULL, NULL);

INSERT INTO `users` 
(`id`, `username`, `password`, `firstName`, `lastName`, `email`, `role`, 
`speciality`, `graduationYear`, `groupUni`, `faculty`, `longitude`, `latitude`)
VALUES (NULL, 'admin', 'admin', 'Vladimir', 'Georgiev', 'vladimir_georgiev@gmail.com', 
'admin', 'software engineering', '2022', '4', 'FMI', "22.1241241", "23.1414314");

INSERT INTO `posts` 
(`id`, `privacy`, `userId`, `occasion`, `location`, `content`, `occasionDate`)
VALUES (NULL, 'faculty', '1', 'Имен ден', 'София', 
'Колеги, заповядайте на именния ми ден .',
'2021-05-21 15:10:00');

INSERT INTO `posts` 
(`id`, `privacy`, `userId`, `occasion`, `location`, `content`, `occasionDate`)
VALUES (NULL, 'group', '3', 'Sofia film fest', 'София', 
'На някого ходи ли му се да погледаме малко кино?',
'2021-01-08 19:00:00');

INSERT INTO `posts` 
(`id`, `privacy`, `userId`, `occasion`, `location`, `content`, `occasionDate`)
VALUES (NULL, 'all', '4', ' Escape room', 'София', 
'Хайде да се съберем и да отидим на escape room!',
'2022-09-19 19:00:00');

INSERT INTO `posts` 
(`id`, `privacy`, `userId`, `occasion`, `location`, `content`, `occasionDate`)
VALUES (NULL, 'speciality', '5', 'Футбол', 'София', 
'На кого му се играе малко футбол?',
'2022-07-27 13:35:00');