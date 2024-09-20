<h4>Modifier ou supprimer un service</h4>

<?php 
include_once('pdo.php');

// Requête pour récupérer les services
$servicesAdmin = $pdo->prepare("SELECT * FROM service");
$servicesAdmin->execute();
$services = $servicesAdmin->fetchAll();

// Traitement de la suppression (via AJAX)
if (isset($_POST['supprimer_service'])) {
    $supprimer = $pdo->prepare("DELETE FROM service WHERE id = ?");
    $supprimer->execute([$_POST['id']]);
    echo "Service supprimé avec succès.";
    exit();
}

// Traitement de la modification (via AJAX)
if (isset($_POST['modifier_service'])) {
    $modifier = $pdo->prepare("UPDATE service SET nom = ?, details = ? WHERE id = ?");
    $modifier->execute([$_POST['nom'], $_POST['details'], $_POST['id']]);
    echo "Service modifié avec succès.";
    exit();
}

// Traitement de la création d'un nouveau service (via AJAX)
if (isset($_POST['creer_service'])) {
    $creation = $pdo->prepare("INSERT INTO service (nom, details) VALUES (?, ?)");
    $creation->execute([$_POST['nouveau_nom'], $_POST['nouveau_details']]);
    echo "Nouveau service créé avec succès.";
    exit();
}
?>

<!-- Affichage de la liste des services -->
<div id="services-list">
    <?php foreach($services as $service) { ?>
    <table>
      <thead>
        <tr>
          <th scope="col">Nom</th>
          <th scope="col">Détails</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?= htmlspecialchars($service['nom']); ?></td>
          <td><?= htmlspecialchars($service['details']); ?></td>
          <td>
            <!-- Formulaire pour la suppression -->
            <form method="post" class="form-supprimer">
              <input type="hidden" name="id" value="<?= $service['id']; ?>">
              <button type="button" class="supprimer-btn" data-id="<?= $service['id']; ?>">Supprimer</button>
            </form>

            <!-- Bouton pour afficher le formulaire de modification -->
            <button class="modifier-btn" data-id="<?= $service['id']; ?>" 
                    data-nom="<?= htmlspecialchars($service['nom']); ?>" 
                    data-details="<?= htmlspecialchars($service['details']); ?>">
              Modifier
            </button>
          </td>
        </tr>
      </tbody>
    </table>   
    <?php } ?>
</div>

<!-- Formulaire de modification caché (utilisé par jQuery) -->
<div id="modifierForm" style="display:none;">
    <h4>Modifier un service</h4>
    <form id="formModifier" method="post">
        <input type="hidden" name="id" id="serviceId">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="serviceNom">
        <label for="details">Détails :</label>
        <textarea name="details" id="serviceDetails"></textarea>
        <input type="submit" value="Enregistrer">
        <button type="button" id="annulerModifier">Annuler</button>
    </form>
</div>

<!-- Formulaire de création d'un nouveau service -->
<div id="creationForm">
    <h4>Créer un nouveau service</h4>
    <form id="formCreation" method="post">
        <label for="nouveau_nom">Nom :</label>
        <input type="text" name="nouveau_nom" id="nouveauNom" required>
        <label for="nouveau_details">Détails :</label>
        <textarea name="nouveau_details" id="nouveauDetails" required></textarea>
        <input type="submit" value="Créer">
    </form>
</div>

<!-- jQuery pour gérer l'affichage du formulaire et les soumissions AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Afficher le formulaire de modification avec les données pré-remplies
    $('.modifier-btn').click(function() {
        var id = $(this).data('id');
        var nom = $(this).data('nom');
        var details = $(this).data('details');

        // Remplir les champs du formulaire
        $('#serviceId').val(id);
        $('#serviceNom').val(nom);
        $('#serviceDetails').val(details);

        // Afficher le formulaire
        $('#modifierForm').show();
    });

    // Annuler la modification
    $('#annulerModifier').click(function() {
        $('#modifierForm').hide();
    });

    // Soumettre le formulaire de modification via AJAX
    $('#formModifier').submit(function(e) {
        e.preventDefault(); // Empêche l'envoi classique du formulaire

        var formData = $(this).serialize(); // Sérialiser les données du formulaire

        $.ajax({
            url: '', // Le fichier PHP actuel
            type: 'POST',
            data: formData + '&modifier_service=true', // Ajout du flag pour la modification
            success: function(response) {
                alert('Modification réussie!');
                location.reload(); // Recharger la page pour voir les modifications
            },
            error: function() {
                alert('Une erreur est survenue.');
            }
        });
    });

    // Soumettre la suppression via AJAX
    $('.supprimer-btn').click(function() {
        var id = $(this).data('id');
        var confirmation = confirm('Voulez-vous vraiment supprimer ce service ?');
        
        if (confirmation) {
            $.ajax({
                url: '', // Le fichier PHP actuel
                type: 'POST',
                data: { id: id, supprimer_service: true }, // Envoyer l'ID pour la suppression
                success: function(response) {
                    alert('Service supprimé avec succès!');
                    location.reload(); // Recharger la page pour voir les modifications
                },
                error: function() {
                    alert('Une erreur est survenue.');
                }
            });
        }
    });

    // Soumettre le formulaire de création via AJAX
    $('#formCreation').submit(function(e) {
        e.preventDefault(); // Empêche l'envoi classique du formulaire

        var formData = $(this).serialize(); // Sérialiser les données du formulaire

        $.ajax({
            url: '', // Le fichier PHP actuel
            type: 'POST',
            data: formData + '&creer_service=true', // Ajout du flag pour la création
            success: function(response) {
                alert('Nouveau service créé avec succès!');
                location.reload(); // Recharger la page pour voir les nouveaux services
            },
            error: function() {
                alert('Une erreur est survenue lors de la création du service.');
            }
        });
    });
});
</script>
<?php include_once('admin-footer.php'); ?>