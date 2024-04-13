<?php
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['user'])) {
    // Jeśli nie jest zalogowany, przekieruj go do strony logowania
    header('Location: index.php');
    exit();
}

// Pobranie nazwy zalogowanego użytkownika
$user_type = $_SESSION['user'];

// Definicja różnych typów użytkowników
$admin_users = array('admin');
$librarian_users = array('bibliotekarz');
$client_users = array('klient');

// Sprawdzenie, do jakiego typu użytkownika należy zalogowany użytkownik
if (in_array($user_type, $admin_users)) {
    // Wyświetlenie zawartości dla administratora
    $dashboard_content = 'Witaj, Administratorze!';
} elseif (in_array($user_type, $librarian_users)) {
    // Wyświetlenie zawartości dla bibliotekarza
    $dashboard_content = 'Witaj, Bibliotekarzu!';
} elseif (in_array($user_type, $client_users)) {
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
        <h2 class="mb-4">Panel użytkownika</h2>
        <p><?php echo $dashboard_content; ?></p>
        <a href="logout.php" class="btn btn-danger">Wyloguj się</a>
    </div>
</body>
</html>
