# Projet2 - Phototendance : Création et administration d'une base de donnée

## But et application
Ce projet à été réaliser durant un stage. Il sert à ressencer les villes,départements,région(anciennes et nouvelles) de france.
Une base qui sera utilisé pour la création d'un site internet de vente de tee-short personalisé (ne fait pas parti du projet présenter).

## Présentation générale

### Présentation de la société

**Phototendance**, implantée à deux pas de Béziers (Hérault, Languedoc Roussillon), est le fruit de plus de 10ans d’expérience dans la photographie puis le Web. La société a su prendre le virage de l’innovation en devenant spécialiste de l’image animée à 360° (visite virtuelle interactive & objet 360°). ils ons ensuite développé d’autres savoir-faire tels que la prise de vue aérienne et la modélisation 3D.

### Contexte

La société phototendance a décider de ce lancer dans un nouveau projet, la création d'un site internet de vente de tee-shit personalisé sur Prestashop, ciblé sur les villes/département/régions est leurs gentillés. Le but est de pouvoir crée un tee-shit avec écrit, par exemple "Bitérois et fier de l'être". Pour ce faire il nous faut crée une base de donnée recensant toutes les villes/département/régions ainsi que des informations sur celle-ci. Etant donnée de la taille demander pour une telle base de donnée, il est important de faire une seconde base de donnée, d'où le fait de ne pas utiliser la base de prestashop. 
Pour des raisons d'optimisation et pour éviter de nombreuses erreurs et facilités la communication avec Prestashop nous utiliserons en premier temps la base de donnée sous format CSV pour l'utiliser sous Prestashop.
De plus étant donnée de l'empleurs des donnée ainsi que de l'ancienneté des base de donnée mis à disposition (insee,datagouv,...) il faudra un système d'administration pour modifier les erreurs de façon éfficace.
Et enfin pour le référencement google il nous faudra instaurer des meta pour chaque ville.

## Expression fonctionelle du besoin

### Objectifs
+ Création d'une base de donnée recensant toutes les villes/départements/régions
+ Création d'un système d'administration
+ Ressortire sous format CSV toutes les informations nécéssaires

### Description du contenu

- Base de donnée
- Modification des gentille
- Ajout des meta(spécifique à une ville, pour toutes les villes)
- Modifications des informations pour une ville/département/région
