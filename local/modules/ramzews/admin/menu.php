<?
$module_menu = array(
    array(
        "text" => "Контакты",
        "url"  => "rm_contact_list.php",
        "more_url" => array("rm_contact_edit.php"),
        "icon" => "form_menu_icon",
        "page_icon" => "form_page_icon",
    ),
);

$aMenu = array(
    "parent_menu" => "global_menu_content", // поместим в раздел "Контент"
    "sort"        => 10,                    // вес пункта меню
    "url"         => "",  // ссылка на пункте меню
    "text"        => "Администрирование",       // текст пункта меню
    "title"       => "Администрирование", // текст всплывающей подсказки
    "icon"        => "form_menu_icon", // малая иконка
    "page_icon"   => "form_page_icon", // большая иконка
    "items_id"    => "menu_rm_media",  // идентификатор ветви
    "items"       => $module_menu,          // остальные уровни меню сформируем ниже.
);

return $aMenu;
?>