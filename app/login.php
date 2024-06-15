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

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    // Pobranie hasła i soli dla podanej nazwy użytkownika z bazy danych
    $sql = "SELECT password, salt FROM users WHERE username = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

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
            $error_message = "Błędne dane logowania.";
        }
    } else {
        $error_message = "Błędne dane logowania.";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteka Online</title>
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
            <div id="login-form" class="bg-light p-4">
                <h2 class="mb-4">Zaloguj się</h2>
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                <form action="login.php" method="post">
                    <div class="form-group">
                        <label for="username">Nazwa użytkownika:</label>
                        <input type="text" id="username" name="username" class="form-control" required aria-describedby="usernameHelp">
                        <small id="usernameHelp" class="form-text text-muted">Wprowadź swoją nazwę użytkownika.</small>
                    </div>
                    <div class="form-group">
                        <label for="password">Hasło:</label>
                        <input type="password" id="password" name="password" class="form-control" required aria-describedby="passwordHelp">
                        <small id="passwordHelp" class="form-text text-muted">Wprowadź swoje hasło.</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Zaloguj</button>
                </form>
                <p>Nie masz konta? <a href="register.php">Zarejestruj się</a></p>
            </div>
        </div>
    </main>
    <footer class="bg-dark text-white p-3">
        <div class="container">
            &copy; 2024 Biblioteka Online
        </div>
    </footer>
    <!-- Dodanie skryptów Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function changeColorScheme(color) {
            document.body.className = color + '-scheme';
        }
    </script>
</body>
</html>
