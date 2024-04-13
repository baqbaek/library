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
if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Pobranie zaszyfrowanego hasła dla podanej nazwy użytkownika z bazy danych
    $sql = "SELECT password FROM users WHERE username = '$username'";
    $result = mysqli_query($connection, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $hashed_password = $row['password'];

        // Porównanie hasła wprowadzonego przez użytkownika z zaszyfrowanym hasłem z bazy danych
        if (password_verify($password, $hashed_password)) {
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
