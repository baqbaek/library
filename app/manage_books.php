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

// Pobranie listy książek z bazy danych
$books_query = "SELECT * FROM books";
$books_result = mysqli_query($connection, $books_query);
$books = mysqli_fetch_all($books_result, MYSQLI_ASSOC);

// Obsługa zmiany ilości książek
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['book_id']) && isset($_POST['quantity'])) {
        $book_id = $_POST['book_id'];
        $quantity = $_POST['quantity'];

        // Pobranie aktualnej ilości dostępnych egzemplarzy książki
        $book_query = "SELECT * FROM books WHERE id = $book_id";
        $book_result = mysqli_query($connection, $book_query);
        $book_row = mysqli_fetch_assoc($book_result);
        $current_quantity = $book_row['quantity'];

        // Dodanie lub odjęcie ilości książek
        if ($_POST['action'] == 'add') {
            $new_quantity = $current_quantity + $quantity;
        } elseif ($_POST['action'] == 'subtract') {
            $new_quantity = max(0, $current_quantity - $quantity);
        }

        // Aktualizacja ilości dostępnych egzemplarzy książki w bazie danych
        $update_query = "UPDATE books SET quantity = $new_quantity WHERE id = $book_id";
        if (mysqli_query($connection, $update_query)) {
            // Aktualizacja udana
            echo "<script>alert('Aktualizacja ilości książek zakończona pomyślnie.')</script>";
        } else {
            // Aktualizacja nieudana
            echo "<script>alert('Błąd podczas aktualizacji ilości książek: " . mysqli_error($connection) . "')</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarządzanie Książkami</title>
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
<body>
    <div class="container mt-5">
    <div class="color-scheme-buttons">
            <button class="btn btn-light" onclick="changeColorScheme('white')">Biały</button>
            <button class="btn btn-dark" onclick="changeColorScheme('black')">Czarny</button>
            <button class="btn btn-warning" onclick="changeColorScheme('contrast')">Kontrast</button>
        </div>
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
            <h3>Zarządzanie Książkami</h3>
            <p>Tutaj możesz zarządzać dostępnymi książkami w bibliotece.</p>
            <!-- Formularze zarządzania książkami -->
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="form-group">
                    <label for="book_id">Wybierz książkę:</label>
                    <select class="form-control" id="book_id" name="book_id" required>
                        <?php foreach ($books as $book): ?>
                            <option value="<?php echo $book['id']; ?>"><?php echo $book['title'] . ' - ' . $book['author']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="quantity">Ilość:</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" required>
                </div>
                <div class="form-group">
                    <label for="action">Akcja:</label>
                    <select class="form-control" id="action" name="action" required>
                        <option value="add">Dodaj</option>
                        <option value="subtract">Odejmij</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Wykonaj</button>
            </form>
        </div>
    </div>

    <!-- Dodanie skryptów Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function changeColorScheme(color) {
            document.body.className = color + '-scheme';
        }
    </script>
</body>
</html>
