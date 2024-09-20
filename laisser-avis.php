<?php 
require_once('pdo.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pseudo = $_POST['pseudo'];
    $avis = $_POST['avis'];

    $inserer_avis = $pdo->prepare("INSERT INTO avis (nom, avis) VALUES (?, ?)");
    $inserer_avis->execute([$pseudo, $avis]);
}
?>

<form method="post">
    <div>
        <label for="pseudo">Votre Pseudo:</label>
        <br>
        <input type="text" name="pseudo" id="pseudo">
    </div>
    <div>
        <textarea name="avis" id="avis" placeholder="Laisser un commentaire..."></textarea>
    </div>
    <div>
        <input type="submit" value="Envoyer">
    </div>
</form>

