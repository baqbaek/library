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

// Pobranie nazwy zalogowanego użytkownika
$user_name = $_SESSION['user'];

// Definicja różnych typów użytkowników
$admin_users = array('admin');
$librarian_users = array('bibliotekarz');
$client_users = array('klient');

// Sprawdzenie, do jakiego typu użytkownika należy zalogowany użytkownik
if (in_array($user_name, $admin_users)) {
    // Wyświetlenie zawartości dla administratora
    $dashboard_content = 'Witaj, Administratorze!';
} elseif (in_array($user_name, $librarian_users)) {
    // Wyświetlenie zawartości dla bibliotekarza
    $dashboard_content = 'Witaj, Bibliotekarzu!';
} elseif (in_array($user_name, $client_users)) {
    // Wyświetlenie zawartości dla klienta
    $dashboard_content = 'Witaj, Kliencie!';
} else {
    // W przypadku, gdy zalogowany użytkownik nie należy do żadnego zdefiniowanego typu
    $dashboard_content = 'Nieznany typ użytkownika';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Dodanie stylów Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <!-- Zakładki -->
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link active" href="#books" data-toggle="tab">Książki</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#profile" data-toggle="tab">Profil</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Wyloguj się</a>
            </li>
        </ul>

        <!-- Zawartość zakładek -->
        <div class="tab-content">
            <!-- Zakładka Książki -->
            <div class="tab-pane fade show active" id="books">
                <h3>Książki</h3>
                <p>Tutaj znajdziesz dostępne książki do wypożyczenia.</p>

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
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . $row['title'] . '</h5>';
                        echo '<p class="card-text">Autor: ' . $row['author'] . '</p>';
                        echo '<p class="card-text">Kategoria: ' . $row['category'] . '</p>';
                        echo '<p class="card-text">Opis: ' . $row['description'] . '</p>';
                        if ($row['available']) {
                            echo '<p class="card-text text-success">Dostępna</p>';
                        } else {
                            echo '<p class="card-text text-danger">Niedostępna</p>';
                        }
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>

                <!-- Przypadki użycia -->
                <h5>Funkcje do zaimplementowania</h5>
                <ul>
                    <li>Wypożycz książkę</li>
                    <li>Zgłoś zagubienie książki</li>
                    <li>Przeglądaj historię wypożyczeń</li>
                    <li>Opłać karę</li>
                    <li>Zwróć książki</li>
                </ul>
            </div>

            <!-- Zakładka Profil -->
            <div class="tab-pane fade" id="profile">
                <h3>Profil</h3>
                <p>Witaj, <?php echo $user_name; ?>!</p>
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

            <!-- Zakładka Przeglądanie historii wypożyczeń -->
            <div class="tab-pane fade" id="borrow_history">
                <h3>Historia wypożyczeń</h3>
                <p>Tutaj znajdziesz historię swoich wypożyczeń.</p>
                <!-- Tutaj możesz dodać kod do wyświetlania historii wypożyczeń -->
            </div>
        </div>
    </div>

    <!-- Dodanie skryptów Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
