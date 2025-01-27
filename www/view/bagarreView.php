<!-- bagarreView.php -->
<div class="container">
    <?= $combattant1 ?>
    <?= $combattant2 ?>
</div>
<div class="container" style="justify-content: space-between;">
    <select name="moveASelect" id="moveASelect">
        <option value="attack">Attaquer</option>
        <option value="wait">Attendre</option>
        <option value="flee">Fuir</option>
    </select>

    <a class="a-btn" id="triggerNextTurn" href="">Tour suivant</a>

    <select name="moveBSelect" id="moveBSelect">
        <option value="attack">Attaquer</option>
        <option value="wait">Attendre</option>
        <option value="flee">Fuir</option>
    </select>
</div>
<pre id="battleTextContainer">
    
</pre>