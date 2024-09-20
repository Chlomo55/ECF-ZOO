
<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'employe') {
    header('Location: login.php');
    exit();
}

include_once('header.php');
include_once('pdo.php');

// Validation of visitor review
if (isset($_POST['valider_avis'])) {
    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    if ($id) {
        try {
            $validerAvis = $pdo->prepare("UPDATE avis SET valide = 1 WHERE id = ?");
            $validerAvis->execute([$id]);
            echo "<p class='success'>Avis validé avec succès.</p>";
        } catch (PDOException $e) {
            echo "<p class='error'>Erreur lors de la validation de l'avis : " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p class='error'>ID invalide.</p>";
    }
}

// Adding animal feeding record
if (isset($_POST['ajouter_alimentation'])) {
    $animal_id = filter_var($_POST['animal_id'], FILTER_VALIDATE_INT);
    $nourriture = htmlspecialchars($_POST['nourriture']);
    $grammage = filter_var($_POST['grammage'], FILTER_VALIDATE_INT);
    $date = htmlspecialchars($_POST['date']);
    $heure = htmlspecialchars($_POST['heure']);

    if ($animal_id && $nourriture && $grammage && $date && $heure) {
        try {
            $ajouterAlimentation = $pdo->prepare("INSERT INTO alimentation (animal_id, nourriture, grammage, date, heure) VALUES (?, ?, ?, ?, ?)");
            $ajouterAlimentation->execute([$animal_id, $nourriture, $grammage, $date, $heure]);
            echo "<p class='success'>Alimentation ajoutée avec succès.</p>";
        } catch (PDOException $e) {
            echo "<p class='error'>Erreur lors de l'ajout de l'alimentation : " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p class='error'>Données invalides.</p>";
    }
}
?>

<h4>Espace Employé</h4>
<form method="POST">
    <h5>Validation des avis</h5>
    <label for="id">ID de l'avis</label>
    <input type="number" name="id" required>
    <button type="submit" name="valider_avis">Valider Avis</button>
</form>

<form method="POST">
    <h5>Ajouter l'alimentation des animaux</h5>
    <label for="animal_id">ID de l'animal</label>
    <input type="number" name="animal_id" required>
    <label for="nourriture">Nourriture</label>
    <input type="text" name="nourriture" required>
    <label for="grammage">Grammage</label>
    <input type="number" name="grammage" required>
    <label for="date">Date</label>
    <input type="date" name="date" required>
    <label for="heure">Heure</label>
    <input type="time" name="heure" required>
    <button type="submit" name="ajouter_alimentation">Ajouter Alimentation</button>
</form>

<?php include_once('footer.php'); ?>
