<?php

class Tache {
    private string $texte;
    private string $priorite;

    public function __construct(string $texte, string $priorite = 'normale') {
        $this->texte = $texte;
        $this->priorite = $priorite;
    }

    public function getTexte(): string {
        return $this->texte;
    }

    public function getPriorite(): string {
        return $this->priorite;
    }

    public function toLigne(): string {
        return $this->texte . '|' . $this->priorite;
    }

    public static function fromLigne(string $ligne): ?self {
        $parts = explode('|', $ligne);
        if (count($parts) >= 1) {
            $texte = trim($parts[0]);
            $priorite = $parts[1] ?? 'normale';
            return new self($texte, trim($priorite));
        }
        return null;
    }
}
