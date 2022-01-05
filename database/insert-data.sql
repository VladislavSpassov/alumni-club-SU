use alumniclub;

INSERT INTO `users` 
(`id`, `username`, `password`, `firstName`, `lastName`, `email`, `role`,
`speciality`, `graduationYear`, `groupUni`, `faculty`, `longitude`, `latitude`) 
VALUES (NULL, 'ivan_ivanov', '$2y$10$Obktks5GamEVE1WIC7U2hOcyDKCzhn1mKAGTOgYiGY02iG68Yrsvy', 'Ivan', 'Ivanov', 'ivan_ivanov@abv.bg',
'user', 'software engineering', '2022', '4', 'FMI', '23.2804297', '42.6592600');
-- password: Ivan_ivanov123

INSERT INTO `users` 
(`id`, `username`, `password`, `firstName`, `lastName`, `email`, `role`,
`speciality`, `graduationYear`, `groupUni`, `faculty`, `longitude`, `latitude`) 
VALUES (NULL, 'petur_petrov', '$2y$10$koghoBgNWdm9/QzByZLRwephZoT861BxzMHVp8tuDUsMWX/SGIUe6', 'Petur', 'Petrov', 'petur-petrov@abv.bg', 
'user', 'software engineering', '2022', '4', 'FMI', '23.3177088', '42.6835968');
-- password: Petur_petrov789

INSERT INTO `users` 
(`id`, `username`, `password`, `firstName`, `lastName`, `email`, `role`, 
`speciality`, `graduationYear`, `groupUni`, `faculty`, `longitude`, `latitude`) 
VALUES (NULL, 'kirilKirilov', '$2y$10$3RSbMFaGiuzJbHAlhFC3o.MCawSNWF2.Hq3we5VHLhm4EtG6wHHXe', 'Kiril', 'Kirilov', 'kirilKirilov@gmail.com',
'user', 'pharmacy', '2023', '3', 'FHF', "23.3258777", "42.6656911");
-- password: kirilKirilov_456

INSERT INTO `users` 
(`id`, `username`, `password`, `firstName`, `lastName`, `email`, `role`, 
`speciality`, `graduationYear`, `groupUni`, `faculty`, `longitude`, `latitude`)
VALUES (NULL, 'tsvetelina_1999', '$2y$10$/i9bZ0Ymq7dfEld4e5DWfuegtOGwIBqyuq0wDHRzdY.BLwKVTN2qW', 'Tsvetelina', 'Tsvetanova', 'tsvetelina_1999@gmail.com', 
'user', 'pharmacy', '2024', '1', 'FHF', "23.7639718", "42.8964548");
-- password: tsvetelinaTs99

INSERT INTO `users` 
(`id`, `username`, `password`, `firstName`, `lastName`, `email`, `role`, 
`speciality`, `graduationYear`, `groupUni`, `faculty`, `longitude`, `latitude`)
VALUES (NULL, 'pavkata', '$2y$10$YmtYW5v/0bFNxfq/xWPEn.2szN1DVYZHabjPCFPCVHRZv361Evbza', 'Pavel', 'Pavlov', 'pavkata@gmail.com', 
'user', 'medicine', '2025', '2', 'FHF', NULL, NULL);
-- password: PavelPavlov2000

INSERT INTO `users` 
(`id`, `username`, `password`, `firstName`, `lastName`, `email`, `role`, 
`speciality`, `graduationYear`, `groupUni`, `faculty`, `longitude`, `latitude`)
VALUES (NULL, 'admin', '$2y$10$QKAZShnonS0SdBLPu/6ty.qrOHAaIoTd0aD0i0x9til2UcgzhJsvW', 'Georgi', 'Georgiev', 'georgiew@gmail.com', 
'admin', 'software engineering', '2024', '2', 'FMI', NULL, NULL);
-- password: Admin_359

INSERT INTO `posts` 
(`id`, `privacy`, `userId`, `occasion`, `location`, `content`, `occasionDate`)
VALUES (NULL, 'group', '1', 'Рожден ден', 'Златоград', 
'Здравейте, колеги. Заповядайте да се позабавляваме на рождения ми ден на 22ри юни.',
'2021-06-22 17:30:00');

INSERT INTO `posts` 
(`id`, `privacy`, `userId`, `occasion`, `location`, `content`, `occasionDate`)
VALUES (NULL, 'faculty', '3', 'Wrong fest', 'Пловдив', 
'Здравейте, смятам да ходя на Wrong fest в Пловдив. Ако някой друг ще ходи, ще се радвам да се видим!',
'2021-07-05 19:00:00');

INSERT INTO `posts` 
(`id`, `privacy`, `userId`, `occasion`, `location`, `content`, `occasionDate`)
VALUES (NULL, 'speciality', '4', 'Разходка из Витоша', 'София', 
'Хей, има ли желаещи за една разходка в планината?',
'2021-07-15 09:00:00');

INSERT INTO `posts` 
(`id`, `privacy`, `userId`, `occasion`, `location`, `content`, `occasionDate`)
VALUES (NULL, 'all', '5', 'Стрийт баскетбол', 'София', 
'Колеги, с приятели смятаме да се съберем да поиграем стрийт баскетбол. В случай, че искате да се пораздвижите, заповядайте!',
'2021-06-25 15:30:00');