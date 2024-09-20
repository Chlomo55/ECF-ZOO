<h4>Modifier ou supprimer un habitat</h4>

<?php 
include_once('pdo.php');

// Récupération des habitats
$habitatsAdmin = $pdo->prepare("SELECT * FROM habitat");
$habitatsAdmin->execute();
$afficher = $habitatsAdmin->fetchAll();

// Traitement de la suppression (via AJAX)
if (isset($_POST['supprimer_habitat'])) {
    $supprimer = $pdo->prepare("DELETE FROM habitat WHERE id = ?");
    $supprimer->execute([$_POST['id']]);
    echo "Habitat supprimé avec succès.";
    exit(); // Arrêter l'exécution pour ne pas renvoyer le HTML complet
}

// Traitement de la modification (via AJAX)
if (isset($_POST['modifier_habitat'])) {
    $modifier = $pdo->prepare("UPDATE habitat SET nom = ?, description = ? WHERE id = ?");
    $modifier->execute([$_POST['nom'], $_POST['description'], $_POST['id']]);
    echo "Habitat modifié avec succès.";
    exit();
}

?>

<!-- Affichage de la liste des habitats -->
<div id="habitats-list">
    <?php foreach($afficher as $afficherHabitat) { ?>
    <table>
      <thead>
        <tr>
          <th scope="col">Nom</th>
          <th scope="col">Description</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?= htmlspecialchars($afficherHabitat['nom']); ?></td>
          <td><?= htmlspecialchars($afficherHabitat['description']); ?></td>
          <td>
            <!-- Formulaire pour la suppression -->
            <form method="post" class="form-supprimer">
              <input type="hidden" name="id" value="<?= $afficherHabitat['id']; ?>">
              <button type="button" class="supprimer-btn" data-id="<?= $afficherHabitat['id']; ?>">Supprimer</button>
            </form>

            <!-- Bouton pour afficher le formulaire de modification -->
            <button class="modifier-btn" data-id="<?= $afficherHabitat['id']; ?>" data-nom="<?= htmlspecialchars($afficherHabitat['nom']); ?>" data-description="<?= htmlspecialchars($afficherHabitat['description']); ?>">Modifier</button>
          </td>
        </tr>
      </tbody>
    </table>   
    <?php } ?>
</div>

<!-- Formulaire de modification caché (utilisé par jQuery) -->
<div id="modifierForm" style="display:none;">
    <h4>Modifier un habitat</h4>
    <form id="formModifier" method="post">
        <input type="hidden" name="id" id="habitatId">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="habitatNom">
        <label for="description">Description :</label>
        <input type="text" name="description" id="habitatDescription">
        <input type="submit" value="Enregistrer">
        <button type="button" id="annulerModifier">Annuler</button>
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
        var description = $(this).data('description');

        // Remplir les champs du formulaire
        $('#habitatId').val(id);
        $('#habitatNom').val(nom);
        $('#habitatDescription').val(description);

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
            data: formData + '&modifier_habitat=true', // Ajout du flag pour la modification
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
        var confirmation = confirm('Voulez-vous vraiment supprimer cet habitat ?');
        
        if (confirmation) {
            $.ajax({
                url: '', // Le fichier PHP actuel
                type: 'POST',
                data: { id: id, supprimer_habitat: true }, // Envoyer l'ID pour la suppression
                success: function(response) {
                    alert('Habitat supprimé avec succès!');
                    location.reload(); // Recharger la page pour voir les modifications
                },
                error: function() {
                    alert('Une erreur est survenue.');
                }
            });
        }
    });
});
</script>
<?php include_once('admin-footer.php'); ?>