<?php
/** @return array Список подключаемых классов */

$classes = array();
/* Модели */
$models = array("CModel");
foreach ($models as $m) {
    $classes[] = "models/".$m;
}

/* Хелперы */
$helpers = array(
    "CAdminHelper",
);
foreach ($helpers as $m) {
    $classes[] = "helpers/".$m;
}

return $classes;