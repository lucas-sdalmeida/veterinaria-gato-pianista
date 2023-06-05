create database veterinaria_gato_pianista;

use veterinaria_gato_pianista;

create table user_account(
    id int,
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
    id int,
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
    id int, 
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
    id int,
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
    id int,
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
