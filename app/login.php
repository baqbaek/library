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

// Sprawdzenie, czy użytkownik jest zalogowany
if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Pobranie hasła i soli dla podanej nazwy użytkownika z bazy danych
    $sql = "SELECT password, salt FROM users WHERE username = '$username'";
    $result = mysqli_query($connection, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $stored_password = $row['password'];
        $salt = $row['salt'];

        // Hashowanie wprowadzonego hasła z solą i pieprzem
        $hashed_password = hash('sha256', $password . $salt . $pepper);

        // Sprawdzenie, czy hasło jest poprawne
        if ($hashed_password == $stored_password) {
            // Użytkownik zalogowany poprawnie, ustaw zmienną sesji
            $_SESSION['user'] = $username;
            header("Location: dashboard.php");
            exit();
        } else {
            // Błędne dane logowania, przekieruj z powrotem do strony logowania
            header("Location: index.php?error=invalid_login");
            exit();
        }
    } else {
        // Błędne dane logowania, przekieruj z powrotem do strony logowania
        header("Location: index.php?error=invalid_login");
        exit();
    }
}
?>
