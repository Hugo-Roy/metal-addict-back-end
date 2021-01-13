# Share-O-Metal

## Introduction

Le site idéal pour partager sur les concerts Metal !

Le site regroupera les concerts passés et pour lesquels chaque utilisateur enregistré pourra apporter des commentaires, des reviews plus complètes, des photos...

L'utilisateur disposera également de sa propre page personnelle, qui regroupe tous les concerts auxquels il a assisté, les reviews qu'il a rédigé, les photos qu'il a partagé.

L'idée est de rester dans ce style musical en incluant tous les genres et sous-genres qui le composent.
Pour info, les API externes utilisées sont très complètes et permettront de remonter jusqu'au début du style
(avec des concerts des années 60/70 pour les plus anciens) jusqu'à aujourd'hui.

L'objectif est de rassembler les fans de metal afin de partager leur expérience et leurs souvenirs.

## Plan et fonctionnalités du site

### Page d'accueil

L'utilisateur pourra effectuer une recherche depuis la page d'accueil.

La recherche devra contenir, au minimum, le nom du groupe.
Cette même recherche pourra être affinée par:
- pays
- ville
- lieu d'évènement
- année

Seront également présents sur cette page:
- une présentation du site
- une liste des dernières reviews postées par les utilisateurs
- une liste des reviews les plus populaires

### Page de résultat de recherche

L'utilisateur verra ici une liste d'un ou plusieurs éléments résultants de sa recherche. Ceux-ci seront présentés sous forme de cards renseignants:
- le nom du groupe
- une photo illustrant le groupe
- la date du concert
- le lieu de l'évènement
- une icône "j'y était", permettant à l'utilisateur de signaler sa participation à cet évènement
- le nombre de reviews rédigées pour cet évènement
- le nombre de photos postées par les utilisateurs

### Page de concert

Après avoir cliqué sur l'un des précédents résultats de sa recherche, l'utilisateur accèdera à cette page. Sur celle-ci seront présents:
- le nom du groupe
- une photo illustrant le groupe
- la date du concert
- le lieu de l'évènement
- une icône "j'y était", permettant à l'utilisateur de signaler sa participation à cet évènement
- les reviews rédigées par les utilisateurs, accompagnées des photos de profil de leurs auteurs et leurs pseudonymes
- la setlist du concert
- ```(Une "note" attribuée par les utilisateurs)```

### Page de rédaction d'une review

Sur cette page, l'utilisateur aura la possibilité de rédiger une review ou il pourra détailler son expérience du concert sélectionné.

```(Seront présents: un système de notation par "pouces" ou case "je recommande", un système de notation du lieu de l'évènement; de la qualité du son, de la scénographie, etc.)```

### Page review

Ici, l'utilisateur aura accès à la version complète de la review sélectionnée, composée comme tel:
- titre
- pseudonyme de l'utilisateur et sa photo de profil
- date de publication
- contenu principal

Seront égalements affichés, les photos prisent et ajoutées par l'utilisateur, les commentaires associés à cette même review.

### Page publique de l'utilisateur

C'est le profil publique de l'utilisateur connecté, consultable par les autres utilisateurs. Ceux-ci auront pourront voir:
- les informations de bases concernant l'utilisateur visité
- les concerts auxquels il a précisé avoir participé
- les reviews correspondantes à ces concerts, si existantes

### Page d'inscription

C'est ici qu'un utilisateur non-inscrit deviendra un utilisateur inscrit grâce à un formulaire d'inscription. Voilà.

### Page ou fenêtre de connexion 

C'est ici qu'un utilisateur inscrit se connectera grâce à un formulaire pour, entre autre, accéder à son espace personnel, rédiger des reviews...

## Rôles utilisateurs

les utilisateurs **non connectés** auront accès à l'ensemble des services du site à l'exception de poster des reviews, commenter des reviews, renseigner avoir participé à un évènement.

Les utilisateurs **connectés** auront accès à l'ensemble des fonctionnalités proposées par le site.

## Technologies utilisées

### Front:
- React, librairies

### Back:
- Symfony, MySQL, PHP