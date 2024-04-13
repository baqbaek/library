CREATE DATABASE biblioteka;

USE biblioteka;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    user_type ENUM('admin', 'bibliotekarz', 'klient') NOT NULL
);

CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    description TEXT,
    available BOOLEAN NOT NULL DEFAULT 1
);

CREATE TABLE borrows (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    borrow_date DATE NOT NULL,
    return_date DATE,
    status ENUM('wypożyczona', 'zarezerwowana') NOT NULL
);

CREATE TABLE borrow_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    borrow_date DATE NOT NULL,
    return_date DATE,
    status ENUM('wypożyczona', 'zarezerwowana', 'zwrócona') NOT NULL
);

INSERT INTO books (title, author, category, description, available)
VALUES ('Harry Potter i Kamień Filozoficzny', 'J.K. Rowling', 'Fantastyka', 'Pierwsza część przygód Harry\'ego Pottera.', 1),
       ('Władca Pierścieni: Drużyna Pierścienia', 'J.R.R. Tolkien', 'Fantasy', 'Pierwsza część trylogii Władca Pierścieni.', 1),
       ('Metro 2033', 'Dmitry Glukhovsky', 'Science Fiction', 'Historia osadzona w postapokaliptycznym świecie Moskwy.', 1),
       ('1984', 'George Orwell', 'Dystopia', 'Klasyczna powieść dystopijna o totalitarnym społeczeństwie.', 1),
       ('Zbrodnia i Kara', 'Fiodor Dostojewski', 'Literatura Rosyjska', 'Historia biednego studenta, który popełnia zbrodnię.', 1);

