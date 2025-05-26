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
    foreign key (revenue_id) references revenue(id) on delete cascade,
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