<?php

// Dans la structure du projet, la classe est dans Personnage
namespace App\Model;

use App\Model\Personnage;

class Bagarre
{
    protected ?int $id;

    protected Personnage $personnageA;
    
    protected Personnage $personnageB;
    
    protected ?int $hpA;
    
    protected ?int $hpB;

    protected ?int $forceA;

    protected ?int $forceB;
    
    protected int $turn;

    public function __construct(
        int $id,
        Personnage $personnageA,
        Personnage $personnageB,
        int $hpA,
        int $hpB,
        int $forceA,
        int $forceB,
        int $turn
    ) {
        $this->id = $id;
        $this->personnageA = $personnageA;
        $this->personnageB = $personnageB;
        $this->hpA = $hpA;
        $this->hpB = $hpB;
        $this->forceA = $forceA;
        $this->forceB = $forceB;
        $this->turn = $turn;
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
     * Get personnage A
     */
    public function getPersonnageA(): ?Personnage
    {
        return $this->personnageA;
    }

    /**
     * Set personnage A
     */
    public function setPersonnageA(Personnage $a): self
    {
        $this->personnageA = $a;

        return $this;
    }

    /**
     * Get personnage B
     */
    public function getPersonnageB(): ?Personnage
    {
        return $this->personnageB;
    }

    /**
     * Set personnage B
     */
    public function setPersonnageB(Personnage $b): self
    {
        $this->personnageB = $b;

        return $this;
    }

    /**
     * Get Hp A
     */
    public function getHpA(): ?int
    {
        return $this->hpA;
    }

    /**
     * Set Hp A
     */
    public function setHpA(int $hp): self
    {
        $this->hpA = $hp;

        return $this;
    }

    /**
     * Get Hp B
     */
    public function getHpB(): ?int
    {
        return $this->hpB;
    }

    /**
     * Set Hp B
     */
    public function setHpB(int $hp): self
    {
        $this->hpB = $hp;

        return $this;
    }

    /**
     * Get force A
     */
    public function getForceA(): ?int
    {
        return $this->forceA;
    }

    /**
     * Set force A
     */
    public function setForceA(int $force): self
    {
        $this->forceA = $force;

        return $this;
    }

    /**
     * Get force B
     */
    public function getForceB(): ?int
    {
        return $this->forceB;
    }

    /**
     * Set force B
     */
    public function setForceB(int $force): self
    {
        $this->forceB = $force;

        return $this;
    }

    /**
     * Get turn
     */
    public function getTurn(): ?int
    {
        return $this->turn;
    }

    /**
     * Set turn
     */
    public function setTurn(int $turn): self
    {
        $this->turn = $turn;

        return $this;
    }
}
