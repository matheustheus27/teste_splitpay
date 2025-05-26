-- Criação de Tabelas

create table client (
    id varchar(11) primary key,
    name varchar(30) not null,
    email varchar(255) unique,
    phone varchar(20) unique
);

create table professional (
    id varchar(11) primary key,
    name varchar(30) not null,
    specialty varchar(30) not null
);

create table product (
    id int primary key,
    name varchar(30) not null,
    price decimal(10, 2) not null
);

create table service (
    id int primary key,
    name varchar(30) not null,
    price decimal(10, 2) not null
);

create table revenue (
    id int primary key,
    dt_creation datetime not null
);

create table professionalcare (
    id int primary key,
    revenue_id int not null,
    client_id varchar(11) not null,
    professional_id varchar(11),
    dt_service datetime not null,
    foreign key (revenue_id) references revenue(id) on delete cascade,
    foreign key (client_id) references client(id),
    foreign key (professional_id) references professional(id)
);

create table payment (
    id int primary key,
    revenue_id int not null,
    amount decimal(10, 2) not null,
    method varchar(20),
    dt_payment datetime not null,
    foreign key (revenue_id) references revenue(id) on delete cascade
);

create table productcare (
    care_id int,
    product_id int,
    primary key (care_id, product_id),
    foreign key (care_id) references professionalcare(id) on delete cascade,
    foreign key (product_id) references product(id)
);

create table servicecare (
    care_id int,
    service_id int,
    primary key (care_id, service_id),
    foreign key (care_id) references professionalcare(id) on delete cascade,
    foreign key (service_id) references service(id)
);


-- População de Banco de Dados

insert into client values ('11111111111', 'José da Silva', 'jose@teste.com', '(31) 99999-9999');
insert into client values ('22222222222', 'Maria Oliveira', 'maria@teste.com', '(31) 88888-8888');

insert into professional values ('11111111111', 'Dr Guilherme de Paula', 'Clinico Geral');
insert into professional values ('22222222222', 'Dra Maria Alencar', 'Psquiatria');

insert into product values (10, 'Dipirona 1mg', 15.3);
insert into product values (11, 'Histamin 2mg', 11.99);

insert into service values (10, 'Consulta', 120);
insert into service values (11, 'Exame', 65.25);

insert into revenue values (10, '2025-05-24');
insert into revenue values (11, '2025-05-25');

insert into professionalcare values (10, 10, '11111111111', '22222222222', '2025-05-24');
insert into professionalcare values (11, 11, '22222222222', '11111111111', '2025-05-25');

insert into payment values (10, 10, 150.95, 'PIX', '2025-05-24');
insert into payment values (11, 10, 145.32, 'DEBITO', '2025-05-25');

insert into productcare values (10, 10);
insert into productcare values (10, 11);

insert into servicecare values (10, 10);
insert into servicecare values (11, 10);