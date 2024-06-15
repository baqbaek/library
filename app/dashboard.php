<?php
session_start();

// Połączenie z bazą danych
$host = 'localhost';
$username = 'admin'; 
$password = 'admin'; 
$database = 'biblioteka'; 

$connection = mysqli_connect($host, $username, $password, $database);

// Sprawdzenie połączenia
if (!$connection) {
    die('Błąd połączenia z bazą danych: ' . mysqli_connect_error());
}

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['user'])) {
    // Jeśli nie jest zalogowany, przekieruj go do strony logowania
    header('Location: index.php');
    exit();
}

// Pobranie nazwy i typu zalogowanego użytkownika
$user_name = $_SESSION['user'];
$user_type = $_SESSION['user_type'];

// Definicja różnych typów użytkowników
$admin_users = array('admin');
$librarian_users = array('bibliotekarz');
$client_users = array('klient');

// Sprawdzenie, do jakiego typu użytkownika należy zalogowany użytkownik
if (in_array($user_type, $admin_users)) {
    // Wyświetlenie zawartości dla administratora
    $dashboard_content = 'Witaj, ' . ucfirst($user_name) . '! Jesteś zalogowany jako Administrator';
} elseif (in_array($user_type, $librarian_users)) {
    // Wyświetlenie zawartości dla bibliotekarza
    $dashboard_content = 'Witaj, ' . ucfirst($user_name) . '! Jesteś zalogowany jako Bibliotekarz';
} elseif (in_array($user_type, $client_users)) {
    // Wyświetlenie zawartości dla klienta
    $dashboard_content = 'Witaj, Kliencie!';
} else {
    // W przypadku, gdy zalogowany użytkownik nie należy do żadnego zdefiniowanego typu
    $dashboard_content = 'Nieznany typ użytkownika';
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="icon" href="images/icon.png" type="image/png">
    <!-- Dodanie stylów Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body.white-scheme {
            background-color: white;
            color: black;
        }
        body.black-scheme {
            background-color: black;
            color: gray;
        }
        body.contrast-scheme {
            background-color: yellow;
            color: black;
        }
        .color-scheme-buttons {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .color-scheme-buttons button {
            margin-left: 5px;
        }
    </style>
</head>
<body class="white-scheme">
    <div class="container mt-5">
        <!-- Pasek nawigacyjny -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light" aria-label="Primary Navigation">
            <div class="container">
                <a class="navbar-brand" href="#">Biblioteka Online</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">Książki</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">Profil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="borrow.php">Wypożycz książkę</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Wyloguj się</a>
                        </li>
                    </ul>
                    <?php if (in_array($user_type, $admin_users) || in_array($user_type, $librarian_users)) { ?>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="manage_books.php">Zarządzaj książkami</a>
                            </li>
                        </ul>
                    <?php } ?>
                </div>
            </div>
        </nav>

        <!-- Przycisk zmiany schematu kolorystycznego -->
        <div class="color-scheme-buttons">
            <button class="btn btn-light" onclick="changeColorScheme('white')">Biały</button>
            <button class="btn btn-dark" onclick="changeColorScheme('black')">Czarny</button>
            <button class="btn btn-warning" onclick="changeColorScheme('contrast')">Kontrast</button>
        </div>

        <!-- Zawartość -->
        <div class="mt-3">
            <h3><?php echo $dashboard_content; ?></h3>
            <h6>Tutaj znajdziesz dostępne książki do wypożyczenia.</h6>

            <!-- Lista książek -->
            <div class="row">
                <?php
                // Pobranie danych o książkach z bazy danych
                $books_query = "SELECT * FROM books";
                $result = mysqli_query($connection, $books_query);

                // Wyświetlenie książek
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="col-md-4 mb-3">';
                    echo '<div class="card">';
                    echo '<img src="' . $row['image_path'] . '" class="card-img-top" alt="Obraz książki">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $row['title'] . '</h5>';
                    echo '<p class="card-text">Autor: ' . $row['author'] . '</p>';
                    echo '<p class="card-text">Kategoria: ' . $row['category'] . '</p>';
                    echo '<p class="card-text">Opis: ' . $row['description'] . '</p>';
                    echo '<p class="card-text">Ilość dostępnych egzemplarzy: ' . $row['quantity'] . '</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Dodanie skryptów jQuery i Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function changeColorScheme(color) {
            document.body.className = color + '-scheme';
        }
    </script>
</body>
</html>
