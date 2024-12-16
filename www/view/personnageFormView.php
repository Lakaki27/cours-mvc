<h2 class="text-3xl font-extrabold text-center text-gray-700 mb-6">
    <?= isset($character) ? "Modifier le personnage" : "Créer un personnage" ?>
</h2>

<form method="POST" class="space-y-5">
    <!-- Nom -->
    <div>
        <label for="name" class="block text-gray-600 font-medium mb-1">Nom</label>
        <input type="text" id="name" name="name"
            value="<?= $character->getNom() ?? '' ?>"
            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
    </div>

    <!-- PV -->
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label for="PV" class="block text-gray-600 font-medium mb-1">PV</label>
            <input type="number" id="PV" name="PV"
                value="<?= $character->PV ?? '' ?>"
                class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
        </div>
        <div>
            <label for="PVMax" class="block text-gray-600 font-medium mb-1">PV Max</label>
            <input type="number" id="PVMax" name="PVMax"
                value="<?= $character->PVMax ?? '' ?>"
                class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
        </div>
    </div>

    <!-- Force -->
    <div>
        <label for="force" class="block text-gray-600 font-medium mb-1">Force</label>
        <input type="number" id="force" name="force"
            value="<?= $character->force ?? '' ?>"
            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
    </div>

    <!-- Argent -->
    <div>
        <label for="money" class="block text-gray-600 font-medium mb-1">Argent</label>
        <input type="number" id="money" name="money"
            value="<?= $character->money ?? '' ?>"
            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
    </div>

    <!-- Avatar -->
    <div>
        <label for="avatar" class="block text-gray-600 font-medium mb-1">Avatar</label>
        <input type="text" id="avatar" name="avatar"
            value="<?= $character->avatar ?? '' ?>"
            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
    </div>

    <!-- Boutons -->
    <div class="flex justify-between items-center mt-4">
        <button type="submit"
            class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 shadow-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            <?= isset($character) ? "Modifier" : "Créer" ?>
        </button>
        <a href="/"
            class="text-gray-500 hover:text-gray-700 underline transition-all duration-200">
            Annuler
        </a>
    </div>
</form>
</div>