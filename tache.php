<?php

class Tache {
    private $id;
    private $texte;
    private $priorite;
    private $terminee;

    public function __construct($id, $texte, $priorite, $terminee) {
        $this->id = $id;
        $this->texte = $texte;
        $this->priorite = $priorite;
        $this->terminee = $terminee;
    }

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

    public function setId($id) {
        $this->id = $id;
    }
}


