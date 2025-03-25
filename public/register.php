<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h2>Inscription</h2>
    <form action="register.php" method="POST">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required><br>
        <input type="email" name="email" placeholder="Adresse e-mail" required><br>
        <input type="password" name="password" placeholder="Mot de passe" required><br>
        <input type="password" name="confirm_password" placeholder="Confirmer mot de passe" required><br>
        <button type="submit" name="register">S'inscrire</button>
    </form>
</body>
</html>
<?php
include "../includes/config.php"; // Connexion à la DB

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $username = htmlspecialchars(trim($_POST["username"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Vérification des mots de passe
    if ($password !== $confirm_password) {
        die("⚠️ Les mots de passe ne correspondent pas !");
    }

    // Vérification de la longueur du mot de passe
    if (strlen($password) < 6) {
        die("⚠️ Le mot de passe doit contenir au moins 6 caractères.");
    }

    // Hashage du mot de passe
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Vérification de l'unicité de l'email
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() > 0) {
        die("⚠️ Cet e-mail est déjà utilisé.");
    }

    // Insertion dans la base de données
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $email, $hashed_password])) {
        echo "✅ Inscription réussie ! <a href='login.php'>Connectez-vous</a>";
    } else {
        echo "❌ Erreur lors de l'inscription.";
    }
}
?>
