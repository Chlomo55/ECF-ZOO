
<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

include_once('mongo_connect.php'); // Assuming a separate file for MongoDB connection

// Display statistics for animal consultations
try {
    $mongo = new MongoDB\Client("mongodb://localhost:27017");
    $db = $mongo->zoo_stats;
    $consultations = $db->consultations->find();

    echo "<h4>Statistiques de consultation des animaux</h4>";
    foreach ($consultations as $consultation) {
        echo "<p>Animal: " . $consultation['animal_name'] . " - Consultations: " . $consultation['count'] . "</p>";
    }
} catch (Exception $e) {
    echo "Erreur lors de la récupération des statistiques : " . $e->getMessage();
}
?>
