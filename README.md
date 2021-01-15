# Share-O-Metal

## Introduction

Le site idéal pour partager sur les concerts Metal !

Le site regroupera les concerts passés et pour lesquels chaque utilisateur enregistré pourra apporter des reviews et des photos.

L'utilisateur disposera également de sa propre page personnelle, qui regroupe tous les concerts auxquels il a assisté, les reviews qu'il a rédigées, les photos qu'il a partagé.

L'idée est de rester dans ce style musical en incluant tous les genres et sous-genres qui le composent.

L'objectif est de rassembler les fans de metal afin de partager leurs expériences et leurs souvenirs.

```(Le texte présenté sous cette forme signal les fonctionnalités envisagées pour l'avenir)```

## Plan et fonctionnalités du site

### Page d'accueil

L'utilisateur pourra effectuer une recherche depuis la page d'accueil.

La recherche devra contenir, au minimum, le nom du groupe.
Cette même recherche pourra être affinée par:
- pays
- année
- ```(ville)```
- ```(lieu de l'évènement)```

Seront également présents sur cette page:
- une présentation du site
- une liste des dernières reviews postées par les utilisateurs

### Page de résultat de recherche

L'utilisateur verra ici une liste d'un ou plusieurs éléments résultants de sa recherche. Ceux-ci seront présentés sous forme de cards renseignants:
- le nom du groupe
- une photo
- la date de l'évènement
- le lieu de l'évènement
- ```(une photo illustrant le groupe)```
- ```(une icône "j'y étais", permettant à l'utilisateur de signaler sa participation à cet évènement)```
- ```(le nombre de reviews rédigées pour cet évènement)```
- ```(le nombre de photos postées par les utilisateurs)```

### Page de concert

Après avoir cliqué sur l'un des précédents résultats de sa recherche, l'utilisateur accèdera à cette page. Sur celle-ci seront présents:
- le nom du groupe
- la date du concert
- le lieu de l'évènement
- une icône "j'y étais", permettant à l'utilisateur connecté de signaler sa participation à cet évènement
- les reviews rédigées par les utilisateurs, accompagnées des photos de profil de leurs auteurs et leurs pseudonymes
- la setlist du concert
- une galerie de photos, concernants le concert, ajoutées par les utilisateurs
- la possiblité pour l'utilisateur connecté d'ajouter une photo
- la possiblité pour l'utilisateur connecté d'ajouter une review
- ```(une photo illustrant le groupe)```
- ```(Une "note" attribuée par les utilisateurs)```

### Page de rédaction d'une review

Sur cette page, l'utilisateur connecté aura la possibilité de rédiger une review ou il pourra détailler son expérience du concert sélectionné. Seront présent:
- le titre de la review
- le contenu de la review
- ```(un système de notation par "pouces" ou case "je recommande", un système de notation du lieu de l'évènement; de la qualité du son, de la scénographie, etc.)```


### Page review

Ici, l'utilisateur aura accès à la version complète de la review sélectionnée, composée comme telle:
- titre
- pseudonyme de l'auteur de la review et sa photo de profil
- date de publication
- contenu principal

Seront égalements affichés, les photos prises et ajoutées par l'auteur de la review, ```(les commentaires associés à cette même review)```.

### Page profil d'un utilisateur visité

C'est le profil d'un utilisateur inscrit, consultable par les autres utilisateurs. Ceux-ci pourront voir:
- les informations de bases concernant l'utilisateur visité
- les concerts auxquels il a précisé avoir participé
- les photos ajoutées par l'utilisateur visité.
- les reviews correspondantes à ces concerts, si existantes.

### Page profil de l'utilisateur connecté concerné

C'est le profil d'un utilisateur inscrit, consultable par les autres utilisateurs. Ceux-ci pourront voir:
- les informations de bases concernant l'utilisateur
- les concerts auxquels il a précisé avoir participé
- les photos ajoutées par l'utilisateur visité.
- les reviews correspondantes à ces concerts, si existantes.
- possibilité de supprimer ses reviews
- possibilité de supprimer ses photos
- possibilité de modifier ses infos

### Page d'inscription

C'est ici qu'un utilisateur non-inscrit deviendra un utilisateur inscrit grâce à un formulaire d'inscription.

```(Validation d'inscription par email)```

### Fenêtre de connexion

C'est ici qu'un utilisateur inscrit se connectera grâce à un formulaire de connexion.

## Rôles utilisateurs

les utilisateurs **non connectés** auront accès à l'ensemble de la navigation du site.

Les utilisateurs **connectés** auront accès à l'ensemble de la navigation du site, il pourra:
- ajouter, modifier, supprimer ses reviews
- ajouter, modifier, supprimer ses photos
- modifier les informations de son profil
- ajouter un concert à son historique via le bouton "j'y étais"

## Technologies utilisées

### Front:
- React, librairies React Hook Form, Animate on Scroll, React Slick, React Spinner.

### Back:
- Symfony, MySQL, PHP

### API:
- Musicbrainz (https://musicbrainz.org/doc/MusicBrainz_API) nécessaire à la création de notre banque de données de groupes.
- Setlist, nécessaire à la récupération d'évènements et de leurs données associées.
- https://api.setlist.fm/docs/1.0/resource__1.0_artist__mbid__setlists.html
- https://api.setlist.fm/docs/1.0/resource__1.0_setlist__setlistId_.html
- https://api.setlist.fm/docs/1.0/ui/index.html#//1.0/search/setlists

## Equipe:

- Product owner : Thomas Lutaster
- Scrum master : Frédéric Millox
- Lead dev front : Frédéric Millox
- Lead dev back : Hugo Drelon
- Git master : Hugo Roy
- Référent par librairie : Thomas Lutaster

## User Stories

- En tant que visiteur, je peux utiliser la navigation (accueil, connexion)
- En tant que visiteur, je peux effectuer une recherche par nom (obligatoire), ville, pays, salle de spectacles ou année
- En tant que visiteur, je peux consulter sur la Home les dernières reviews postés par les utilisateurs
- En tant que visiteur, je peux consulter la page Results Events
- En tant que visiteur, je peux cliquer sur la page Results Events sur un événement retourné
- En tant que visiteur, je peux filtrer sur la page Results Events les résultats de la recherche
- En tant que visiteur, je peux consulter la page Event
- En tant que visiteur, je peux cliquer sur la page Event sur une review du concert
- En tant que visiteur, je peux consulter sur la page Event toutes les photos des reviews du concert
- En tant que visiteur, je peux consulter la page Review
- En tant que visiteur, je peux consulter sur la page Review toutes les photos des reviews du concert
- En tant que visiteur, je peux m’inscrire sur le site
- En tant qu’utilisateur inscrit, je peux indiquer sur la page Results Events que j’ai participé au concert
- En tant qu’utilisateur inscrit, je peux accéder sur la page Event à la page Add Review
- En tant qu’utilisateur inscrit, je peux ajouter/supprimer sur la page Event une photo
- En tant qu’utilisateur inscrit, je peux créer sur la page Add Review une review
- En tant qu’utilisateur inscrit, je peux modifier sur la page Review ma review
- En tant qu’utilisateur inscrit, je peux accéder à la page User Profile
- En tant qu’utilisateur inscrit, je peux sur la page Page Profile lister mes photos / suppression
- En tant qu’utilisateur inscrit, je peux sur la page Page Profile lister mes concerts
- En tant qu’utilisateur inscrit, je peux sur la page Page Profile lister mes reviews
- En tant que visiteur, je peux utiliser la navigation (accueil, déconnexion)