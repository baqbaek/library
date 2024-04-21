<?php
session_start();
$host = 'localhost';
$username = 'admin'; 
$password = 'admin'; 
$database = 'biblioteka'; 
$connection = mysqli_connect($host, $username, $password, $database);
if (!$connection) {
    die('Błąd połączenia z bazą danych: ' . mysqli_connect_error());
}
$user_name = $_SESSION['user'];

// Definicja różnych typów użytkowników
$admin_users = array('admin');
$librarian_users = array('bibliotekarz');
$client_users = array('klient');

$books_query = "SELECT * FROM books";
$books_result = mysqli_query($connection, $books_query);
$books = mysqli_fetch_all($books_result, MYSQLI_ASSOC);

// Obsługa zmiany ilości książek
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['book_id'])) {
        $book_id = $_POST['book_id'];

        // Zmniejszenie ilości dostępnych książek w bazie danych
        $update_query = "UPDATE books SET quantity = quantity - 1 WHERE id = ?";
        $stmt = $connection->prepare($update_query);
        $stmt->bind_param("i", $book_id);
        $stmt->execute();

        // Komunikat po wypożyczeniu książki
        echo "<script>alert('Książka została wypożyczona pomyślnie.')</script>";
    } else {
        echo "<script>alert('Proszę wybrać książkę.')</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Wypożycz książkę</title>
</head>
<body>
<div class="container mt-5">
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
                            <a class="nav-link" href="borrow.php">Wypożycz książkę</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Wyloguj się</a>
                        </li>
                     
                    </ul>
                    <?php if (in_array($user_name, $admin_users) || in_array($user_name, $librarian_users)) { ?>
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="manage_books.php">Zarządzaj książkami</a>
                            </li>
                        </ul>
                    <?php } ?>
                </div>
            </div>
        </nav>
    <h1>Wypożycz książkę</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div class="form-group">
            <label for="book_id">Wybierz książkę:</label>
            <select class="form-control" id="book_id" name="book_id" required>
                <?php foreach ($books as $book): ?>
                    <option value="<?php echo $book['id']; ?>"><?php echo $book['title'] . ' - ' . $book['author']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" name="submit">Wypożycz</button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
