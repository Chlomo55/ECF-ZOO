<div>
<h3>Rien n'est mieux que le resenti des visiteurs</h3>
<?php 
require_once('pdo.php');

$affiche = $pdo->prepare("SELECT * FROM avis WHERE validate = 1");
$affiche->execute();
$AffichAvis = $affiche->fetchAll();

foreach($AffichAvis as $Avis){ ?>
<div>
    <h4><?= $Avis['nom']?></h4>
    <p><?= $Avis['avis']?></p>
</div>
<?php
}
?>
</div>
