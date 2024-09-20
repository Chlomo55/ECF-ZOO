<?php 
include_once('pdo.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $pass = $_POST['pass'];

if($username === "admin@zooarcadia.fr" AND $pass === "Admin1234"){
 header('location: admin.php');
}}
?>
<div>
    <div>
        <h4>Connexion</h4>
    </div>
    <form method="post">
    <div>
        <input type="text" name="username" id="username" placeholder="Adresse mail">
    </div>
    <div>
        <input type="password" name="pass" id="pass" placeholder="Votre mot de passe">
    </div>
    <div>
        <input type="submit" value="Se connecter">
    </div>
    </form>
</div>