DROP DATABASE biblioteka;

CREATE DATABASE biblioteka;

USE biblioteka;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    user_type ENUM('admin', 'bibliotekarz', 'klient') NOT NULL DEFAULT 'klient';
);
ALTER TABLE users ADD COLUMN salt VARCHAR(32) NOT NULL;

CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    description TEXT,
    available INT,
    image_path VARCHAR(255),
    quantity INT
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

INSERT INTO books (title, author, category, description, available, image_path, quantity)
VALUES ('Harry Potter i Kamień Filozoficzny', 'J.K. Rowling', 'Fantastyka', 'Pierwsza część przygód Harry\'ego Pottera.', 1, 'https://ecsmedia.pl/c/harry-potter-i-kamien-filozoficzny-tom-1-w-iext146745658.jpg', 10),
       ('Władca Pierścieni: Drużyna Pierścienia', 'J.R.R. Tolkien', 'Fantasy', 'Pierwsza część trylogii Władca Pierścieni.', 1, 'https://s.lubimyczytac.pl/upload/books/159000/159957/352x500.jpg', 2),
       ('Metro 2033', 'Dmitry Glukhovsky', 'Science Fiction', 'Historia osadzona w postapokaliptycznym świecie Moskwy.', 1, 'https://s.lubimyczytac.pl/upload/books/4864000/4864254/693340-352x500.jpg', 0),
       ('1984', 'George Orwell', 'Dystopia', 'Klasyczna powieść dystopijna o totalitarnym społeczeństwie.', 1, 'https://ksiazkiprzyherbacie.pl/environment/cache/images/0_0_productGfx_10896/Rok-1984_orwell_ksiegarniaksiazkiprzyherbacie.jpg', 1),
       ('Zbrodnia i Kara', 'Fiodor Dostojewski', 'Literatura Rosyjska', 'Historia biednego studenta, który popełnia zbrodnię.', 1, 'https://s.lubimyczytac.pl/upload/books/5053000/5053602/1043237-352x500.jpg', 3),
       ('W pustyni i w puszczy', 'Henryk Sienkiewicz', 'Przygodowa', 'Opowieść o dwóch chłopcach, Staśku i Nel, którzy wyruszają na wyprawę do Afryki.', 1, 'https://ecsmedia.pl/c/w-pustyni-i-w-puszczy-b-iext151438050.jpg', 3),
       ('Pan Tadeusz', 'Adam Mickiewicz', 'Epicka', 'Epopeja narodowa opisująca losy szlachty polskiej w czasach napoleońskich.', 1, 'https://api.culture.pl/sites/default/files/styles/420_auto/public/2022-06/pan_tadeusz_polona_11.jpg?itok=yzcpi1GZ', 1),
       ('Quo Vadis', 'Henryk Sienkiewicz', 'Historyczna', 'Historia miłości pomiędzy rzymskim wodzem, a chrześcijańską dziewczyną w czasach Nerona.', 1, 'https://ecsmedia.pl/c/quo-vadis-w-iext121792362.jpg', 0 ),
       ('Hobbit, czyli tam i z powrotem', 'J.R.R. Tolkien', 'Fantasy', 'Opowieść o hobbitach, podróży, przygodach i skarbach.', 1, 'https://s.lubimyczytac.pl/upload/books/5060000/5060266/1090573-352x500.jpg', 3);

INSERT INTO users (username, password) VALUES
('admin', 'admin123'),
('bibliotekarz', 'biblio123'),
('klient', 'klient123');