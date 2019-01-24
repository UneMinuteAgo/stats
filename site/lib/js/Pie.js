//@TODO : voir pour un system de selection d'un existant - gestion de nom - bind - jeu de donnée

function Pie(){
    var self = this;

    self._arcInX = 0.67;
    self._arcOutX = 1;
    self._arcPad = 0.005;
    self._width = 100;
    self._height = 100;
    self._replace = false;
    self._host = null;
    self._default = {
        mapFn: function(d) {
            return d.value;
        }
    };

    /**
     * Callback pour le remplissage du pie chart.
     */
    self.arc = function() {
        var radius = Math.min(self._width, self._height) / 2;
        return d3.arc().innerRadius(radius * self._arcInX).outerRadius(radius * self._arcOutX);
    };

    /**
     * Défini le point de départ de l'arc représentant une valeur.
     *
     * @param   {number} arcIn  Valeur comprise entre 0 et 1.
     * @returns {Pie}
     */
    self.arcIn = function(arcIn) {
        arcIn = parseFloat(arcIn);

        if (isNaN(arcIn)) {
            arcIn = 0;
        }

        self._arcInX = arcIn;
        return self;
    };

    /**
     * Défini le point de d'arrêt de l'arc représentant une valeur.
     *
     * @param   {number} arcOut  Valeur comprise entre 0 et 1.
     * @returns {Pie}
     */
    self.arcOut = function(arcOut) {
        arcOut = parseFloat(arcOut);

        if (isNaN(arcOut)) {
            arcOut = 1;
        }
        self._arcOutX = arcOut;
        return self;
    };

    /**
     * Défini le paramétrage permettant la construction des arcs représentant une valeur.
     */
    self.arcShape = function(arcIn, arcOut, padding) {
        self.arcIn(arcIn);
        self.arcOut(arcOut);
        self.padding(padding);

        return self;
    };

    /**
     * Construit à l'endroit attendu le graphique. N'inclus pas la génération de celui-ci.
     */
    self.build = function() {
        var SVG = d3.create('svg').attr('width', self._width).attr('height', self._height).node();

        if (self._host) {
            if (self._replace) {
                self._host.parentNode.replaceChild(SVG, self._host);
            } else {
                self._host.appendChild(SVG);
            }
        }
    };

    /**
     * Génère une couleur à partir d'une chaine.
     *
     * @param   data
     * @returns {Pie}
     */
    self.color = function(data) {

        return d3.scaleOrdinal().domain(
            data.map(function(el) {
                return el.label
            })
        ).range(
            d3.quantize(function(t){
                return d3.interpolateSpectral(t * 0.8 + 0.1);
            }, data.length)//.reverse()
        );

        // d3.interpolateSpectral(x)
        //  x = [0,1]
        //  return rgb(x,y,z)

        // d3.quantize() je pense qu'il traduit la couleur RGB sur une échelle de 0 à 1

        // d3.scaleOrdinal().range() applique les valeurs quantizées aux valeurs enregistrées dans domain

        // En gros,
        //  - On créer une echelle ordonal de couleur
        //  - On spécifie le jeu de donnée à qui il faut attaché la couleur
        //  - On attache la couleur à l'aide de range
        //  - La couleur est génerer et quantifié par jeu de donnée disponible
    };

    /**
     * Génère le graphique à l'aide du jeu de donnée.
     *
     * @param   {object}   data   Jeu de donnée à traiter.
     * @param   {function} mapFn  Function de mapping de la valeur.
     * @returns {Pie}
     */
    self.make = function(data, mapFn) {
        // Sécurisation des arguments
        if (!(typeof mapFn) === 'function') {
            mapFn = self._default.mapFn;
        }

        // Création de l'enveloppe du graphique
        self.build();

        // Création du moteur de dispatch des données en Pie
        var pie = d3.pie()
            .padAngle(self._arcPad) // Angle de séparation des différentes valeur
            .sort(null)             // Default : X > x
            .value(mapFn);          // Function pour trouver la valeur utile

        // Répartition des données en part représentative en fonction de leur valeur utile
        var arcs = pie(data);

        // Generation des couleurs
        var color = self.color(data);

        // Production du graph
        //-- Selectionner le SVG cible
        var svg = d3.select("svg:last-child");

        //-- Création des portions de valeurs
        var g = svg.append('g');
        // Déplacement du groupe au centre
        g.attr("transform", function() {
            var hzCenter = self._width / 2;
            var vCenter = self._height / 2;

            return "translate(" + hzCenter + "," + vCenter + ")";
        })
            .attr("text-anchor", "middle")
            .style("font", "0.8rem sans-serif");

        // Prévoir de selectionner tous les éléments que nous allons construire
        g.selectAll("path")
        // Envoie des valeures dispatchées
            .data(arcs)
            // Entrer dans le jeu de donnée et processer
            .enter()
            // Créer un element SVG path pour chaque valeur
            .append("path")
            // Définir la couleur de remplissage
            .attr('fill', function(d){
                return color(d.data.label)
            })
            // Définir la valeur de D pour la shape PATH (cf SVG)
            .attr('d', self.arc())
            // Introduction d'un titre
            .append('title').text(function(d) {
            return d.data.label;
        });

        //-- Création des labels
        g.selectAll("text")
        // Toujours s'appuyer sur le jeu de donnée générer pour Pie
            .data(arcs)
            // Création d'un element text
            .enter()
            .append("text")
            // Centrer le texte
            .attr("transform", function(d) {
                var center = self.arc().centroid(d);

                return "translate(" + center + ")";
            })
            //
            //.attr("dy", "0.35em");
            .append("tspan")
            //.attr("x", "-0.5rem")
            .attr("y", "-0.2rem")
            .style("font-weight", "bold")
            .style("font-size", "0.75rem")
            .style("font-weight", "bold")
            .text(function(d){
                return d.data.label;
            });

        g.selectAll("text").filter(function(d) {
            // console.log(d, d.endAngle - d.startAngle);
            return ((d.endAngle - d.startAngle) > 0.25);
        })
            .append("tspan")
            .attr("x", 0)
            .attr("y", "0.6rem")
            .text(function(d) {
                return d.data.value.toLocaleString();
            });
    };

    /**
     * Défini l'élement cible (ou son selecteur) devant être remplacer par le nouveau graphique.
     *
     * @param   {String|HTMLElement} replace Element Cible pour une opération replaceChild().
     * @returns {Pie}
     */
    self.replace = function(replace) {
        if ((typeof replace) !== 'object') {
            replace = document.querySelector(replace);
        }

        if (replace) {
            self._host = replace;
            self._replace = true;
        }

        return self;
    };

    /**
     * Défini l'élement cible (ou son selecteur) devant acceuillir le graphique.
     *
     * @param   {String|HTMLElement} target Element Cible pour une opération appendChild().
     * @returns {Pie}
     */
    self.target = function(target) {
        if ((typeof target) !== 'object') {
            target = document.querySelector(target);
        }

        if (target) {
            self._host = target;
            self._replace = false;
        }

        return self;
    };

    /**
     * Défini la largeur en pixel du graphique.
     *
     * @param   {number} width  Largeur en pixel du graphique.
     * @returns {Pie}
     */
    self.width = function(width) {
        self._width = parseInt(width);
        if (isNaN(self._width)) {
            self._width = 100;
        }

        return self;
    };

    /**
     * Défini la hauteur en pixel du graphique.
     *
     * @param   {number} height  hauteur en pixel du graphique.
     * @returns {Pie}
     */
    self.height = function(height) {
        self._height = parseInt(height);
        if (isNaN(self._height)) {
            self._height = 100;
        }
        return self;
    };

    self.padding = function(padding) {
        //@TODO: controle de l'argument
        self._arcPad = padding;

        return self;
    };

    /**
     * Défini les dimension largeur, hauteur du graphique en pixel.
     *
     * @param   {number} width  Largeur en pixel du graphique.
     * @param   {number} height  hauteur en pixel du graphique.
     * @returns {Pie}
     */
    self.size = function(width, height) {
        self.width(width);
        self.height(height);
        return self;
    };

    self.load = function() {
        var select = document.querySelector('#xmlFile');

        document.querySelectorAll('svg').forEach(function(el) {
            el.parentNode.removeChild(el);
        });

        new xhrQuery().target("lib/iface/getStats.php").values(
            "file=" + select.value,
            "data=controlaccess:occupation",
            "nbResultValue=" + 10
        ).callbacks(function(e) {
            // console.log(e);
            self.target(document.body).make(JSON.parse(e), function(d){return d.value;});
        }).send();

        new xhrQuery().target("lib/iface/getStats.php").values(
            "file=" + select.value,
            "data=controlaccess:genreform",
            "nbResultValue=" + 10
        ).callbacks(function(e) {
            self.target(document.body).make(JSON.parse(e), function(d){return d.value;});
        }).send();
    };

    return self;
}

function applySettings() {
    var svg = document.querySelector('svg');
    if (svg) {
        svg.parentNode.removeChild(svg);
    }

    var width = document.querySelector("#width").value;
    var height = document.querySelector("#height").value;
    var arcIn = document.querySelector("#arcIn").value;
    var arcOut = document.querySelector("#arcOut").value;
    var arcPad = document.querySelector("#arcPad").value;

    PIE.size(width, height).arcShape(arcIn, arcOut, arcPad).load();
}

var PIE = new Pie();