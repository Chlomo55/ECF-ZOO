<?php
// Inclusion du fichier pour la connexion à la base de données
include_once('pdo.php');

// Traitement de la suppression d'un compte
if (isset($_POST['supprimer_compte'])) {
    $id = $_POST['id'];
    $supprimer = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $supprimer->execute([$id]);
    echo "Le compte a été supprimé avec succès.";
}

// Traitement de la modification d'un compte
if (isset($_POST['modifier_compte'])) {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    // Si un nouveau mot de passe est fourni, on le hache
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $modifier = $pdo->prepare("UPDATE users SET username = ?, pass = ?, role = ? WHERE id = ?");
        $modifier->execute([$username, $hashedPassword, $role, $id]);
    } else {
        // Si aucun nouveau mot de passe n'est fourni, on ne modifie que le username et le rôle
        $modifier = $pdo->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
        $modifier->execute([$username, $role, $id]);
    }

    echo "Le compte a été modifié avec succès.";
}

// Récupération de tous les comptes d'utilisateurs
$usersAdmin = $pdo->prepare("SELECT * FROM users WHERE role = '1' OR role = '0'");
$usersAdmin->execute();
$users = $usersAdmin->fetchAll();
?>

<h4>Gestion des comptes d'utilisateurs</h4>

<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>Email (Nom d'utilisateur)</th>
            <th>Mot de passe</th>
            <th>Rôle</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user) { ?>
        <tr>
            <form method="POST">
                <input type="hidden" name="id" value="<?= $user['id']; ?>">

                <td>
                    <input type="email" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>
                </td>

                <td>
                    <!-- Mot de passe crypté affiché, et possibilité de changer -->
                    <input type="password" name="password" placeholder="Laisser vide pour conserver le même mot de passe">
                </td>

                <td>
                    <select name="role" required>
                        <option value="0" <?= $user['role'] == 0 ? 'selected' : ''; ?>>Employé</option>
                        <option value="1" <?= $user['role'] == 1 ? 'selected' : ''; ?>>Vétérinaire</option>
                    </select>
                </td>

                <td>
                    <!-- Boutons pour modifier ou supprimer -->
                    <input type="submit" name="modifier_compte" value="Modifier">
                    <input type="submit" name="supprimer_compte" value="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce compte ?');">
                </td>
            </form>
        </tr>
        <?php } ?>
    </tbody>
</table>
<?php include_once('admin-footer.php');?>