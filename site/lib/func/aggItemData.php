<?php
/**
 * Agrège les données depuis la fiche producteur.
 *
 * @param SimpleXMLElement $ead
 * @param string           $stack
 * @param string           $data
 * @param string           $attributes
 * @param string           $filter
 */
function aggItemData ($ead, $stack, $dataType, $attributes, $filter)
{
    $series = $ead->archdesc->dsc;

    $aggregates = [];

    // Parcourir les séries
    for ($s = 0; $s < count($series->c); $s++) {
        $serie = $series->c[$s];

        // Parcourir les recordgrps
        for ($r = 0; $r < count($serie->c); $r++) {
            $recordgrp = $serie->c[$r];

            // Parcourir les items
            for ($i = 0; $i < count($recordgrp->c); $i++) {
                $item = $recordgrp->c[$i];

                $dataTypeSXE = $item->$stack->$dataType;

                for ($d = 0; $d < count($dataTypeSXE); $d++) {
                    $data = $dataTypeSXE[$d];
                    $dataStr = strval($data);

                    if (!array_key_exists($dataStr, $aggregates)) $aggregates[$dataStr] = 0;
                    $aggregates[$dataStr]++;
                }
            }
        }
    }

    return $aggregates;
}
