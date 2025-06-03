# 📝 Projet PHP – To-Do List

Ce projet est une application web simple développée en **PHP natif**, permettant à un utilisateur d’ajouter, afficher et supprimer des tâches via une interface claire et responsive.

---

## 🚀 Fonctionnalités

- ✅ Ajout de tâches avec niveau de priorité (normale, importante, urgente)
- 🗑️ Suppression individuelle de tâches
- 💾 Sauvegarde locale dans un fichier `.txt` (pas de base de données)
- 🔁 Protection contre les doublons via le pattern PRG (Post/Redirect/Get)
- ✨ Affichage dynamique avec design responsive CSS
- 🔒 Validation des entrées (anti-XSS, anti-champs vides)
- ✅ Code structuré en POO, prêt pour migration vers une base de données

---

## 🧱 Structure du projet

Todolist/
├── index.php # Page principale (formulaire + logique)
├── taches.php # Gestion du stockage des tâches (POO)
├── style.css # Feuille de styles
├── script.js # JS léger pour animations frontend
├── data/
│ └── taches.txt # Fichier de sauvegarde des tâches
├── .gitignore # Exclusion du fichier de données
└── README.md # Documentation du projet


---

## ⚙️ Technologies utilisées

- 🐘 PHP (sans framework)
- 🔤 HTML5 / CSS3
- 📄 Fichier `.txt` pour le stockage
- 🌐 Serveur local : **MAMP (Mac)**

---

## 💻 Installation & lancement

1. Cloner ce dépôt dans votre répertoire `php-projects`
2. Ouvrir **MAMP** et vérifier que le `Document Root` pointe vers le bon dossier
3. Démarrer les serveurs Apache
4. Accéder à l’application via :

http://localhost:8888/Todolist/


---

## 💡 Pistes d’amélioration

- ✅ Passage à une base de données MySQL via PDO
- 📆 Ajout de dates limites ou deadlines
- 🗂️ Catégorisation ou filtres de tâches
- 🧑‍💼 Authentification utilisateur
- 🌙 Ajout d’un mode sombre
- 📱 Amélioration de l’UI avec Bootstrap ou Tailwind

---

## 👨‍💻 À propos

Ce projet a été réalisé dans le cadre de ma **préparation à un jobdating pour une alternance en développement web**, afin de démontrer mes compétences en :

- Programmation PHP orientée objet
- Manipulation de fichiers
- Structuration de projet modulaire
- Logique back-end simple et sécurisée

---

> Merci de votre lecture ! Si vous avez des suggestions ou feedbacks, je suis preneur 🙌






