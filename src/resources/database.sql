create database veterinaria_gato_pianista;

use veterinaria_gato_pianista;

create table user_account(
    id int auto_increment,
    username varchar(50) not null,
    password binary(60) not null,
    role varchar(20) not null,
    registration_date date,
    status varchar(15) default 'Active',

    constraint user_account_pk primary key (id),
    constraint user_account_username_uk unique (username),
    constraint user_account_status_chk check (status in ('Active', 'Inactive')),
    constraint user_account_role_chk check (role in ('Tutor', 'Doctor', 'Employee', 'Admin'))
);

create table doctor(
    id int auto_increment,
    name varchar(100) not null,
    cpf varchar(14) not null,
    crmv varchar(50) not null,
    phone_number varchar(15),
    date_of_birth date,
    hiring_date date not null,
    registration_date date,
    status varchar(15) default 'Active',
    account_id int not null,

    constraint doctor_pk primary key (id),
    constraint doctor_cpf_uk unique (cpf),
    constraint doctor_crmv_uk unique (crmv),
    constraint doctor_user_account_fk foreign key (account_id) references user_account(id),
    constraint doctor_date_of_birth_chk check (date_of_birth < hiring_date and date_of_birth < registration_date),
    constraint doctor_status_chk check (status in ('Active', 'Inactive'))
);

create table tutor(
    id int auto_increment, 
    name varchar(100) not null,
    cpf varchar(14) not null,
    phone_number varchar(15),
    date_of_birth date,
    registration_date date,
    status varchar(15) default 'Active',
    account_id int,

    constraint tutor_pk primary key (id),
    constraint tutor_cpf_uk unique (cpf),
    constraint tutor_user_account_fk foreign key (account_id) references user_account(id),
    constraint tutor_date_of_birth_chk check (date_of_birth < registration_date),
    constraint tutor_status_chk check (status in ('Active', 'Inactive'))
);

create table animal(
    id int auto_increment,
    name varchar(100) not null,
    specie varchar(50) not null,
    race varchar(50),
    tutor_id int not null,
    date_of_birth date,
    registration_date date,
    status varchar(15) default 'Active',

    constraint animal_pk primary key (id),
    constraint animal_name_tutor_uk unique (name, tutor_id),
    constraint animal_tutor foreign key (tutor_id) references tutor(id),
    constraint animal_date_of_birth_chk check (date_of_birth < registration_date),
    constraint animal_status_chk check (status in ('Active', 'Inactive'))
);

create table appointment(
    id int auto_increment,
    animal_id int not null,
    doctor_id int not null,
    start_datetime datetime not null,
    type varchar(20) not null,
    reason varchar(255),
    action varchar(255),
    end_datetime datetime,

    constraint appointment_pk primary key (id),
    constraint appointment_animal_doctor_start unique (animal_id, doctor_id, start_datetime),
    constraint appointment_animal_fk foreign key (animal_id) references animal(id),
    constraint appointment_doctor_fk foreign key (doctor_id) references doctor(id),
    constraint appointment_end_datetime_chk check (end_datetime > start_datetime)
);

insert into user_account(username, password, role, registration_date) values 
    ('tiago_trojahn_as_tutor', '$2y$17$g7D3RqtAh7bHV.kjDNoAEu5c6fYuOwPQeVnT9posn4nxopuGWSaqC',
        'Tutor', '2023-06-05'),
    ('tiago_trojahn_as_doctor', '$2y$17$PZUdC8wp7EpE4MKgS93Ro.JdZbo0LZVV5OmsupO.pS73JIvSvGu0i',
        'Doctor', '2023-06-05'),
    ('Veterinario 1', '$2y$17$dKuhgm5nExPxzHz5mXzY5elIyQ2jts6ZbYB78Zx6ZtMibawSLFnve',
        'Doctor', '2023-06-05'),
    ('Veterinario 2', '$2y$17$fM3eyGihIKhM/NFDGCu2POMbNq2C.skXqbD1m44ddgvpzuP88jBvy',
        'Doctor', '2023-06-05'),
    ('Veterinario 3', '$2y$17$VBIwVvYAQw9O3i/dldQL4ekP3yrkMh1NPBcJZNjLpfnY7uwAVL0ju',
        'Doctor', '2023-06-05'),
    ('Veterinario 4', '$2y$17$D9i0Wzez4Yf.JKHMjn3oQu/kDKU9uZzMP8g00wBj2ZgPvoMhpUiKW',
        'Doctor', '2023-06-05'),
    ('tiago_trojahn_as_employee', '$2y$17$WQyJzEqLpyh2t1O.hDz7nOPuJNqwsvewTt845csdqks900sXIsXaq',
        'Employee', '2023-06-05'),
    ('dono_do_gato_pianista', '$2y$17$/sKJvjsK/MqQ4RdKQPBjs.FSJlmpmzMML/0Dj/o9v0JW8Rho4SX3q',
        'Tutor', '2023-06-05'),
    ('tiago_trojahn_as_admin', '$2y$17$R7I9McqpuvWC9MUdHhOzBO0X1k9rdoQ5OY/5HSJSUQyWYexaQsIsq',
        'Admin', '2023-06-05');

insert into tutor(name, cpf, phone_number, date_of_birth, registration_date, account_id) values
    ('Tiago Trojahn', '123456789-10', '(11)11111-1111', '2000-01-01', '2023-06-05', 
        (select id from user_account where username = 'tiago-trojahn_as_tutor')),
    ('Tutor 1', '23456789101', '(11)22222-2222', '1998-04-20', '2023-06-05', null),
    ('Tutor 2', '34567891012', '(11)33333-3333', '1980-05-10', '2023-06-05', null),
    ('Tutor 3', '45678910123', '(11)44444-4444', '2000-10-17', '2023-06-05', null),
    ('Dono do Gato Pianista', '56789101234', '(11)28310-0140', '2002-05-21', '2023-06-05',
        (select id from user_account where username = 'dono_do_gato_pianista'));

insert into doctor(name, cpf, crmv, phone_number, date_of_birth, hiring_date, registration_date, account_id) values
    ('Dr. Tiago Trojahn', '12345678910', 'CRMV-SP1111', '(11)12345-6789', '2000-01-01', '2023-06-05', '2023-06-05',
        (select id from user_account where username = 'tiago_trojahn_as_doctor')),
    ('Dr. Veterinario 1', '67891012345', 'CRMV-SP2222', '(11)23456-7890', '1990-10-12', '2023-06-05', '2023-06-05',
        (select id from user_account where username = 'Veterinario 1')),
    ('Dr. Veterinario 2', '78910123456', 'CRMV-SP3333', '(11)34567-8901', '1984-12-20', '2023-06-05', '2023-06-05',
        (select id from user_account where username = 'Veterinario 2')),
    ('Dr. Veterinario 3', '89101234567', 'CRMV-SP4444', '(11)45678-9012', '2004-01-10', '2023-06-05', '2023-06-05',
        (select id from user_account where username = 'Veterinario 3')),
    ('Dr. Veterinario 4', '91012345678', 'CRMV-SP5555', '(11)56789-0123', '2000-06-15', '2023-06-05', '2023-06-05',
        (select id from user_account where username = 'Veterinario 4'));

insert into animal(name, specie, race, tutor_id, date_of_birth, registration_date) values
    ('Toto', 'Cachorro', 'Husk', (select id from tutor where name = 'Tiago Trojahn'), '2020-08-14', '2023-06-05'),
    ('Spike', 'Cachorro', 'Pastor Alemao', (select id from tutor where name = 'Tutor 3'), '2023-01-20', '2023-06-05'),
    ('Tobias', 'Cachorro', 'Boxer', (select id from tutor where name = 'Tutor 3'), '2021-10-03', '2023-06-05'),
    ('Mariana', 'Tartaruga', null, (select id from tutor where name = 'Tutor 1'), '2022-04-14', '2023-06-05'),
    ('Gato Pianista', 'Gato', 'Laranjinha', (select id from tutor where name = 'Dono do Gato Pianista'), 
        '2021-06-12', '2023-06-05');

insert into appointment(doctor_id, animal_id, start_datetime, type, reason, action, end_datetime) values
    ((select id from doctor where name = 'Dr. Tiago Trojahn'), (select id from animal where name = 'Gato Pianista'),
        '2023-06-15 08:00:00', 'Examination', 'É a prova', null, null),
    ((select id from doctor where name = 'Dr. Veterinario 1'), (select id from animal where name = 'Toto'),
        '2023-05-15 14:35:00', 'Examination', 'Dodói', 'Constatado que está dodói', '2023-05-15 14:50:00'),
    ((select id from doctor where name = 'Dr. Veterinario 2'), (select id from animal where name = 'Spike'),
        '2023-05-20 11:00:00', 'Examination', 'Checkup', 'Checkup', '2023-05-20 11:15:00'),
    ((select id from doctor where name = 'Dr. Veterinario 3'), (select id from animal where name = 'Toto'),
        '2023-05-20 12:00:00', 'Surgery', 'Cirurgia para não ficar mais dodói', 'Cirugia complexa', 
        '2023-05-20 15:49:00'),
    ((select id from doctor where name = 'Dr. Veterinario 4'), (select id from animal where name = 'Mariana'),
        '2023-06-01 16:19:58', 'Examination', 'Problemas de Tartaruga', 'Resolvidos os Problemas', 
        '2023-06-01 16:25:00');
