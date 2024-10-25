<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Inserimento in MongoDB</title>
</head>
<body>
    <form action="insert.php" method="POST">
        <label for="name">Nome:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <button type="submit">Inserisci</button>
    </form>
</body>
</html>
