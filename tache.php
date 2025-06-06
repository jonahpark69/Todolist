<?php

class Tache {
    private string $texte;
    private string $priorite;
    private bool $terminee;

    // ✅ Le constructeur accepte maintenant $terminee
    public function __construct(string $texte, string $priorite, bool $terminee = false) {
        $this->texte = $texte;
        $this->priorite = $priorite;
        $this->terminee = $terminee;
    }

    public function getTexte(): string {
        return $this->texte;
    }

    public function getPriorite(): string {
        return $this->priorite;
    }

    public function estTerminee(): bool {
        return $this->terminee;
    }

    public function marquerCommeTerminee(bool $val): void {
        $this->terminee = $val;
    }

    // BONUS si tu veux ajouter un setter plus tard :
    public function setTexte(string $texte): void {
        $this->texte = $texte;
    }

    public function setPriorite(string $priorite): void {
        $this->priorite = $priorite;
    }
}
