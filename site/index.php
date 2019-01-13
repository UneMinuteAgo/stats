<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>D3.js Graphs Dynamic</title>
    <script src="https://d3js.org/d3.v5.min.js"></script>
    <script src="lib/vendor/neooblaster/xhrQuery/src/xhrQuery.js" onerror="alert('xhrQuery not available. Please check dependancies with \'composer update\'')"></script>
    <script src="lib/js/Pie.js"></script>
</head>
<body>

<ul>
    <li><label for="width">Largeur (Px) :</label><input id="width" type="number" value="400"/></li>
    <li><label for="height">Hauteur (Px) :</label><input id="height" type="number" value="400"/></li>
    <li><label for="arcIn">Début de l'arc [0,1] :</label><input id="arcIn" type="number" value="0.60"/></li>
    <li><label for="arcOut">Fin de l'arc [0,1] :</label><input id="arcOut" type="number" value="0.95"/></li>
    <li><label for="arcPad">Espace entre valeur :</label><input id="arcPad" type="number" value="0.02"/></li>
</ul>

<input type="button" onclick="applySettings();" value="Appliquer"/>

<br />
<select name="" id="xmlFile">
    <?php
    $folder = "lib/xml";
    $files = scandir($folder);

    for ($f = 0; $f < count($files); $f++) {
        $file = $files[$f];
        if (is_dir("$folder/$file")) continue;
        echo "<option value='$file'>$file</option>";
    }
    ?>
</select>
<input type="button" value="Charger Données" onclick="PIE.load();"/>

<pre>
</pre>

</body>
</html>