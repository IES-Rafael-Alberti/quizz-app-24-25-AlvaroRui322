<?php
session_start();
require 'config.php';

if (!isset($_GET['id'])) {
    die("Cuestionario no especificado.");
}

$quiz_id = $_GET['id'];
$questions = $pdo->prepare("SELECT * FROM preguntas WHERE quiz_id = ?");
$questions->execute([$quiz_id]);
$questions = $questions->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $score = 0;
    foreach ($questions as $question) {
        if (isset($_POST[$question['question_id']]) && $_POST[$question['question_id']] == $question['correct_option']) {
            $score++;
        }
    }

    $stmt = $pdo->prepare("INSERT INTO resultados (user_id, quiz_id, score) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $quiz_id, $score]);

    echo "<h2>Tu puntuación: $score</h2>";
}
?>

<!DOCTYPE html>
<html>
<body>
<h1>Cuestionario</h1>
<form method="POST">
    <?php foreach ($questions as $question) { ?>
        <p><?php echo $question['question_text']; ?></p>
        <input type="radio" name="<?php echo $question['question_id']; ?>" value="A"> <?php echo $question['option_a']; ?><br>
        <input type="radio" name="<?php echo $question['question_id']; ?>" value="B"> <?php echo $question['option_b']; ?><br>
        <input type="radio" name="<?php echo $question['question_id']; ?>" value="C"> <?php echo $question['option_c']; ?><br>
        <input type="radio" name="<?php echo $question['question_id']; ?>" value="D"> <?php echo $question['option_d']; ?><br>
    <?php } ?>
    <button type="submit">Enviar</button>
</form>
</body>
</html>

