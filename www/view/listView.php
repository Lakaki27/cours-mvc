<?php if ($characters) { ?>
    <table class="mb-4">
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
                <td><?= $char->getPV() ?></td>
                <td><?= $char->getPVMax() ?></td>
                <td><?= $char->getForce() ?></td>
                <td><?= $char->getFacesDe() ?></td>
                <td><?= $char->getChance() ?></td>
                <td><?= $char->getXP() ?></td>
                <td><?= $char->getMoney() ?></td>
                <td><?= $char->getAvatar() ?></td>
                <td>
                    <a href="/personnage/<?= $char->getId() ?>">Modifier</a>
                    <a class="deleteBtn" id="delete_<?= $char->getId() ?>">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php } else { ?>
    <h3>Il n'existe encore aucun combattant ! Créez votre premier personnage ci-dessous...</h3>
<?php } ?>
<a href="/personnage/ajouter" class="my-4">Ajouter un personnage</a>