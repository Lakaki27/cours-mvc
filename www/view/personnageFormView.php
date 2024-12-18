<h2 class="text-3xl font-extrabold text-center text-gray-700 mb-6">
    Modifier le personnage
</h2>

<form method="POST" class="space-y-5">
    <!-- Nom -->
    <div>
        <label for="name" class="block text-gray-600 font-medium mb-1">Nom</label>
        <input type="text" id="name" name="name"
            value="<?= isset($character) ? $character->getNom() : '' ?>"
            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
    </div>
    
    <!-- Avatar -->
    <div>
        <label for="avatar" class="block text-gray-600 font-medium mb-1">Avatar</label>
        <input type="text" id="avatar" name="avatar"
            value="<?= isset($character) ? $character->getAvatar() : '' ?>"
            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
    </div>

    <!-- Boutons -->
    <div class="flex justify-between items-center mt-4">
        <button type="submit"
            class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 shadow-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            Modifier
        </button>
        <a href="/"
            class="a-btn">
            Annuler
        </a>
    </div>
</form>
</div>