<?php
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['user'])) {
    // Jeśli nie jest zalogowany, przekieruj go do strony logowania
    header('Location: index.php');
    exit();
}

// Pobranie nazwy zalogowanego użytkownika
$user_name = $_SESSION['user'];

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <!-- Dodanie stylów Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <!-- Pasek nawigacyjny -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
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
                            <a class="nav-link" href="logout.php">Wyloguj się</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Zawartość -->
        <div class="mt-3">
            <h3>Profil użytkownika: <?php echo $user_name; ?></h3>
            <p>Tutaj znajdziesz informacje o swoim profilu użytkownika.</p>
            <form action="change_password.php" method="post">
                <button type="submit" class="btn btn-link">Zmiana hasła</button>
            </form>
            <!-- Przypadki użycia -->
            <h5>Funkcje do zaimplementowania</h5>
            <ul>
                <li><a href="#borrow_history" data-toggle="tab">Przeglądanie historii wypożyczeń</a></li>
            </ul>
        </div>
    </div>

    <!-- Dodanie skryptów Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
