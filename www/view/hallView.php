<?php if ($bagarres) { ?>
    <table class="mb-4" style="width: 100%">
        <tr>
            <th>Combattant 1</th>
            <th>Combattant 2</th>
            <th>Tour</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($bagarres as $bagarre): ?>
            <tr>
                <td><?= htmlspecialchars($bagarre->getPersonnageA()->getNom()) ?></td>
                <td><?= htmlspecialchars($bagarre->getPersonnageB()->getNom()) ?></td>
                <td><?= $bagarre->getTurn() ?></td>
                <td>
                    <a href="/bagarre?a=<?= $bagarre->getPersonnageA()->getId() ?>&b=<?= $bagarre->getPersonnageB()->getId() ?>">Reprendre</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php } else { ?>
    <h3>Il n'y a aucune bagarre en cours.</h3>
<?php } ?>

<div class="container" style="align-items: center; justify-content: space-around;">
    <select name="personnageASelect" id="personnageASelect">
        <option value="">Choisir un personnage</option>
        <?php foreach ($personnages as $p): ?>
            <option value="<?= $p->getId() ?>"><?= $p->getNom() ?></option>
        <?php endforeach; ?>
    </select>

    <p style="color: black;">VS</p>

    <select name="personnageBSelect" id="personnageBSelect">
        <option value="">Choisir un personnage</option>
        <?php foreach ($personnages as $p): ?>
            <option value="<?= $p->getId() ?>"><?= $p->getNom() ?></option>
        <?php endforeach; ?>
    </select>

    <a class="a-btn" id="triggerBagarre">BASTON !</a>
</div>