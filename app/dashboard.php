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
                <div class="card-deck">
                    <div class="card">
                        <img src="book1.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Harry Potter i Kamień Filozoficzny</h5>
                            <p class="card-text">Autor: J.K. Rowling</p>
                            <p class="card-text">Kategoria: Fantasy</p>
                        </div>
                    </div>
                    <div class="card">
                        <img src="book2.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Dziady</h5>
                            <p class="card-text">Autor: Adam Mickiewicz</p>
                            <p class="card-text">Kategoria: Klasyczna literatura</p>
                        </div>
                    </div>
                    <div class="card">
                        <img src="book3.jpg" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title">Władca Pierścieni: Drużyna Pierścienia</h5>
                            <p class="card-text">Autor: J.R.R. Tolkien</p>
                            <p class="card-text">Kategoria: Fantasy</p>
                        </div>
                    </div>
                </div>
                <!-- Przypadki użycia -->
                <h5>Funkcje do zaimplementowania</h5>
                <ul>
                    <li>Wypożycz książkę</li>
                    <li>Przeglądaj książki</li>
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

                <!-- Przypadki użycia -->
                <h5>Funkcje do zaimplementowania</h5>
                <ul>
                    <li>Zmiana danych osobowych</li>
                    <li><a href="#borrow_history" data-toggle="tab">Przeglądanie historii wypożyczeń</a></li>
                    <li>
                        <form action="change_password.php" method="post">
                            <button type="submit" class="btn btn-link">Zmiana hasła</button>
                        </form>
                    </li>
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
