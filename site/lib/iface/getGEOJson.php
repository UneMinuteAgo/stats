<?php
//echo "<pre>";
$root = $_SERVER['DOCUMENT_ROOT'];

// Indexation des coordonnées de rues
$streetMapCoordFile = fopen("$root/lib/res/streetMapCoord.tsv", "r");
$streets = [];

$first = true;
while ($buffer = fgets($streetMapCoordFile)) {
    // Ignorer les Headers
    if ($first) {
        $first = false;
        continue;
    }

    // Récupération des données
    $street = null;
    $lat = null;
    $lng = null;

    list($street, $lng, $lat) = preg_split("/\t/", $buffer);

    $street = strtolower($street);

    $streets[$street] = [
        "street" => $street,
        "lat" => $lat,
        "lng" => trim($lng)
    ];
}
fclose($streetMapCoordFile);



// Lecture de l'XML
$srcFile = @$_REQUEST['file'];
//$srcFile = 'FRAN_IR_042380.xml';
$ead = new SimpleXMLElement(file_get_contents("$root/lib/xml/$srcFile"));
$notaryName = strval($ead->archdesc->did->origination->persname);
list($notaryLastName, $notaryFirstName) = preg_split("/,/", $notaryName);
$notaryName = strtoupper(trim($notaryLastName)). " ". trim($notaryFirstName);
$series = $ead->archdesc->dsc;
$features = [];
$streetsUsed = [];

// Validation
$geognameFound = 0;
$geognameMatch = 0;

// Parcourir les séries
for ($s = 0; $s < count($series->c); $s++) {
    $serie = $series->c[$s];

    // Parcourir les recordgrps
    for ($r = 0; $r < count($serie->c); $r++) {
        $recordgrp = $serie->c[$r];

        // Parcourir les items
        for ($i = 0; $i < count($recordgrp->c); $i++) {
            $item = $recordgrp->c[$i];

            $geognames = $item->controlaccess->geogname;

            for ($d = 0; $d < count($geognames); $d++) {
                $geogname = $geognames[$d];

                if (strval($geogname->attributes()->source) !== 'FRAN_RI_025') continue;

                $geognameFound++;

                $street = strval($geogname);
                $street = preg_replace("/^(.+)\s+\((.+)\)/", "$2 $1", $street);
                $street = preg_replace("/'\s/", " ", $street);
                $streetLower = strtolower($street);

                if (array_key_exists($streetLower, $streets)) {
                    $geognameMatch++;

                    if (in_array($streetLower, $streetsUsed)) continue;

                    $streetsUsed[] = $streetLower;

                    $features[] = [
                        "type" => "Feature",
                        "geometry" => [
                            "type" => "Point",
                            "street" => $street,
                            "coordinates" => [
                                floatval($streets[$streetLower]['lat']),
                                floatval($streets[$streetLower]['lng']),
                            ]
                        ]
                    ];
                }
            }
        }
    }
}

$geojsonCustomers = [
    "type" => "FeatureCollection",
    "geogname" => [
        "found" => $geognameFound,
        "matches" => $geognameMatch
    ],
    "features" => $features
];

// Récupération du geojson du notaire
$notaries = json_decode(file_get_contents("$root/lib/res/notaries.geojson"), true);
$notary = [];

for ($n = 0; $n < count($notaries['features']); $n++) {
    $notary = $notaries['features'][$n];

    $notary['properties']['NOTAIRE'];

    if ($notary['properties']['NOTAIRE'] === $notaryName) {
        break;
    }
}

$json = [
    "notary" => $notary,
    "customers" => $geojsonCustomers
];


//print_r($json);

// Allow Cross Origin
header('Access-Control-Allow-Origin: *');

// Génération du jeu de donnée JSON
echo json_encode($json);


