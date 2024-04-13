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
        // Zaszyfrowanie hasła przed dodaniem do bazy danych
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Dodanie nowego użytkownika do bazy danych
        $sql_insert = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja</title>
    <!-- Dodanie stylów Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="bg-dark text-white p-3">
        <div class="container">
            <h1>Biblioteka Online</h1>
        </div>
    </header>
    <main class="py-5">
        <div class="container">
            <div id="register-form" class="bg-light p-4">
                <h2 class="mb-4">Zarejestruj się</h2>
                <form action="register.php" method="post">
                    <div class="form-group">
                        <label for="username">Nazwa użytkownika:</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Hasło:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
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
</body>
</html>
