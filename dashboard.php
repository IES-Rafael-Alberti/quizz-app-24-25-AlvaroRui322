<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];

    $stmt = $pdo->prepare("INSERT INTO cuestionarios (title, description) VALUES (?, ?)");
    $stmt->execute([$title, $description]);
}

$quizzes = $pdo->query("SELECT * FROM cuestionarios")->fetchAll();
?>

<!DOCTYPE html>
<html>
<body>
<h1>Panel de Administración</h1>
<form method="POST">
    <input type="text" name="title" placeholder="Título" required>
    <textarea name="description" placeholder="Descripción" required></textarea>
    <button type="submit">Crear Cuestionario</button>
</form>

<h2>Cuestionarios Disponibles</h2>
<ul>
    <?php foreach ($quizzes as $quiz) {
        echo "<li>{$quiz['title']} - <a href='quiz.php?id={$quiz['quiz_id']}'>Tomar</a></li>";
    } ?>
</ul>
</body>
</html>
