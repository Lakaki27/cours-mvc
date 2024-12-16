<h2 class="text-3xl font-extrabold text-center text-gray-700 mb-6">
    Création de personnage
</h2>

<form method="POST" class="space-y-5">
    <!-- Nom -->
    <div>
        <label for="name" class="block text-gray-600 font-medium mb-1">Nom</label>
        <input type="text" id="name" name="name"
            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
    </div>
    
    <!-- PVMax -->
    <div>
        <label for="PVMax" class="block text-gray-600 font-medium mb-1">Max PV</label>
        <input type="number" id="PVMax" name="PVMax"
            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
    </div>

    <!-- Force -->
    <div>
        <label for="force" class="block text-gray-600 font-medium mb-1">Force</label>
        <input type="number" id="force" name="force"
            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
    </div>

    <!-- Classe -->
    <div>
        <label for="classe" class="block text-gray-600 font-medium mb-1">Force</label>
        <select name="classe" id="classe">
            <option value="Personnage">Personnage</option>
            <option value="Voleur">Voleur</option>
            <option value="Vampire">Vampire</option>
        </select>
    </div>

    <!-- Avatar -->
    <div>
        <label for="avatar" class="block text-gray-600 font-medium mb-1">Avatar</label>
        <input type="text" id="avatar" name="avatar"
            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
    </div>

    <!-- Boutons -->
    <div class="flex justify-between items-center mt-4">
        <button type="submit"
            class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-all duration-300 shadow-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            Créer
        </button>
        <a href="/"
            class="a-btn">
            Annuler
        </a>
    </div>
</form>
</div>