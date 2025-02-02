<!-- cardView.php -->
<div id="card<?= $letter ?>" class="player-card overlay mb-2" style="background: linear-gradient(to bottom, transparent 50%, #ea0024), url('<?= htmlspecialchars($personnage->getAvatar()) ?>'), url('/img/default.png');">
    <div class="player-name">
        <h2><?= htmlspecialchars($personnage->getNom()) ?></h2>
    </div>
    <div class="classe">
        <p><?= htmlspecialchars($personnage->getClasse()) ?></p>
    </div>
    <div class="infos">
        <p>STR[<span id="labelForce<?= $letter ?>"><?= htmlspecialchars($personnage->getForce()) ?></span>] LUCK[<?= htmlspecialchars($personnage->getChance()) ?>] LVL[<?= htmlspecialchars($personnage->getLevel()) ?>]</p>
    </div>
    <div class="pv">
        <p><span id="labelPV<?= strtolower($letter) ?>"><?= htmlspecialchars($personnage->getPV()) ?></span> PV</p>
    </div>
</div>