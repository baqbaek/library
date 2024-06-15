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

// Stały pieprz (powinien być przechowywany w pliku konfiguracyjnym lub w zmiennej środowiskowej)
$pepper = "bartekKubaRafal";

// Funkcja do generowania soli
function generateSalt($length = 16) {
    return bin2hex(random_bytes($length / 2));
}

// Funkcja do zmiany hasła użytkownika
function changePassword($current_password, $new_password, $user_name, $connection) {
    global $pepper; // Użycie globalnej zmiennej $pepper

    // Zabezpieczenie hasła przed atakami SQL Injection
    $current_password = mysqli_real_escape_string($connection, $current_password);
    $new_password = mysqli_real_escape_string($connection, $new_password);

    // Pobranie aktualnego hasła i soli z bazy danych
    $sql = "SELECT password, salt FROM users WHERE username = '$user_name'";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);

    // Weryfikacja aktualnego hasła
    if ($row) {
        $stored_password = $row['password'];
        $salt = $row['salt'];
        $hashed_current_password = hash('sha256', $current_password . $salt . $pepper);

        if ($hashed_current_password != $stored_password) {
            echo "<script>alert('Nieprawidłowe aktualne hasło.'); window.location.href = 'change_password.php';</script>";
            return;
        }
    } else {
        echo "<script>alert('Użytkownik nie istnieje.'); window.location.href = 'change_password.php';</script>";
        return;
    }

    // Generowanie nowej soli
    $new_salt = generateSalt();

    // Hashowanie nowego hasła z nową solą i pieprzem
    $hashed_new_password = hash('sha256', $new_password . $new_salt . $pepper);

    // Zapytanie SQL do aktualizacji hasła i soli w bazie danych
    $sql = "UPDATE users SET password = '$hashed_new_password', salt = '$new_salt' WHERE username = '$user_name'";
    
    // Wykonanie zapytania
    if (mysqli_query($connection, $sql)) {
        echo "<script>window.location.href = 'dashboard.php'; alert('Hasło zostało pomyślnie zmienione.');</script>";
    } else {
        echo "Błąd podczas aktualizacji hasła: " . mysqli_error($connection);
    }
}

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

// Sprawdzenie, czy formularz został przesłany
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['current_password']) && isset($_POST['new_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        changePassword($current_password, $new_password, $user_name, $connection);
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zmiana hasła</title>
    <!-- Dodanie stylów Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Zmiana hasła dla użytkownika: <?php echo $user_name; ?></h2>
        <form action="change_password.php" method="post">
            <div class="form-group">
                <label for="current_password">Aktualne hasło:</label>
                <input type="password" class="form-control" id="current_password" name="current_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">Nowe hasło:</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <button type="button" class="btn btn-primary" onclick="window.location.href='profile.php'">Powrót</button>
            <button type="submit" class="btn btn-primary">Zmień hasło</button>
        </form>
    </div>

    <!-- Dodanie skryptów Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
