<?php
session_start();

// Sprawdzenie danych logowania
$valid_users = array(
    'admin' => 'admin123',
    'bibliotekarz' => 'biblio123',
    'klient' => 'klient123'
);

$username = $_POST['username'];
$password = $_POST['password'];

if (isset($valid_users[$username]) && $valid_users[$username] === $password) {
    $_SESSION['user'] = $username;
    header('Location: dashboard.php');
} else {
    echo 'Błędna nazwa użytkownika lub hasło. <a href="index.php">Spróbuj ponownie</a>.';
}
?>
