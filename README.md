# Graphiques Statistiques

Dans le cadre du Hackathon des Archives Nationales 2018, l'un des axes de travail
était la visualisation des données d'une fiche producteur.

Nous avons choisi de visualiser les cinq informations suivantes pour le notaire en
cours de consultation :

* Les occupations des différents clients du notaire.
* Les types d'acte produit par le notaire.
* L'activité du notaire dans le temps.
* Les dix clients les plus suivis par le notaire
* L'aire d'influence du notaire


## Installation du projet


### Installation sous Docker

Si vous avez le logiciel **Docker** installé sur votre PC,
vous pouvez suivre ce chapitre.
Néanmoins, il faudra également disposer d'un certain nombre d'outil
pour aller jusqu'au bout de l'opération.

Voici la liste des outils à installer en fonction de vore environnement

| Outil | Windows | Linux | Description |
|-------|:-------:|:-----:|-------------|
| Docker  |    X    |   X   | Comprenant : ``docker``, ``docker-compose`` et ``docker-machine``<sup>1</sup> |
| GIT   |    X    |   X   | Requis pour son ``MinGW`` et son intégration à Windows<sup>2</sup> |
| Make   |    X    |   -   | Commande `make`<sup>3</sup> |

<sup>1</sup> : Pour Windows avec Docker Toolbox.

<sup>2</sup> : L'intégration de GIT dans Windows permet de fusionner les
commandes Linux avec celles de Windows.

<sup>3</sup> : ``make`` est une commande Linux en standard.


Lorsque les outils sont installés,
rendez-vous à la racine du projet où se trouve le fichier ``README.md``
et lancez une invite de commande.
Tapez la commande : ``make install``.

La première installation prendra du temps,
car **Docker** doit générer l'image du système
à l'aide du fichier ``docker/core/Dockerfile``.
Cela comprend le téléchargement de librairies et de mise à jour du système.
L'étape suivante consiste à télécharger les dépédances du projet qui va prendre
également un certain temps.

Lorsque le processus d'installation sera terminée,
la démonstration est utilisable à l'adresse suivante : ``http://localhost``.

Si la page ne se charge pas et que la commande ``make ls``
indique dans la colonne ``STATUS`` du style `Up About a minute`
il y à probablement un problème de redirection de port.

````plaintext
CONTAINER ID        IMAGE               COMMAND                  CREATED                  STATUS                  PORTS                                                                        NAMES
6c3b0ca392db        hackhan             "/bin/bash /entrypoi…"   Less than a second ago   Up Less than a second   0.0.0.0:80->80/tcp, 0.0.0.0:443->443/tcp, 3307/tcp, 0.0.0.0:3307->3306/tcp   hackhan
````

Si la page se charge avec des erreurs, patientez que
l'installation des dépendances se termine.

Si après un certain temps, cela ne fonctionne toujours pas,
tapez la commande ``make hackhan`` pour vous rendre
dans la machine.

Tapez la commande ``composer update`` pour vérifier que les
dépendances soient bien installées et à jour.

Si cela n'aboutis toujours pas, contactez l'équipe
Une Minute Ago.



### Installation Manuelle

Si vous avez déjà un serveur Web et que vous souhaitez
simplement avoir les ressources,
il suffit de clôner le projet pour les obtenirs.

Neanmoins, il faudra se munir de l'outil ``composer``
pour procéder à l'installation des dépendances.


#### Clonage du dépôt

Lancez une invite de commande et tapez la commande suivante :

````git
git clone https://github.com/UneMinuteAgo/stats.git
````

Le dossier cloné ``stats`` se trouvera à l'emplacement où vous avez tapez
la commande.
Aller dans le dossier ``stats`` à l'aide de la commande `cd stats`.



#### Installation de Composer

Si vous n'avez pas ``composer``, suivez les instructions suivantes.
Si vous avez déjà ``composer``, vous pouvez passer au chapitre suivant.

Dans une invite de commande, tapez les commandes suivantes :

````bash
wget https://getcomposer.org/composer.phar
chmod +x composer.phar
mv composer.phar /usr/local/bin/composer
````

Vérifiez que l'installation s'est déroulée correctement
à l'aide de la commande suivante : ``composer --version``



#### Création du projet

Une fois que ``composer`` est opérationnel,
si vous n'êtes pas dans le dossier cloné ``stats``,
rendez-vous y à l'aide de la commande ``cd stats``.

Tapez la commande suivante pour obtenir les dépendances :
``composer create-project``



#### Se mettre à jour

Pour maintenir à jour les ressources obtenu depuis le **GitHub**,
effectuez de temps en temps la commande suivante :
``git fetch --all``

S'il y à eu des mises à jour, il faudra
les appliquer à l'aide de la commande ``git pull origin master``.
Cela implique que vous n'ayez modifiés aucun fichier.

