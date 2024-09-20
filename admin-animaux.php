
<?php
// Adding session check for security
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

include_once('pdo.php');

// Requête avec jointure pour récupérer les animaux et les habitats associés
try {
    $animauxAdmin = $pdo->prepare("
        SELECT animaux.*, habitat.nom AS habitat_nom 
        FROM animaux 
        JOIN habitat ON animaux.habitat_id = habitat.id
    ");
    $animauxAdmin->execute();
    $animaux = $animauxAdmin->fetchAll();
} catch (PDOException $e) {
    echo "Erreur lors de la récupération des animaux : " . $e->getMessage();
}

// Traitement de la suppression (via AJAX)
if (isset($_POST['supprimer_animal'])) {
    // Validate the ID
    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    if ($id) {
        try {
            $supprimer = $pdo->prepare("DELETE FROM animaux WHERE id = ?");
            $supprimer->execute([$id]);
            echo "Animal supprimé avec succès.";
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression : " . $e->getMessage();
        }
    } else {
        echo "ID invalide.";
    }
    exit();
}

// Traitement de la modification (via AJAX)
if (isset($_POST['modifier_animal'])) {
    // Validate inputs
    $prenom = htmlspecialchars($_POST['prenom']);
    $race = htmlspecialchars($_POST['race']);
    $habitat_id = filter_var($_POST['habitat_id'], FILTER_VALIDATE_INT);
    $etat = htmlspecialchars($_POST['etat']);
    $nourriture = htmlspecialchars($_POST['nourriture']);
    $grammage = filter_var($_POST['grammage'], FILTER_VALIDATE_INT);
    $date_passage = htmlspecialchars($_POST['date-passage']);
    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);

    if ($prenom && $race && $habitat_id && $etat && $nourriture && $grammage && $date_passage && $id) {
        try {
            $modifier = $pdo->prepare("UPDATE animaux SET prenom = ?, race = ?, habitat_id = ?, etat = ?, nourriture = ?, grammage = ?, `date-passage` = ? WHERE id = ?");
            $modifier->execute([$prenom, $race, $habitat_id, $etat, $nourriture, $grammage, $date_passage, $id]);
            echo "Animal modifié avec succès.";
        } catch (PDOException $e) {
            echo "Erreur lors de la modification : " . $e->getMessage();
        }
    } else {
        echo "Données invalides.";
    }
    exit();
}
?>
<h4>Modifier ou supprimer un animal</h4>
