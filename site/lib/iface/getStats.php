<?php

$root = $_SERVER['DOCUMENT_ROOT'];

require_once $root . "/lib/func/aggItemData.php";

$srcFile = $_POST['file'];
$nbResultValues = intval($_POST['nbResultValue']);
list($stack, $dataType) =  preg_split("/:/", $_POST['data']);


// Ajustement des paramètres
$nbResultValues = ($nbResultValues < 3) ? 0 : $nbResultValues;

$ead = new SimpleXMLElement(file_get_contents($root . "/lib/xml/$srcFile"));

$aggragtes = aggItemData($ead, $stack, $dataType, '', '');


// Effectuer le triage des données
arsort($aggragtes);


// Opérer la réduction des résultats à un nombre restreint de valeur
// si applicable
if ($nbResultValues >= 3) {
    $revelantAgg = array_slice($aggragtes, 0, $nbResultValues);
    $restAgg = array_slice($aggragtes, $nbResultValues);

    $restAggValue = 0;

    foreach ($restAgg as $key => $value ) {
        $restAggValue += $value;
    }

    $aggragtes = $revelantAgg;
    $aggragtes["Autres"] = $restAggValue;
}


// Formatage des données pour le rendre en JSON
$JSONData = [];
foreach ($aggragtes as $label => $value) {
    $JSONData[] = [
        "label" => $label,
        "value" => $value
    ];
}
$JSONData[0]['COMMA'] = '';


// Génération du jeu de donnée JSON
echo json_encode($JSONData);


