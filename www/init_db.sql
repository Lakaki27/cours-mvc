-- Création de la table characters
CREATE TABLE IF NOT EXISTS characters (
    id INTEGER PRIMARY KEY AUTO_INCREMENT, -- Identifiant unique
    name TEXT NOT NULL,                    -- Nom du personnage
    PV INTEGER NOT NULL,                   -- Points de vie actuels
    PVMax INTEGER NOT NULL,                -- Points de vie maximum
    str INTEGER NOT NULL,                -- Force du personnage
    facesDe INTEGER NOT NULL DEFAULT 6,    -- Nombre de faces pour le dé
    chance INTEGER NOT NULL DEFAULT 50,    -- Taux de chance (0-100)
    XP INTEGER NOT NULL DEFAULT 0,         -- Expérience du personnage
    mny INTEGER NOT NULL DEFAULT 0,      -- Argent possédé
    avatar TEXT NOT NULL,                  -- Chemin de l'image
    class TEXT NOT NULL                    -- Type de personnage (ex: "Personnage", "Vampire")
);

-- Insertion d'exemples de personnages
INSERT INTO characters (name, PV, PVMax, str, facesDe, chance, XP, mny, avatar, class)
VALUES 
    ('Bob', 100, 100, 10, 6, 50, 0, 10, 'bob.jpg', 'Personnage'),
    ('Dracula', 90, 90, 15, 8, 70, 0, 50, 'dracula.jpg', 'Vampire');