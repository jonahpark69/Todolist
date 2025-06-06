<?php

class Tache {
    private int $id = 0;              // 🆔 Identifiant unique de la tâche (issu de la BDD)
    private string $texte;           // 📝 Contenu de la tâche
    private string $priorite;        // 🔺 Priorité : normale, importante, urgente
    private bool $terminee;          // ☑️ Statut : terminée ou non

    // 🧱 Constructeur
    public function __construct(string $texte, string $priorite = 'normale', bool $terminee = false) {
        $this->texte = $texte;
        $this->priorite = $priorite;
        $this->terminee = $terminee;
    }

    // 🔄 Getter / Setter pour ID
    public function getId(): int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    // 🔄 Getter / Setter pour texte
    public function getTexte(): string {
        return $this->texte;
    }

    public function setTexte(string $texte): void {
        $this->texte = $texte;
    }

    // 🔄 Getter / Setter pour priorité
    public function getPriorite(): string {
        return $this->priorite;
    }

    public function setPriorite(string $priorite): void {
        $this->priorite = $priorite;
    }

    // 🔄 Getter / Setter pour terminé
    public function estTerminee(): bool {
        return $this->terminee;
    }

    public function setTerminee(bool $terminee): void {
        $this->terminee = $terminee;
    }
}

