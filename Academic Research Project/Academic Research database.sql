CREATE database ARC_Database;
USE ARC_Database;

CREATE TABLE Users (
	user_id int primary key auto_increment,
    username varchar(50) not null unique,
    email varchar(100) not null unique,
    password_hash varchar(250) not null,
    first_name varchar(50) not null,
    last_name varchar(50) not null,
    institution varchar(100) null,
    department varchar(100) null
);

CREATE TABLE Projects (
	project_id int primary key auto_increment,
    project_name varchar(200) not null,
    description text null,
    created timestamp default current_timestamp,
    owner_id int not null,
    foreign key (owner_id) references Users(user_id)
);

CREATE TABLE Documents (
	document_id int auto_increment primary key,
    document_name varchar(200) not null,
    file_path varchar(500) not null,
    project_id int not null,
    uploaded_by int not null,
    uploaded_at timestamp default current_timestamp,
    foreign key (project_id) references Projects(project_id),
    foreign key (uploaded_by) references Users(user_id)
);