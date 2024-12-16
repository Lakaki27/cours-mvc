<!-- cardView.php -->
<div class="player-card overlay" style="background: linear-gradient(to bottom, transparent 50%, #ea0024), url('<?= htmlspecialchars($personnage->avatar) ?>'), url('/img/default.png');">
    <div class="player-name">
        <h2><?= htmlspecialchars($personnage->getNom()) ?></h2>
    </div>
    <div class="classe">
        <p><?= htmlspecialchars($personnage->getClasse()) ?></p>
    </div>
    <div class="infos">
        <p>STR[<?= htmlspecialchars($personnage->force) ?>] LUCK[<?= htmlspecialchars($personnage->chance) ?>] XP[<?= htmlspecialchars($personnage->XP) ?>]</p>
    </div>
    <div class="pv">
        <p><?= htmlspecialchars($personnage->PV) ?>PV</p>
    </div>
</div>