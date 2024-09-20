
<?php 
session_start();
include_once('header.php'); 
?>

<section>
    <h2>Bienvenue au Zoo d'Arcadia</h2>
    <p>Découvrez les différents habitats, animaux et services de notre zoo écologique situé près de la forêt de Brocéliande.</p>
    <img src="zoo.jpeg" alt="Photo du zoo" style="width:100%; max-height:400px; object-fit:cover;">
</section>

<section>
    <h3>Nos habitats</h3>
    <?php include_once('habitat.php'); ?>
</section>

<section>
    <h3>Nos services</h3>
    <?php include_once('services.php'); ?>
</section>

<section>
    <h3>Ce que nos visiteurs disent</h3>
    <?php include_once('avis.php'); ?>
</section>

<?php 
include_once('footer.php'); 
?>
