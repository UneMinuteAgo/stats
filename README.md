# Interfaces de visualisation des données

Dans le cadre du Hackathon des Archives Nationales 2018, l'un des axes de travail
était la visualisation des données d'une fiche producteur.

Nous avons choisi de visualiser les cinq informations suivantes pour le notaire en
cours de consultation :

* Les occupations des différents clients du notaire.
* Les types d'acte produit par le notaire.
* L'activité du notaire dans le temps.
* Les dix clients les plus suivis par le notaire
* L'aire d'influence du notaire

Si vous souhaitez être informé des mise à jours majeurs
de cette documentation, veuillez watcher l'issue suivante 
sur GitHub : ``https://github.com/UneMinuteAgo/stats/issues/1``


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
git clone --recurse-submodules https://github.com/UneMinuteAgo/stats.git
````

Le dossier cloné ``stats`` se trouvera à l'emplacement où vous avez tapez
la commande.
Aller dans le dossier ``stats`` à l'aide de la commande `cd stats`.

Si le dossier à déjà été cloné,
pour intégrer les sous-modules, exécutez la commande suivante :

````git
git submodule update --init --recursive
````



#### Installation de Composer

L'installation présente via Docker installe et exécute la commande
qui procède à l'installation des dépendances.

Si vous souhaitez utiliser les ressources sur votre propre installation
et que vous n'avez pas ``composer``, suivez les instructions suivantes.
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

Pour mettre à jour les sous-modules, lancer la commande suivante :

````git
git submodule update --remote --merge
````

Pour mettre à jour les dépendances PHP,
tapez la commande suivante :

````bash
composer update
````






## Interfaces de visualisation

Le projet étant fourni avec un ``docker-compose.yml``,
très simplement utilisable à l'aide de la commande ``make install``,
les URL présentés dans la suite de la documentation se baserons
sur des adresses en ``localhost`` pointant vers le site web
installé dans Docker.

Si vous utilisez les ressources sur une autre installation,
pensez à adapter les chemins (si nécessaire).


### Interfaces pour les Pies Charts

L'interface pour les Pies Charts sert à obtenir
depuis un fichier XML producteur un jeu de donnée calculé afin de prduire
un graphique. Le Pie Chart est le graphique que nous avons choisis,
mais ce jeu de donnée peu être utilisé pour n'importe quel autre graphique<sup>1</sup>.

<sup>1</sup> Compatibilité à verifier sur les autres format de graphiques.


#### Prévisualisation en standalone

La projet offre par le biais de la page ``http://localhost/index.php``,
une interface pour visualiser, tester et maniupler un graphique.
Cette page utilise le script (`site/lib/iface/getStats.php`) qui produit 
le jeu de donnée exploitable par le moteur ``Pie``
disponible dans `site/lib/js/Pie.js`.



#### Obtention des données via "API"

L'inteface ``site/lib/iface/getStats.php`` produit un JSON
à partir d'un fichier XML producteur.
Dans le cadre de ce projet,
les fichiers XML doivent se trouver dans le dossier ``site/lib/xml``.

Cette interface accepte les requêtes via les méthodes ``GET`` and `POST`.
Pour la documentation, nous opterons pour la méthode ``GET`` qui est plus
simple et plus visuelle.

Voici la liste des arguments acceptés :

* ``file`` : Nom du fichier XML à traiter (de type `String`).
* ``nbResultValue`` : Défini le x premières valeurs que doit retourner l'interface.
Le reste est aggrégé sous l'unité ``Autres``. L'argument est de type `Interger` et doit
être supérieur ou égale à ``3`` pour être effectif.
* ``data`` : Il s'agit de l'information que l'on souhaite aggrégé depuis l'XML de DTD `ead` au niveau d'un item. Exemple pour aggrégé les occupations, l'argument
vaut ``controlaccess:occupation``

URL pour récupérer les 10 premières occupations du notaire **Claude Royer II** :
``http://localhost/lib/iface/getStats.php?file=FRAN_IR_041106.xml&data=controlaccess:occupation&nbResultValue=10``.

Vous pouvez tester directement sur l'interface en ligne
utilisé pour le Hackathon disponible pour du CORS :

``https://hackhan.neoblaster.fr/lib/iface/getStats.php?file=FRAN_IR_041106.xml&data=controlaccess:occupation&nbResultValue=10``

Resultat : 

````json
[{"label":"commer\u00e7ant","value":88},{"label":"conseiller du roi","value":26},{"label":"avocat au parlement (Ancien R\u00e9gime)","value":22},{"label":"officier (arm\u00e9e)","value":21},{"label":"officier de la maison militaire du roi (Ancien R\u00e9gime)","value":21},{"label":"procureur de justice (Ancien R\u00e9gime)","value":16},{"label":"ma\u00e7on","value":15},{"label":"employ\u00e9 de maison","value":13},{"label":"notaire","value":12},{"label":"jardinier","value":12},{"label":"Autres","value":320}]
````






### Interfaces pour les GeoJSON

L'interface GeoJson sert à obtenir dans un jeu JSON de
deux GeoJSON permettant de mettre des markers sur
une map **Leaflet**.
Le premier jeu de données sert à positionner le notaire.
Le seconde jeu de données sert à positionner les clients du notaire.

N'ayant pas trouver les informations relative
à l'adresse du notaire, l'interface s'appuit
sur le fichier ``site/lib/res/notaries.geojson`` contenant
les coordonnées des 6 notaires du hackathon.
Cette partie doit être automatisée en s'appuyant sur des XML de métadata, si disponible.

Concernant le GeoJSON des clients, ces informations sont directement issues
du fichier XML du producteur et les coordonnées sont obtenues
à partir d'un fichier contenant toutes les rues de paris ``site/lib/res/streetMapCoord.tsv``


#### Prévisualisation en standalone

La projet offre par le biais de la page ``http://localhost/map.php?file=FRAN_IR_041106.xml``,
une interface pour visualiser, tester et maniupler une carte **Leaflet**.
Si l'argument URL ``file`` n'est pas spécifié, c'est le fichier XML ``FRAN_IR_041106.xml``
de **Claude Royer II** qui est utilisé.

Le but est de pouvoir inclure en CORS la carte via l'url via un `iframe`.



#### Obtention des données via "API"

L'interface ``site/lib/iface/getGEOJson.php`` utilisée par la page
``site/map.php`` peu être utilisée pour obtenir le JSON du notaire et de ses clients.
Le seul argument requis via la méthode ``GET`` ou `POST` est `file`
où la valeur doit correspondre à un fichier XML producteur disponible dans le dossier
``site/lib/xml``

Exemple pour **Clause Royer II** : ``https://localhost/lib/iface/getGEOJson.php?file=FRAN_IR_041106.xml``

Resultat obtenu : 

````json
{"notary":{"type":"Feature","geometry":{"type":"Point","coordinates":[2.336852,48.853774]},"properties":{"ETUDE":"I","NOTAIRE":"ROYER Claude II","RUE":"rue de Buci","COMMUNE":"Paris","DATES_EXTREMES":"27 f\u00e9vrier 1778 - 2 juillet 1781","result_label":"Rue de Buci 75006 Paris","result_score":0.94,"result_type":"street","result_id":"75106_1360_ae9a44","result_housenumber":null,"result_name":"Rue de Buci","result_street":"","result_postcode":75006,"result_city":"Paris","result_context":"75, Paris, \u00cele-de-France","result_citycode":75106}},"customers":{"type":"FeatureCollection","geogname":{"found":651,"matches":377},"features":[{"type":"Feature","geometry":{"type":"Point","street":"rue des Saints-P\u00e8res","coordinates":[2.3308624,48.8552578]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Montorgueil","coordinates":[2.3468026,48.8647777]}},{"type":"Feature","geometry":{"type":"Point","street":"rue du Four","coordinates":[2.3329265,48.8526373]}},{"type":"Feature","geometry":{"type":"Point","street":"rue du Cherche-Midi","coordinates":[2.3237847,48.8480932]}},{"type":"Feature","geometry":{"type":"Point","street":"rue de Grenelle","coordinates":[2.3175233,48.8575711]}},{"type":"Feature","geometry":{"type":"Point","street":"rue de Savoie","coordinates":[2.3413467,48.8543359]}},{"type":"Feature","geometry":{"type":"Point","street":"rue de Buci","coordinates":[2.3368208,48.8538084]}},{"type":"Feature","geometry":{"type":"Point","street":"rue des Rosiers","coordinates":[2.3596246,48.8570695]}},{"type":"Feature","geometry":{"type":"Point","street":"rue de la B\u00fbcherie","coordinates":[2.3481285,48.8521422]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Saint-Dominique","coordinates":[2.3114997,48.8599542]}},{"type":"Feature","geometry":{"type":"Point","street":"rue de Seine","coordinates":[2.3369031,48.8545182]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Saint-Beno\u00eet","coordinates":[2.3329687,48.8548095]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Saint-Jacques","coordinates":[2.3426845,48.8455928]}},{"type":"Feature","geometry":{"type":"Point","street":"rue de Beaune","coordinates":[2.3294696,48.8581866]}},{"type":"Feature","geometry":{"type":"Point","street":"rue de Vaugirard","coordinates":[2.3136037,48.843235]}},{"type":"Feature","geometry":{"type":"Point","street":"rue de la Harpe","coordinates":[2.3441796,48.8519568]}},{"type":"Feature","geometry":{"type":"Point","street":"quai Malaquais","coordinates":[2.3340792,48.8580983]}},{"type":"Feature","geometry":{"type":"Point","street":"rue des Prouvaires","coordinates":[2.3443028,48.8617796]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Christine","coordinates":[2.3393123,48.8546892]}},{"type":"Feature","geometry":{"type":"Point","street":"rue du Temple","coordinates":[2.3576336,48.8625974]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Princesse","coordinates":[2.3344961,48.8523123]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Gu\u00e9n\u00e9gaud","coordinates":[2.3385661,48.8557119]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Saint-Antoine","coordinates":[2.3637435,48.8542904]}},{"type":"Feature","geometry":{"type":"Point","street":"rue du Bac","coordinates":[2.3250388,48.8554648]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Saint-Placide","coordinates":[2.3254167,48.8489984]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Dauphine","coordinates":[2.3395981,48.8550261]}},{"type":"Feature","geometry":{"type":"Point","street":"rue de Tournon","coordinates":[2.3371385,48.8509402]}},{"type":"Feature","geometry":{"type":"Point","street":"rue de Varenne","coordinates":[2.3199539,48.854954]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Saint-Denis","coordinates":[2.3500379,48.8637419]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Montmartre","coordinates":[2.3440154,48.8672927]}},{"type":"Feature","geometry":{"type":"Point","street":"rue de Cond\u00e9","coordinates":[2.3380293,48.8505154]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Cassette","coordinates":[2.3308809,48.849596]}},{"type":"Feature","geometry":{"type":"Point","street":"rue de la Verrerie","coordinates":[2.3530064,48.8579752]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Quincampoix","coordinates":[2.3506002,48.8612414]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Guisarde","coordinates":[2.3345467,48.8518944]}},{"type":"Feature","geometry":{"type":"Point","street":"rue du Foin","coordinates":[2.3644268,48.8571058]}},{"type":"Feature","geometry":{"type":"Point","street":"rue des Ciseaux","coordinates":[2.3341238,48.8533627]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Saint-Guillaume","coordinates":[2.3286434,48.8544079]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Poissonni\u00e8re","coordinates":[2.347804,48.8696163]}},{"type":"Feature","geometry":{"type":"Point","street":"rue de la Chaise","coordinates":[2.3271514,48.8531776]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Mazarine","coordinates":[2.3377217,48.8554052]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Michel-Le-Comte","coordinates":[2.3557283,48.8623491]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Saint-Hyacinthe","coordinates":[2.3316461,48.866029]}},{"type":"Feature","geometry":{"type":"Point","street":"rue de Bi\u00e8vre","coordinates":[2.3502492,48.8505639]}},{"type":"Feature","geometry":{"type":"Point","street":"rue du Cygne","coordinates":[2.3497653,48.8631118]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Mouffetard","coordinates":[2.3497802,48.8421919]}},{"type":"Feature","geometry":{"type":"Point","street":"rue des Gravilliers","coordinates":[2.355456,48.8641972]}},{"type":"Feature","geometry":{"type":"Point","street":"rue de la Tacherie","coordinates":[2.3499044,48.8573106]}},{"type":"Feature","geometry":{"type":"Point","street":"rue de Richelieu","coordinates":[2.3378996,48.8676817]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Galande","coordinates":[2.3471753,48.851517]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Rousselet","coordinates":[2.3184226,48.8489074]}},{"type":"Feature","geometry":{"type":"Point","street":"rue F\u00e9rou","coordinates":[2.3340265,48.8496346]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Beaurepaire","coordinates":[2.3637544,48.8702144]}},{"type":"Feature","geometry":{"type":"Point","street":"quai de Bourbon","coordinates":[2.3551833,48.8530564]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Saint-Romain","coordinates":[2.3208674,48.8475318]}},{"type":"Feature","geometry":{"type":"Point","street":"rue des Marmousets","coordinates":[2.3501704,48.8362006]}},{"type":"Feature","geometry":{"type":"Point","street":"rue du Roule","coordinates":[2.3435056,48.8602509]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Saint-Sulpice","coordinates":[2.3364257,48.8513928]}},{"type":"Feature","geometry":{"type":"Point","street":"rue du Jour","coordinates":[2.3446934,48.863752]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Jacob","coordinates":[2.3339586,48.8555201]}},{"type":"Feature","geometry":{"type":"Point","street":"rue de la Ferronnerie","coordinates":[2.3473555,48.8603141]}},{"type":"Feature","geometry":{"type":"Point","street":"rue de la Cerisaie","coordinates":[2.3658666,48.85203]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Saint-Merri","coordinates":[2.3525606,48.8596061]}},{"type":"Feature","geometry":{"type":"Point","street":"rue de Bretagne","coordinates":[2.3620125,48.8631601]}},{"type":"Feature","geometry":{"type":"Point","street":"rue de Babylone","coordinates":[2.3205121,48.8517041]}},{"type":"Feature","geometry":{"type":"Point","street":"rue de Cl\u00e9ry","coordinates":[2.3482527,48.8685929]}},{"type":"Feature","geometry":{"type":"Point","street":"rue du Sabot","coordinates":[2.3315357,48.8527101]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Saint-Victor","coordinates":[2.3508803,48.8483543]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Saint-Laurent","coordinates":[2.3577991,48.875491]}},{"type":"Feature","geometry":{"type":"Point","street":"quai de la Tournelle","coordinates":[2.3535444,48.8504277]}},{"type":"Feature","geometry":{"type":"Point","street":"rue de Poitou","coordinates":[2.3631709,48.8615357]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Pav\u00e9e","coordinates":[2.360806,48.8564587]}},{"type":"Feature","geometry":{"type":"Point","street":"rue des Lombards","coordinates":[2.3488096,48.8595223]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Saint-Martin","coordinates":[2.3522949,48.8628062]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Beaubourg","coordinates":[2.3542903,48.8627337]}},{"type":"Feature","geometry":{"type":"Point","street":"rue de la Monnaie","coordinates":[2.3428837,48.859148]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Saint-Christophe","coordinates":[2.2796464,48.8445493]}},{"type":"Feature","geometry":{"type":"Point","street":"rue de Montmorency","coordinates":[2.3551016,48.8630853]}},{"type":"Feature","geometry":{"type":"Point","street":"rue Hautefeuille","coordinates":[2.3421706,48.8517789]}}]}}
````

Vous pouvez tester directement sur l'interface en ligne
utilisé pour le Hackathon disponible pour du CORS :

``https://hackhan.neoblaster.fr/lib/iface/getGEOJson.php?file=FRAN_IR_041106.xml``
