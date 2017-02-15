<?php
/** @return array Список подключаемых классов */

$classes = array();
/* Модели */
$models = array(
    "CFileTable",
);
foreach ($models as $m) {
    $classes[] = "models/".$m;
}

return $classes;