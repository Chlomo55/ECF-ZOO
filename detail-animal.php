<!-- Je récupere l'id avec get de l'animal choisi pour lui détailler ses propres caractères -->

<?php 
include_once('pdo.php');
$id_animal = 1;
// $_GET['id']
$infosAnimal = $pdo->prepare("SELECT * FROM animaux WHERE id = ?");
$infosAnimal->execute([$id_animal]);
$infos = $infosAnimal->fetchAll();
foreach($infos as $info){ ?>
<div>
    <h5><?=$info['prenom']?></h5>
    <p><?=$info['prenom']?> est un <?=$info['race']?> qui vit dans <?=$info['habitat']?></p>
    <p>Il se nourrit de <?=$info['nourriture']?> et son grammage est de <?=$info['grammage'].'g'?></p>
    <p>Actuellement il est en <?=$info['etat']?></p>
    <p>Sa dernière visite du vétérinaire remonte à <?=$info['date-passage']?></p>
</div>

<?php 
}
?>