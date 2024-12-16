<table>
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>PV</th>
        <th>PVMax</th>
        <th>Force</th>
        <th>Dé</th>
        <th>Chance</th>
        <th>XP</th>
        <th>Argent</th>
        <th>Avatar</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($characters as $char): ?>
        <tr>
            <td><?= $char->getId() ?></td>
            <td><?= htmlspecialchars($char->getNom()) ?></td>
            <td><?= $char->PV ?></td>
            <td><?= $char->PVMax ?></td>
            <td><?= $char->force ?></td>
            <td><?= $char->facesDe ?></td>
            <td><?= $char->chance ?></td>
            <td><?= $char->XP ?></td>
            <td><?= $char->money ?></td>
            <td><?= $char->avatar ?></td>
            <td>
                <a href="/personnage/<?= $char->getId() ?>">Modifier</a>
                <a href="/personnage/<?= $char->getId() ?>/delete" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<a href="/personnage/ajouter">Ajouter un personnage</a>