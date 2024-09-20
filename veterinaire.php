
<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'veterinaire') {
    header('Location: login.php');
    exit();
}

include_once('header.php');
include_once('pdo.php');

// Adding animal health report
if (isset($_POST['ajouter_rapport'])) {
    $animal_id = filter_var($_POST['animal_id'], FILTER_VALIDATE_INT);
    $etat = htmlspecialchars($_POST['etat']);
    $nourriture = htmlspecialchars($_POST['nourriture']);
    $grammage = filter_var($_POST['grammage'], FILTER_VALIDATE_INT);
    $date = htmlspecialchars($_POST['date']);
    $details = htmlspecialchars($_POST['details']);

    if ($animal_id && $etat && $nourriture && $grammage && $date) {
        try {
            $ajouterRapport = $pdo->prepare("INSERT INTO comptes_rendus (animal_id, etat, nourriture, grammage, date, details) VALUES (?, ?, ?, ?, ?, ?)");
            $ajouterRapport->execute([$animal_id, $etat, $nourriture, $grammage, $date, $details]);
            echo "<p class='success'>Compte rendu ajouté avec succès.</p>";
        } catch (PDOException $e) {
            echo "<p class='error'>Erreur lors de l'ajout du compte rendu : " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p class='error'>Données invalides.</p>";
    }
}
?>

<h4>Espace Vétérinaire</h4>
<form method="POST">
    <h5>Ajouter un compte rendu sur la santé d'un animal</h5>
    <label for="animal_id">ID de l'animal</label>
    <input type="number" name="animal_id" required>
    <label for="etat">État de l'animal</label>
    <input type="text" name="etat" required>
    <label for="nourriture">Nourriture donnée</label>
    <input type="text" name="nourriture" required>
    <label for="grammage">Grammage</label>
    <input type="number" name="grammage" required>
    <label for="date">Date</label>
    <input type="date" name="date" required>
    <label for="details">Détails supplémentaires (optionnel)</label>
    <textarea name="details"></textarea>
    <button type="submit" name="ajouter_rapport">Ajouter Rapport</button>
</form>

<?php include_once('footer.php'); ?>
