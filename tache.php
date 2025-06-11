<?php
/**
 * ===========================================================
 * Classe Tache — Représente une tâche dans l'application
 * ===========================================================
 * Cette classe modélise une tâche avec :
 * - un identifiant (id)
 * - un texte descriptif
 * - un niveau de priorité
 * - un état terminé ou non
 */

class Tache {
    private $id;
    private $texte;
    private $priorite;
    private $terminee;

    /**
     * Constructeur
     *
     * @param int|null $id         Identifiant unique (peut être null pour les nouvelles tâches)
     * @param string   $texte      Texte de la tâche
     * @param string   $priorite   Niveau de priorité (normale, importante, urgente)
     * @param int      $terminee   Statut de complétion (0 ou 1)
     */
    public function __construct($id, $texte, $priorite, $terminee) {
        $this->id        = $id;
        $this->texte     = $texte;
        $this->priorite  = $priorite;
        $this->terminee  = $terminee;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getTexte() {
        return $this->texte;
    }

    public function getPriorite() {
        return $this->priorite;
    }

    public function estTerminee() {
        return $this->terminee;
    }

    // Setter uniquement pour l'ID (défini après insertion en base)
    public function setId($id) {
        $this->id = $id;
    }
}



