# ✅ To-Do List – Projet PHP

Ce projet est une application web de gestion de tâches, développée en **PHP natif** avec une base de données **MySQL**, un design **responsive avec Tailwind CSS**, et des interactions modernes via **JavaScript et AJAX**.

---

## 🚀 Fonctionnalités principales

- ✅ Ajout de tâches avec priorité (normale, importante, urgente)
- ✏️ Modification d'une tâche par double-clic (édition inline)
- ☑️ Marquage d'une tâche comme terminée (avec animation ✔️ + confettis)
- 🗑️ Suppression individuelle (avec modale de confirmation)
- 🧹 Suppression groupée des tâches terminées (via modale AJAX)
- 🔍 Recherche temps réel
- 🔃 Tri des tâches (par nom ou priorité)
- 🌓 Mode sombre (Dark mode)
- 🧱 Vue liste ↔ vue grille

---

## ⚙️ Technologies utilisées

- 🐘 PHP (POO, natif, sans framework)
- 🗄️ MySQL avec PDO pour le stockage des tâches
- 🧠 JavaScript (DOM, fetch/AJAX, animations)
- 🎨 Tailwind CSS (via CDN)
- 🔧 Git + GitHub Desktop
- 🌐 Serveur local MAMP (Mac)

---

## 📁 Structure du projet

Todolist/
├── index.php # Page principale
├── config.php # Connexion à la base de données
├── TacheStorageMySQL.php # Classe de gestion des tâches en BDD (POO)
├── update-terminee.php # Maj AJAX du statut "terminée"
├── update-texte.php # Maj AJAX du texte d'une tâche
├── delete-all-completed.php# Suppression groupée des tâches terminées
├── main.js # JS principal : interactions, modales, tri...
├── success.js # Animation ✔️ + confettis
├── style.css # (supprimé - remplacé par Tailwind)
├── README.md # Ce fichier


---

## 📦 Installation & lancement

1. Cloner ce dépôt dans votre dossier `php-projects`
2. Ouvrir **MAMP** (ou équivalent) et pointer le `Document Root` vers ce dossier
3. Vérifier la connexion MySQL dans `config.php`
4. Importer la base de données si nécessaire
5. Lancer le serveur Apache et accéder au projet via :  
   👉 `http://localhost:8888/Todolist/`

---

## 💡 Pistes d'amélioration futures

- 🔐 Authentification utilisateur
- 📆 Ajout de dates limites ou deadlines
- 🗂️ Filtres avancés (par statut, date, catégorie)
- 📱 Interface mobile améliorée
- 🛠️ Refactorisation JS en modules

---

## 🙋 À propos

Ce projet a été conçu dans le cadre de ma préparation à un **jobdating pour une alternance en développement web**. Il m’a permis de mettre en pratique mes compétences en :

- Développement PHP orienté objet
- Intégration MySQL sécurisée via PDO
- Conception d’interfaces modernes et responsives
- Interaction AJAX fluide et propre
- Gestion de projet versionné avec Git & GitHub

---

> Merci pour votre lecture ! N’hésitez pas à me faire part de vos retours 🙌








