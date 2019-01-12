<?php

use Template\Template;

$root = $_SERVER['DOCUMENT_ROOT'];

require_once $root . '/lib/vendor/autoload.php';

$xml = $_POST['file'];
$ead = new SimpleXMLElement(file_get_contents($root . "/lib/xml/$xml"));

// Minute     MC/ET : /ead/archdesc/dsc/<series>/<recordgrp>/<item>
// Repertoire MC/RE :

$series = $ead->archdesc->dsc;

$occupations = [];

for ($s = 0; $s < count($series->c); $s++) {
    $serie = $series->c[$s];

    for ($r = 0; $r < count($serie->c); $r++) {
        $recordgrp = $serie->c[$r];

        for ($i = 0; $i < count($recordgrp->c); $i++) {
            $item = $recordgrp->c[$i];

            for ($o = 0; $o < count($item->controlaccess->occupation); $o++) {
                $occupation = $item->controlaccess->occupation[$o];
                $occupationName = strval($occupation);
                if (!array_key_exists($occupationName, $occupations)) $occupations[$occupationName] = 0;
                $occupations[$occupationName]++;
            }
        }
    }
}

// Triage plus grosse valeur à la plus faible
arsort($occupations);

$others = array_slice($occupations, 9);
$otherAgg = 0;

foreach ($others as $key => $val) {
    $otherAgg += $val;
}

$final = array_slice($occupations,0, 9);
$final['Autres'] = $otherAgg;

// Formatage des donnée pour le moteur
$json = [];
foreach ($final as $occupation => $value) {
    $json[] = [
        "COMMA" => ",",
        "OCCUPATION" => $occupation,
        "VALUE" => $value
    ];
}

$json[0]['COMMA'] = '';

$template = new Template();
$template->set_template_file($root . '/lib/templates/occupations.json.tpl');
$template->set_output_name('occupations.json');
$template->set_vars_delim('{{');
$template->set_vars(["JSON" => $json]);
//$template->render()->display();
echo $template::strip_blank($template->render()->get_render_content());






