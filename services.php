<?php 
require_once('pdo.php');
include_once('header.php');
?>

<div>
    <h3>Nos services</h3>
    <h4>Le Zoo Arcadia vous propose plusieurs services</h4>
    
    <?php 
    $AfficheServices = $pdo->prepare('SELECT * FROM `service`');
    $AfficheServices->execute();
    $Services = $AfficheServices->fetchAll();
    foreach($Services as $Service){?>
    <li><?=$Service['nom'] ?></li>
    <?php }?>
</div>