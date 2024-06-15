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

// Stały pieprz (powinien być przechowywany w pliku konfiguracyjnym lub w zmiennej środowiskowej)
$pepper = "bartekKubaRafal";

// Funkcja do generowania soli
function generateSalt($length = 16) {
    return bin2hex(random_bytes($length / 2));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Sprawdzenie, czy użytkownik o danej nazwie już istnieje
    $sql_check = "SELECT * FROM users WHERE username = '$username'";
    $result_check = mysqli_query($connection, $sql_check);

    if (mysqli_num_rows($result_check) > 0) {
        // Użytkownik o tej nazwie już istnieje 
        echo "<script>alert('Użytkownik o tej nazwie już istnieje.');</script>";
    } else {
        // Generowanie soli
        $salt = generateSalt();
        
        // Hashowanie hasła z solą i pieprzem
        $hashed_password = hash('sha256', $password . $salt . $pepper);

        // Dodanie nowego użytkownika do bazy danych z rolą klienta
        $sql_insert = "INSERT INTO users (username, password, salt, user_type) VALUES ('$username', '$hashed_password', '$salt', 'klient')";
        if (mysqli_query($connection, $sql_insert)) {
            // Przekierowanie użytkownika do strony logowania po udanej rejestracji
            echo "<script>window.location.href = 'index.php';</script>";
        } else {
            echo "Błąd podczas dodawania użytkownika: " . mysqli_error($connection);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja</title>
    <!-- Dodanie stylów Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
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
    <header class="bg-dark text-white p-3">
        <div class="container">
            <h1>Biblioteka Online</h1>
        </div>
    </header>
    <div class="color-scheme-buttons">
            <button class="btn btn-light" onclick="changeColorScheme('white')">Biały</button>
            <button class="btn btn-dark" onclick="changeColorScheme('black')">Czarny</button>
            <button class="btn btn-warning" onclick="changeColorScheme('contrast')">Kontrast</button>
        </div>
    <main class="py-5">
        <div class="container">
            <div id="register-form" class="bg-light p-4">
                <h2 class="mb-4">Zarejestruj się</h2>
                <form action="register.php" method="post">
                    <div class="form-group">
                        <label for="username">Nazwa użytkownika:</label>
                        <input type="text" id="username" name="username" class="form-control" required aria-describedby="usernameHelp">
                        <small id="usernameHelp" class="form-text text-muted">Wprowadź unikalną nazwę użytkownika.</small>
                    </div>
                    <div class="form-group">
                        <label for="password">Hasło:</label>
                        <input type="password" id="password" name="password" class="form-control" required aria-describedby="passwordHelp">
                        <small id="passwordHelp" class="form-text text-muted">Wprowadź silne hasło, składające się z co najmniej 8 znaków.</small>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="window.location.href='index.php'">Powrót</button>
                    <button type="submit" class="btn btn-primary">Zarejestruj</button>
                </form>
            </div>
        </div>
    </main>
    <footer class="bg-dark text-white p-3">
        <div class="container">
            &copy; 2024 Biblioteka Online
        </div>
    </footer>
    <!-- Dodanie skryptów Bootstrap -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function changeColorScheme(color) {
            document.body.className = color + '-scheme';
        }
    </script>
</body>
</html>
