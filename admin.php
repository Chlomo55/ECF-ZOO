
<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

include_once('header.php');
?>

<div class="admin-section">
    <h4>Tableau de bord Administrateur</h4>
    <ul>
        <li><a href="admin-comptes.php">Gérer les comptes employés et vétérinaires</a></li>
        <li><a href="admin-habitat.php">Gérer les habitats</a></li>
        <li><a href="admin-animaux.php">Gérer les animaux</a></li>
        <li><a href="admin-services.php">Gérer les services</a></li>
        <li><a href="admin-horaires.php">Gérer les horaires</a></li>
        <li><a href="stats.php">Voir les statistiques des consultations</a></li>
    </ul>
</div>

<?php include_once('footer.php'); ?>
