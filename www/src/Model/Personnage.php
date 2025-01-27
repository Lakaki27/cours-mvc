<?php

namespace App\Model;

class Personnage
{
    private const XP_BASE = 10;
    private const XP_FACTOR = 1.26;

    protected ?int $id; // ID peut être null
    protected string $nom;
    private int $PV;
    private int $PVMax;
    private int $force;
    private int $facesDe;
    private int $chance;
    private int $XP = 0;
    private int $money;
    private int $level = 0;
    private string $avatar;
    protected string $classe;

    public function __construct(
        string $nom,
        int $PV,
        int $PVMax,
        int $force,
        int $facesDe = 6,
        int $chance = 50,
        int $money = 100,
        string $avatar = 'avatar.jpg',
        int $XP = 0,
        int $level = 0,
        int|null $id = null,
        string $classe = "Personnage"
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->PV = $PV;
        $this->PVMax = $PVMax;
        $this->force = $force;
        $this->facesDe = $facesDe;
        $this->chance = $chance;
        $this->money = $money;
        $this->avatar = "/img/$avatar";
        $this->classe = $classe;
        $this->XP = $XP;
        $this->level = $level;
    }

    public function isAlive()
    {
        return $this->getPV() > 0;
    }

    public function gagnerXP($XP)
    {
        $this->setXP($this->getXP() + $XP);
        return $this->levelUp();
    }

    public function gagnerMoney($money)
    {
        $this->setMoney($this->getMoney() + $money);
    }

    /**
     * Fonction permettant d'ajuster l'XP et le niveau d'un personnage après avoir gagné de l'XP.
     * @return int Nombre de niveaux gagnés suite au rééquilibrage
     */
    private function levelUp(): int
    {
        $gainedLevels = 0;
        while ($this->getXP() >= $this->getXPForLevelUp()) {
            $gainedLevels++;
            $this->setXP($this->getXP() - $this->getXPForLevelUp());
        }

        $this->setLevel($this->getLevel() + $gainedLevels);
        return $gainedLevels;
    }

    /**
     * Méthode de calcul de l'XP nécessaire avant le prochain level up.
     * Suite géométrique: chaque niveau requiert 1.26 fois plus d'XP que le précédent, commençant au niveau 0 avec x(0) = 10XP.
     * @param int Un niveau précis pour le calcul de la formule géométrique, ou null pour le niveau actuel du personnage.
     * @return int L'XP manquante avant le prochain level up.
     */
    public function getXPForLevelUp(int|null $level = null)
    {
        $level = is_null($level) ? $this->getLevel() : $level;

        return ceil(self::XP_BASE * pow(self::XP_FACTOR, $level));
    }

    public function attaquer(Personnage $cible)
    {

        // On lance le dé
        $scoreDe = rand(1, $this->getFacesDe());

        $resultat = "{$this->getTitle()} lance son dé à {$this->getFacesDe()} faces et obtient $scoreDe\n";

        // On ramène à une chiffre entre 0 et 1
        $factDe = $scoreDe / $this->getFacesDe();
        // On ajoute la chance
        $factChance = $this->getChance() / 100;
        // On fait une moyenne entre le dé et la chance
        $chanceFinale = ($factDe + $factChance) / 2;

        // Si le calcul est inférieur à 0.5, on rate l'attaque
        $success = $chanceFinale > 0.5;

        if (!$success) {
            $resultat .= "{$this->getTitle()} rate son attaque !\n";
            return $resultat;
        } else {
            $resultat .= "{$this->getTitle()} attaque {$cible->getTitle()}! GRAOUUUU !\n";
            $resultat .= "{$cible->getTitle()} perd {$this->getForce()} PV !\n";
            $cible->setPV($cible->getPV() - $this->getForce());
        }

        return $resultat;
    }

    public function wait() {
        $sentences = [
            "{$this->getNom()} est dans la lune, il passe son tour !",
            "{$this->getNom()} décide d'être pacifiste et de ne pas attaquer !",
            "{$this->getNom()} prend un traitement homéopathique ! (ça n'a aucun effet...)",
            "Mais que fout {$this->getNom()} ? Il oublie son tour !",
            "{$this->getNom()} est occupé à admirer ma femme et en oublie de faire une action !"
        ];

        return $sentences[array_rand($sentences, 1)] . "\n";
    }

    /**
     * Get the value of id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of nom
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    public function getTitle(): string
    {
        return "{$this->getNom()} le {$this->getClasse()}";
    }

    /**
     * Set the value of nom
     */
    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get the value of PV
     */
    public function getPV(): int
    {
        return $this->PV;
    }

    /**
     * Set the value of PV
     */
    public function setPV(int $PV): self
    {
        $this->PV = $PV;

        return $this;
    }

    /**
     * Get the value of PVMax
     */
    public function getPVMax(): int
    {
        return $this->PVMax;
    }

    /**
     * Set the value of PVMax
     */
    public function setPVMax(int $PVMax): self
    {
        $this->PVMax = $PVMax;

        return $this;
    }

    /**
     * Get the value of force
     */
    public function getForce(): int
    {
        return $this->force;
    }

    /**
     * Set the value of force
     */
    public function setForce(int $force): self
    {
        $this->force = $force;

        return $this;
    }

    /**
     * Get the value of facesDe
     */
    public function getFacesDe(): int
    {
        return $this->facesDe;
    }

    /**
     * Set the value of facesDe
     */
    public function setFacesDe(int $facesDe): self
    {
        $this->facesDe = $facesDe;

        return $this;
    }

    /**
     * Get the value of chance
     */
    public function getChance(): int
    {
        return $this->chance;
    }

    /**
     * Set the value of chance
     */
    public function setChance(int $chance): self
    {
        $this->chance = $chance;

        return $this;
    }

    /**
     * Get the value of XP
     */
    public function getXP(): int
    {
        return $this->XP;
    }

    /**
     * Set the value of XP
     */
    public function setXP(int $XP): self
    {
        $this->XP = ceil($XP);

        return $this;
    }

    /**
     * Get the value of money
     */
    public function getMoney(): int
    {
        return $this->money;
    }

    /**
     * Set the value of money
     */
    public function setMoney(int $money): self
    {
        $this->money = $money;

        return $this;
    }

    /**
     * Get the value of avatar
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * Set the value of avatar
     */
    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get the value of classe
     */
    public function getClasse(): string
    {
        return $this->classe;
    }

    /**
     * Set the value of classe
     */
    public function setClasse(string $classe): self
    {
        $this->classe = $classe;

        return $this;
    }

    /**
     * Get the value of level
     */
    public function getLevel(): int
    {
        return $this->level;
    }

    /**
     * Set the value of level
     */
    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }
}
