<h4>Gestion des horaires</h4>

<?php
include_once('pdo.php');

// Récupération des horaires
$horairesAdmin = $pdo->prepare("SELECT * FROM horaires ORDER BY FIELD(type, 'normal', 'vacances'), FIELD(jour, 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche')");
$horairesAdmin->execute();
$horaires = $horairesAdmin->fetchAll();

// Traitement de la suppression (via AJAX)
if (isset($_POST['supprimer_horaire'])) {
    $supprimer = $pdo->prepare("DELETE FROM horaires WHERE id = ?");
    $supprimer->execute([$_POST['id']]);
    echo "Horaire supprimé avec succès.";
    exit();
}

// Traitement de la modification (via AJAX)
if (isset($_POST['modifier_horaire'])) {
    // Vérifier si un horaire existe déjà pour ce jour et ce type
    $verif = $pdo->prepare("SELECT * FROM horaires WHERE jour = ? AND type = ? AND id != ?");
    $verif->execute([$_POST['jour'], $_POST['type'], $_POST['id']]);
    if ($verif->rowCount() > 0) {
        echo "Un horaire existe déjà pour ce jour et ce type.";
    } else {
        $modifier = $pdo->prepare("UPDATE horaires SET jour = ?, ouverture = ?, fermeture = ?, type = ? WHERE id = ?");
        $modifier->execute([$_POST['jour'], $_POST['ouverture'], $_POST['fermeture'], $_POST['type'], $_POST['id']]);
        echo "Horaire modifié avec succès.";
    }
    exit();
}

// Traitement de la création d'un nouvel horaire (via AJAX)
if (isset($_POST['creer_horaire'])) {
    // Vérifier si un horaire existe déjà pour ce jour et ce type
    $verif = $pdo->prepare("SELECT * FROM horaires WHERE jour = ? AND type = ?");
    $verif->execute([$_POST['jour'], $_POST['type']]);
    if ($verif->rowCount() > 0) {
        echo "Un horaire existe déjà pour ce jour et ce type.";
    } else {
        $creation = $pdo->prepare("INSERT INTO horaires (jour, ouverture, fermeture, type) VALUES (?, ?, ?, ?)");
        $creation->execute([$_POST['jour'], $_POST['ouverture'], $_POST['fermeture'], $_POST['type']]);
        echo "Nouvel horaire créé avec succès.";
    }
    exit();
}
?>

<!-- Affichage des horaires -->
<div id="horaires-list">
    <?php foreach($horaires as $horaire) { ?>
    <table>
      <thead>
        <tr>
          <th scope="col">Jour</th>
          <th scope="col">Ouverture</th>
          <th scope="col">Fermeture</th>
          <th scope="col">Type</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?= htmlspecialchars($horaire['jour']); ?></td>
          <td><?= htmlspecialchars($horaire['ouverture']); ?></td>
          <td><?= htmlspecialchars($horaire['fermeture']); ?></td>
          <td><?= htmlspecialchars($horaire['type'] == 'normal' ? 'Normal' : 'Vacances'); ?></td>
          <td>
            <!-- Formulaire pour la suppression -->
            <form method="post" class="form-supprimer">
              <input type="hidden" name="id" value="<?= $horaire['id']; ?>">
              <button type="button" class="supprimer-btn" data-id="<?= $horaire['id']; ?>">Supprimer</button>
            </form>

            <!-- Bouton pour afficher le formulaire de modification -->
            <button class="modifier-btn" data-id="<?= $horaire['id']; ?>" 
                    data-jour="<?= htmlspecialchars($horaire['jour']); ?>" 
                    data-ouverture="<?= $horaire['ouverture']; ?>" 
                    data-fermeture="<?= $horaire['fermeture']; ?>" 
                    data-type="<?= $horaire['type']; ?>">
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
    <h4>Modifier un horaire</h4>
    <form id="formModifier" method="post">
        <input type="hidden" name="id" id="horaireId">
        <label for="jour">Jour :</label>
        <select name="jour" id="horaireJour" required>
            <option value="Lundi">Lundi</option>
            <option value="Mardi">Mardi</option>
            <option value="Mercredi">Mercredi</option>
            <option value="Jeudi">Jeudi</option>
            <option value="Vendredi">Vendredi</option>
            <option value="Samedi">Samedi</option>
            <option value="Dimanche">Dimanche</option>
        </select>
        <label for="ouverture">Ouverture :</label>
        <input type="time" name="ouverture" id="horaireOuverture" required>
        <label for="fermeture">Fermeture :</label>
        <input type="time" name="fermeture" id="horaireFermeture" required>
        <label for="type">Type :</label>
        <select name="type" id="horaireType">
            <option value="normal">Normal</option>
            <option value="vacances">Vacances</option>
        </select>
        <input type="submit" value="Enregistrer">
        <button type="button" id="annulerModifier">Annuler</button>
    </form>
</div>

<!-- Formulaire de création d'un nouvel horaire -->
<div id="creationForm">
    <h4>Créer un nouvel horaire</h4>
    <form id="formCreation" method="post">
        <label for="jour">Jour :</label>
        <select name="jour" id="nouveauJour" required>
            <option value="Lundi">Lundi</option>
            <option value="Mardi">Mardi</option>
            <option value="Mercredi">Mercredi</option>
            <option value="Jeudi">Jeudi</option>
            <option value="Vendredi">Vendredi</option>
            <option value="Samedi">Samedi</option>
            <option value="Dimanche">Dimanche</option>
        </select>
        <label for="ouverture">Ouverture :</label>
        <input type="time" name="ouverture" id="nouveauOuverture" required>
        <label for="fermeture">Fermeture :</label>
        <input type="time" name="fermeture" id="nouveauFermeture" required>
        <label for="type">Type :</label>
        <select name="type" id="nouveauType">
            <option value="normal">Normal</option>
            <option value="vacances">Vacances</option>
        </select>
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
        var jour = $(this).data('jour');
        var ouverture = $(this).data('ouverture');
        var fermeture = $(this).data('fermeture');
        var type = $(this).data('type');

        // Remplir les champs du formulaire
        $('#horaireId').val(id);
        $('#horaireJour').val(jour);
        $('#horaireOuverture').val(ouverture);
        $('#horaireFermeture').val(fermeture);
        $('#horaireType').val(type);

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
            data: formData + '&modifier_horaire=true', // Ajout du flag pour la modification
            success: function(response) {
                alert(response); // Afficher le message de succès ou d'erreur
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
        var confirmation = confirm('Voulez-vous vraiment supprimer cet horaire ?');
        
        if (confirmation) {
            $.ajax({
                url: '', // Le fichier PHP actuel
                type: 'POST',
                data: { id: id, supprimer_horaire: true }, // Envoyer l'ID pour la suppression
                success: function(response) {
                    alert('Horaire supprimé avec succès!');
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
            data: formData + '&creer_horaire=true', // Ajout du flag pour la création
            success: function(response) {
                alert(response); // Afficher le message de succès ou d'erreur
                location.reload(); // Recharger la page pour voir les nouveaux horaires
            },
            error: function() {
                alert('Une erreur est survenue lors de la création de l\'horaire.');
            }
        });
    });
});
</script>
