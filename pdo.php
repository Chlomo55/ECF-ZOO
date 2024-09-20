<?php 
$user = 'root';
$pass = '';

try{
    $pdo = new PDO('mysql:host=localhost;dbname=zoo', $user, $pass);

} catch(Exception $e){ 
    ?>

<div>
    <h3>Une érreur de connexion à la base de donnée est survenue</h3>
    <h4>Veuillez ressayer ultérieurement</h4>
</div>
<?php }
?>