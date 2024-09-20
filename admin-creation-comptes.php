<?php
// Inclusion du fichier pour la connexion à la base de données
include_once('pdo.php');

// Inclusion de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require_once 'PHPMailer-master/PHPMailer-master/src/PHPMailer.php';
require_once 'PHPMailer-master/PHPMailer-master/src/SMTP.php';

// Fonction pour envoyer un email à l'utilisateur sans le mot de passe
function envoyerEmail($email, $role) {
    // Déterminer le texte du rôle (employé ou vétérinaire)
    $roleText = ($role == '1') ? 'vétérinaire' : 'employé';

    // Configuration de PHPMailer
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'chlomo.freoua@gmail.com'; // Remplacez cette ligne par votre adresse email
    $mail->Password = 'lysvjszruhsufdxh';   // Remplacez par votre mot de passe ou mot de passe d'application
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Configuration de l'email
    $mail->setFrom('admin@zoo.com', 'Admin Zoo'); // Remplacer par votre adresse email et votre nom
    $mail->addAddress($email); // Envoyer à l'utilisateur
    $mail->Subject = 'Création de votre compte ' . $roleText;
    $mail->isHTML(true);

    // Contenu de l'email
    $mail->Body = "
    <div style='font-family: Arial, sans-serif; font-size: 16px; line-height: 1.5; text-align: center;'>
        <p>Bonjour,</p>
        <p>Votre compte de $roleText a été créé avec succès.</p>
        <p>Votre nom d'utilisateur est : <strong>$email</strong></p>
        <p>Veuillez contacter l'administrateur pour obtenir votre mot de passe.</p>
        <p>Merci de faire partie de notre équipe!</p>
    </div>
    ";

    // Envoi de l'email
    if ($mail->send()) {
        return true;
    } else {
        return $mail->ErrorInfo;
    }
}

// Traitement de la création de compte
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role']; // 0 = Employé, 1 = Vétérinaire

    // Hachage du mot de passe pour la sécurité
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Vérifier si l'utilisateur existe déjà
    $verif = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $verif->execute([$username]);

    if ($verif->rowCount() > 0) {
        echo "Un compte avec cet email existe déjà.";
    } else {
        // Insertion de l'utilisateur dans la base de données
        $insertion = $pdo->prepare("INSERT INTO users (username, pass, role) VALUES (?, ?, ?)");
        $insertion->execute([$username, $hashedPassword, $role]);

        // Envoi de l'email à l'utilisateur
        $result = envoyerEmail($username, $role);

        if ($result === true) {
            echo "Compte créé avec succès. Un email a été envoyé à l'utilisateur.";
        } else {
            echo "Une erreur est survenue lors de l'envoi de l'email : " . $result;
        }
    }
}
?>


<h4>Créer un compte pour un employé ou un vétérinaire</h4>
<form method="POST">
    <label for="username">Email (Nom d'utilisateur) :</label>
    <input type="email" name="username" required><br>

    <label for="password">Mot de passe :</label>
    <input type="password" name="password" required><br>

    <label for="role">Rôle :</label>
    <select name="role" required>
        <option value="0">Employé</option>
        <option value="1">Vétérinaire</option>
    </select><br>

    <input type="submit" value="Créer le compte">
</form>

<?php include_once('admin-footer.php'); ?>
