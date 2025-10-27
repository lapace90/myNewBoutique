# E-Commerce Symfony Platform

![Symfony](https://img.shields.io/badge/Symfony-6.x-000000?style=for-the-badge&logo=symfony&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.x-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

## 📝 Description

Une plateforme e-commerce moderne et élégante développée avec Symfony, offrant une expérience d'achat complète et intuitive. Cette application propose toutes les fonctionnalités essentielles d'une boutique en ligne professionnelle, de la gestion des produits au système de paiement sécurisé.

## Fonctionnalités Principales

### **Gestion des Produits**
- Catalogue produits avec catégories
- Fiches produits détaillées avec descriptions et images
- Système de recherche avancée avec filtres (nom, prix, catégorie)
- Pagination intelligente des résultats

### **Système d'Évaluations**
- Commentaires et notes par les clients
- Calcul automatique de la note moyenne
- Vérification d'achat avant évaluation
- Affichage des avis sur les fiches produits

### **Gestion de la Livraison**
- Multiple transporteurs disponibles
- Différentes options de livraison (express, standard, point relais)
- Calcul automatique des frais de port
- Option Click & Collect gratuite

### **Espace Client**
- Inscription et connexion sécurisées
- Gestion du profil utilisateur
- Historique des commandes
- Suivi des livraisons
- Gestion des adresses de livraison

### **Panier & Commandes**
- Ajout/suppression de produits au panier
- Modification des quantités
- Processus de commande en plusieurs étapes
- Confirmation de commande par email

### **Recherche & Navigation**
- Barre de recherche globale
- Filtres par catégorie
- Filtres par fourchette de prix
- Tri des résultats (pertinence, prix)
- Navigation par catégories

## Installation

### Prérequis

- PHP 8.1 ou supérieur
- Composer
- Symfony CLI
- MySQL 8.0 ou supérieur
- Node.js et npm (pour les assets)

### Étapes d'installation

1. **Cloner le repository**
```bash
git clone https://github.com/votre-username/votre-projet.git
cd votre-projet
```

2. **Installer les dépendances PHP**
```bash
composer install
```

3. **Configurer la base de données**

Créer un fichier `.env.local` et configurer votre connexion :
```env
DATABASE_URL="mysql://username:password@127.0.0.1:3306/ecommerce_db?serverVersion=8.0"
```

4. **Créer la base de données**
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

5. **Charger les données de test (optionnel)**
```bash
php bin/console doctrine:fixtures:load
```

6. **Installer les dépendances front-end**
```bash
npm install
npm run build
```

7. **Lancer le serveur de développement**
```bash
symfony server:start
```

L'application sera accessible à l'adresse : `https://localhost:8000`

## Architecture du Projet

```
├── src/
│   ├── Controller/       # Contrôleurs Symfony
│   ├── Entity/           # Entités Doctrine
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── Carrier.php
│   │   ├── Order.php
│   │   ├── Comment.php
│   │   └── User.php
│   ├── Form/            # Formulaires
│   ├── Repository/      # Repositories Doctrine
│   └── DataFixtures/    # Fixtures pour les données de test
├── templates/
│   ├── base.html.twig
│   ├── product/         # Templates produits
│   ├── account/         # Templates compte utilisateur
│   └── components/      # Composants réutilisables
├── public/
│   ├── css/
│   ├── js/
│   └── images/
├── migrations/          # Migrations de base de données
└── config/             # Configuration Symfony
```

## Modèle de Données

### Entités principales

- **Product** : Gestion des produits (nom, description, prix, image)
- **Category** : Catégories de produits
- **User** : Utilisateurs et comptes clients
- **Order** : Commandes clients
- **OrderDetails** : Détails des commandes
- **Carrier** : Transporteurs et modes de livraison
- **Comment** : Avis et évaluations des produits
- **SearchFilters** : Filtres de recherche avancée

## Technologies Utilisées

- **Backend**
  - Symfony 6.x
  - PHP 8.x
  - Doctrine ORM
  - Twig

- **Frontend**
  - Bootstrap 5
  - JavaScript (Vanilla)
  - Font Awesome Icons

- **Base de données**
  - MySQL 8.x

- **Outils**
  - Composer (gestion des dépendances PHP)
  - NPM (gestion des assets)
  - KnpPaginatorBundle (pagination)

## Tests

Pour lancer les tests :

```bash
# Tests unitaires
php bin/phpunit

# Tests fonctionnels
php bin/console doctrine:fixtures:load --env=test
php bin/phpunit --testsuite=functional
```

## Fonctionnalités Responsive

L'application est entièrement responsive et s'adapte à tous les types d'écrans :
- Mobile (< 768px)
- Tablette (768px - 1024px)
- Desktop (> 1024px)

## Sécurité

- Authentification sécurisée avec hashage des mots de passe
- Protection CSRF sur tous les formulaires
- Validation des données côté serveur
- Gestion des rôles et permissions
- Sessions sécurisées

## Performances

- Mise en cache des requêtes Doctrine
- Lazy loading des relations
- Pagination des résultats
- Optimisation des requêtes SQL
- Compression des assets

## Contribution

Les contributions sont les bienvenues ! Pour contribuer :

1. Forkez le projet
2. Créez une branche pour votre fonctionnalité (`git checkout -b feature/AmazingFeature`)
3. Committez vos changements (`git commit -m 'Add some AmazingFeature'`)
4. Poussez vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrez une Pull Request

## 📝 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## Équipe

- **Développeur Principal** : Ilaria Pace
- **Contact** : ilariapace06@gmail.com

## Remerciements

- Symfony Documentation
- Bootstrap Team
- La communauté Open Source

---

<p align="center">
  Fait avec ❤️ et ☕ en utilisant Symfony
</p>
