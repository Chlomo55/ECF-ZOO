<section>
        <h2>Nos habitats</h2>
        <p>Découvrez nos animaux issus des habitats suivants :</p>
        <ul>
            <?php 
            require_once('pdo.php');
            $nomsHabitat = $pdo->prepare('SELECT * FROM habitat');
            $nomsHabitat->execute();
            $habitats = $nomsHabitat->fetchAll();
            foreach($habitats as $habitat){ ?>
               <li><a href="animaux.php?nom=<?= $habitat['nom']?>"><?= $habitat['nom']?></a></li> 
            <?php }
            ?>
            
        </ul>
    </section>