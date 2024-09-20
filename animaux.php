<?php 
include_once('pdo.php');
$nom_habitat = $_GET['nom'];

$habitasList = $pdo->prepare(("SELECT * FROM animaux WHERE habitat = ?"));
$habitasList->execute([$nom_habitat]);
$liste = $habitasList->fetchAll();
foreach($liste as $animal_List){ ?>
<li><a href="detail-animal.php?id=<?= $animal_List['id']?>"><?= $animal_List['prenom']?></a></li>
<?php 
}
?>