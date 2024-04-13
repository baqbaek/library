<?php
session_start();

// Usunięcie wszystkich zmiennych sesji
session_unset();

// Zniszczenie sesji
session_destroy();

// Przekierowanie użytkownika do strony logowania
header('Location: index.php');
exit();
?>
