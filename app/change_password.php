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

// Funkcja do zmiany hasła użytkownika
function changePassword($new_password) {
    $file_path = "users_passwords.txt";
    $file_content = file_get_contents($file_path);
    $lines = explode(PHP_EOL, $file_content);
    foreach ($lines as $key => $line) {
        $user_data = explode(":", $line);
        if ($user_data[0] == $user_name) {
            // Zmiana hasła
            $lines[$key] = $user_name . ":" . password_hash($new_password, PASSWORD_DEFAULT);
            break;
        }
    }
    // Zapisanie zmian do pliku
    file_put_contents($file_path, implode(PHP_EOL, $lines));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['new_password'])) {
        $new_password = $_POST['new_password'];
        changePassword($new_password);
        echo "<script>alert('Hasło zostało pomyślnie zmienione.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
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
                <label for="new_password">Nowe hasło:</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <button type="submit" class="btn btn-primary">Zmień hasło</button>
        </form>
    </div>

    <!-- Dodanie skryptów Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
