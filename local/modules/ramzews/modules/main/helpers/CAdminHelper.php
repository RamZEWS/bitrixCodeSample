<?php
class CAdminHelper {
    /**
     * Build fields for admin
     * @param array $fields
     * @param array $currentValue
     * @return string
     */
    public static function createFormFields($fields, $currentValue = array()){
        $html = array();
        foreach($fields as $field => $info){
            if($info['type'] != "hidden") {
                $html[] = "<tr>";
                $html[] = "<td>".$info['name']."</td>";
                $html[] = "<td>";
            }
            switch($info['type']){
                case "select":
                    $html[] = '<select name="'.$field.'">';
                    if(isset($info["no_value"]) && $info["no_value"]) {
                        $html[] = '<option value="">Не выбрано</option>';
                    }
                    foreach($info['values'] as $k => $v) {
                        $selected = $k == $currentValue[$field] ? "selected" : "" ;
                        $html[] = '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
                    }
                    $html[] = '</select>';
                    break;
                case "boolean":
                    $html[] = '<select name="'.$field.'">';
                    foreach(array('' => "Не выбрано", 0 => "Нет", 1 => "Да") as $k => $v) {
                        $selected = $currentValue ? ($k == $currentValue[$field] ? "selected" : "") : "";
                        $html[] = '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
                    }
                    $html[] = '</select>';
                    break;
                case "checkbox":
                    $html[] = '<input class="typeinput" type="checkbox" name="'.$field.'" '.($currentValue[$field] ? "checked" : "").' />';
                    break;
                case "textarea":
                    $html[] = '<textarea name="'.$field.'" style="width: 500px; height: 200px;">'.$currentValue[$field].'</textarea>';
                    break;
                case 'date':
                case 'datetime':
                    if(isset($info['range']) && $info['range']) {
                        $html[] = '<div class="adm-input-wrap adm-input-wrap-calendar">
                                           <input type="text" value="'.$currentValue[$field."_START"].'" size="22" name="'.$field.'_START" class="adm-input adm-input-calendar">
                                           <span onclick="BX.calendar({node:this, field:\''.$field.'_START\', form: \'\', bTime: true, bHideTime: false});" title="Нажмите для выбора даты" class="adm-calendar-icon"></span>
                                  </div>';
                        $html[] = '<div class="adm-input-wrap adm-input-wrap-calendar">
                                           <input type="text" value="'.$currentValue[$field."_END"].'" size="22" name="'.$field.'_END" class="adm-input adm-input-calendar">
                                           <span onclick="BX.calendar({node:this, field:\''.$field.'_END\', form: \'\', bTime: true, bHideTime: false});" title="Нажмите для выбора даты" class="adm-calendar-icon"></span>
                                  </div>';
                    } else {
                        $html[] = '<div class="adm-input-wrap adm-input-wrap-calendar">
                                           <input type="text" value="'.$currentValue[$field].'" size="22" name="'.$field.'" class="adm-input adm-input-calendar">
                                           <span onclick="BX.calendar({node:this, field:\''.$field.'\', form: \'\', bTime: true, bHideTime: false});" title="Нажмите для выбора даты" class="adm-calendar-icon"></span>
                                  </div>';
                    }
                    break;
                case "string":
                case "text":
                case "int":
                    if(isset($info['range']) && $info['range']) {
                        $html[] = '<input class="typeinput" type="text" name="'.$field.'_START" value="'.$currentValue[$field."_START"].'" />';
                        $html[] = '<input class="typeinput" type="text" name="'.$field.'_END" value="'.$currentValue[$field."_END"].'" />';
                    } else {
                        $html[] = '<input class="typeinput" type="text" name="'.$field.'" value="'.$currentValue[$field].'" />';
                    }
                    break;
                case "file":
                    $fileHtml = false;
                    if(isset($currentValue[$field])) {
                        $file = CFile::GetPath($currentValue[$field]);
                        if($file) {
                            $img = getimagesize($_SERVER["DOCUMENT_ROOT"].$file);
                            if($img) {
                                $html[] = '<div style="margin-bottom:10px;"><a href="'.$file.'" target="_blank"><img src="'.$file.'" style="max-width: 200px;"/></a></div>';
                            } else {
                                $html[] = '<div style="margin-bottom:10px;"><a href="'.$file.'" target="_blank">'.$file.'</a></div>';
                            }
                            $fileHtml = '<div style="margin-bottom:10px;margin-top:10px;"><input type="checkbox" name="DELETE_FILE_'.$field.'"/><label style="margin-left:5px;">Удалить файл</label></div>';
                        }
                    }
                    $isMulti = (isset($info["multiload"]) && $info["multiload"]);
                    $name = $isMulti ? $field."[]" : $field;
                    $html[] = '<input class="typeinput" type="'.$info['type'].'" name="'.$name.'" '.($isMulti ? 'multiple="multiple"' : "").'/>';
                    if($fileHtml) $html[] = $fileHtml;
                    break;

                /* JS Color needs */
                case "color":
                    $default = isset($info["default"]) ? $info["default"] : false;
                    if(!$currentValue[$field]) $currentValue[$field] = $default;
                    $html[] = '<input class="jscolor typeinput" name="'.$field.'" value="'.$currentValue[$field].'">';
                    break;

                /* Hidden */
                case "hidden":
                    $html[] = '<input name="'.$field.'" value="'.$currentValue[$field].'" type="hidden">';
                    break;
            }
            if($info['type'] != "hidden") {
                $html[] = "</td></tr>";
            }
        }
        return implode("", $html);
    }

    /**
     * Get filter values
     * @param array $fInfo
     * @param array $from
     * @return array
     */
    public static function GetFilterValues($fInfo, $from = null){
        $result = array();
        $from = $from ?: $_REQUEST;
        foreach($fInfo as $key => $info){
            $value = isset($from[$key]) ? $from[$key] : null;
            if(isset($info["alias"])) {
                $value = $from[$info["alias"]];
            }
            switch($info['type']){
                case "int":
                    if(isset($info['range']) && $info['range']) {
                        if(isset($from[$key."_START"]) && $from[$key."_START"]) {
                            $result[">=".$key] = intval($from[$key."_START"]);
                        }
                        if(isset($from[$key."_END"]) && $from[$key."_END"]) {
                            $result["<=".$key] = intval($from[$key."_END"]);
                        }
                    } else {
                        if($value) $result[$key] = intval($value);
                    }
                    break;
                case "boolean":
                    if(!is_null($value)) {
                        $result[$key] = $value ? 1 : 0;
                    }
                    break;
                case 'date':
                case 'datetime':
                    $format = $info['type'] == "datetime" ? "Y-m-d H:i:s" : "Y-m-d";
                    if(isset($info['range']) && $info['range']) {
                        if(isset($from[$key."_START"]) && $from[$key."_START"]) {
                            $result[">=".$key] = new \Bitrix\Main\Type\DateTime(date($format, strtotime($from[$key."_START"])), $format);
                        }
                        if(isset($from[$key."_END"]) && $from[$key."_END"]) {
                            $result["<=".$key] = new \Bitrix\Main\Type\DateTime(date($format, strtotime($from[$key."_END"])), $format);
                        }
                    } else {
                        if($value) {
                            $result["%".$key] = new Bitrix\Main\Type\DateTime(date($format, strtotime($value)), $format);
                        }
                    }
                    break;
                case "string":
                case "text":
                    if($value) $result["%".$key] = $value;
                    break;
                default:
                    if($value) $result[$key] = $value;
                    break;
            }
        }
        return $result;
    }

    /**
     * Check string for url
     * @param string $string
     * @return boolean
     */
    public static function isUrl($string){
        return filter_var($string, FILTER_VALIDATE_URL);
    }

    /**
     * Create list for admin
     * @global CMain $APPLICATION
     * @param string $model
     * @param array $filter
     * @param string $addUrl
     * @param string $editUrl
     * @param string $nameField
     * @param string $nameType
     * @param array $showHeader
     * @param array $sort
     * @return \CAdminList
     */
    public static function CreateList($model, $filter = array(), $addUrl = "", $editUrl = "", $nameField = "NAME", $nameType = "text", $showHeader = array(), $sort = array("by" => "ID", "order" => "ASC"), $needEdit = null, $formAction = "", $replaceArr = null) {
        global $APPLICATION;
        $sTableID = $model::getTableName();
        $sort = array(
            "by" => isset($sort["by"]) ? strtoupper($sort["by"]) : "ID",
            "order" => isset($sort["order"]) ? strtoupper($sort["order"]) : "DESC",
        );
        $oSort = new CAdminSorting($sTableID, $sort['by'], $sort['order']);
        $lAdmin = new CAdminList($sTableID, $oSort);

        $rsData = $model::GetAllRs($filter, array($sort['by'] => $sort['order']));
        $rsData = new CAdminResult($rsData, $sTableID);
        $needHeader = array_merge(array("ID", $nameField), $showHeader);
        $header = $model::getAdminListHeader($needHeader, false);
        $lAdmin->AddHeaders($header);

        $curPage = $APPLICATION->GetCurPage();
        $request = $_GET;

        $existActiveField = false;
        while($arRes = $rsData->Fetch()) {
                $row = &$lAdmin->AddRow($arRes['ID'], $arRes);
                $eUrl = str_replace("#ID#", $arRes["ID"], $editUrl);

                $arActions = array(
                    array(
                        "ICON" => "edit",
                        "TEXT" => "Редактировать",
                        "ACTION" => $lAdmin->ActionRedirect($eUrl)
                    )
                );

                if($needEdit) {
                    $formAction = $formAction ?: ($curPage."?".http_build_query($request));
                    $script = "$('<form/>').attr('action', '".$formAction."').attr('method','POST').attr('enctype', 'multipart/form-data').attr('id', '".(strtolower($model)."_".$arRes["ID"])."').hide().appendTo($('body'));";
                    foreach($needEdit as $f => $type){
                        $html = self::CreateListEditField($f, $type, $arRes[$f], strtolower($model)."_".$arRes["ID"]);
                        $row->AddViewField($f, $html);
                        $script .= "var cl = $('.".(strtolower($model)."_".$arRes["ID"])."[name=".$f."]').clone(); cl.appendTo($('#".(strtolower($model)."_".$arRes["ID"])."'));";
                    }
                    $script .= "$('<input/>').attr('type', 'hidden').attr('name', '".(strtolower($model)."_save")."').val('".$arRes["ID"]."').appendTo($('#".(strtolower($model)."_".$arRes["ID"])."'));";
                    $script .= "$('#".(strtolower($model)."_".$arRes["ID"])."').submit();";
                    $request[strtolower($model)."_save"] = $arRes["ID"];
                    $arActions[] = array(
                        "ICON" => "edit",
                        "TEXT" => "Сохранить",
                        "ACTION" => $script
                    );
                    unset($request[strtolower($model)."_save"]);
                } else {
                    if(isset($arRes[$nameField])) {
                        switch($nameType) {
                            case "image":
                                $f = CFile::ResizeImageGet($arRes[$nameField], array("width" => 100, "height" => 100), BX_RESIZE_IMAGE_PROPORTIONAL);
                                $row->AddViewField($nameField, '<a href="'.$eUrl.'"><img class="view-img" src="'.$f['src'].'"/></a>');
                                break;
                            default:
                                $row->AddViewField($nameField, "<a href='".$eUrl."'>".$arRes[$nameField]."</a>");
                                break;
                        }
                    }
                }

                if(isset($arRes["ACTIVE"])) {
                    $existActiveField = true;
                    $row->AddViewField("ACTIVE", $arRes["ACTIVE"] ? "Да" : "Нет");
                    $request[strtolower($model)."_change_active"] = $arRes["ID"];
                    $arActions[] = array(
                        "ICON" => "edit",
                        "TEXT" => $arRes["ACTIVE"] ? "Деактивировать" : "Активировать",
                        "ACTION" => $lAdmin->ActionRedirect($curPage."?".http_build_query($request))
                    );
                    unset($request[strtolower($model)."_change_active"]);
                }

                if($replaceArr) {
                    foreach($replaceArr as $rKey => $rField){
                        $row->AddViewField($rKey, $arRes[$rField]);
                    }
                }

                $request[strtolower($model)."_delete"] = $arRes["ID"];
                $arActions[] = array(
                    "ICON" => "delete",
                    "TEXT" => "Удалить",
                    "ACTION" => "if(confirm('Удалить?')) ".$lAdmin->ActionRedirect($curPage."?".http_build_query($request))
                );
                unset($request[strtolower($model)."_delete"]);


                $row->AddActions($arActions);
        }
        if($lAdmin->aRows) {
            /* Добавляем групповые действия */
            $groupActions = array(
                strtolower($model)."_delete" => array(
                    "action" => "$('<input/>').attr('type', 'hidden').attr('name', 'action').attr('value','".(strtolower($model)."_delete")."').appendTo(this.form);this.form.submit();",
                    "type" => "button",
                    "value" => strtolower($model)."_delete",
                    "name" => "Удалить"
                )
            );

            if($existActiveField) {
                $groupActions[strtolower($model)."_active"] = array(
                    "action" => "$('<input/>').attr('type', 'hidden').attr('name', 'action').attr('value','".(strtolower($model)."_active")."').appendTo(this.form);this.form.submit();",
                    "type" => "button",
                    "value" => strtolower($model)."_active",
                    "name" => "Активировать"
                );
                $groupActions[strtolower($model)."_inactive"] = array(
                    "action" => "$('<input/>').attr('type', 'hidden').attr('name', 'action').attr('value','".(strtolower($model)."_inactive")."').appendTo(this.form);this.form.submit();",
                    "type" => "button",
                    "value" => strtolower($model)."_inactive",
                    "name" => "Деактивировать"
                );
            }
            $lAdmin->AddGroupActionTable($groupActions, array("disable_action_target" => true));
        }

        $lAdmin->AddAdminContextMenu(array(
            array(
                "TEXT" => "Добавить",
                "LINK" => $addUrl,
                "ICON" => "btn_new",
            ),
        ));
        $lAdmin->CheckListMode();
        $lAdmin->context->additional_items = array();

        return $lAdmin;
    }

    public static function CheckDeleteAction($model) {
        if(isset($_REQUEST["action"]) && $_REQUEST["action"] == strtolower($model)."_delete") {
            $ids = isset($_REQUEST["ID"]) ? $_REQUEST["ID"] : false;
            if($ids) {
                $model::Delete(array("ID" => $ids));
            }
        }
    }

    public static function CheckActiveAction($model) {
        if(isset($_REQUEST["action"]) && $_REQUEST["action"] == strtolower($model)."_active") {
            $ids = isset($_REQUEST["ID"]) ? $_REQUEST["ID"] : false;
            if($ids) {
                $model::Update(array("ID" => $ids), array("ACTIVE" => 1));
            }
        } else if(isset($_REQUEST["action"]) && $_REQUEST["action"] == strtolower($model)."_inactive"){
            $ids = isset($_REQUEST["ID"]) ? $_REQUEST["ID"] : false;
            if($ids) {
                $model::Update(array("ID" => $ids), array("ACTIVE" => 0));
            }
        }
    }

    public static function CheckEditAction($model) {
        if(isset($_REQUEST[strtolower($model)."_save"]) && $id = intval($_REQUEST[strtolower($model)."_save"])) {
            $arFields = $model::prepareFields($_POST);
            $map = $model::getMap();
            if(isset($map["ACTIVE"])) {
                $arFields["ACTIVE"] = intval(isset($_POST["ACTIVE"]));
            }
            if(isset($map["PICTURE_ID"])) {
                if($fID = $model::UploadFile("PICTURE_ID", true)) {
                    $arFields["PICTURE_ID"] = $fID;
                }
            }

            $model::Update($id, $arFields);
        }
    }

    public static function CreateListEditField($key, $type, $value = "", $randKey = false){
        if(!$randKey) $randKey = randString(5);
        $html = array();
        switch($type){
            case "text":
            case "textarea":
                $html[] = '<input class="typeinput '.$randKey.'" type="text" name="'.$key.'" value="'.$value.'" />';
                break;
                //$html[] = '<textarea name="'.$key.'">'.$value.'</textarea>';
                //break;
            case "image":
                if(isset($value)) {
                    $file = CFile::GetPath($value);
                    if($file) {
                        $img = getimagesize($_SERVER["DOCUMENT_ROOT"].$file);
                        if($img) {
                            $html[] = '<div style="margin-bottom:10px;"><a href="'.$file.'" target="_blank"><img src="'.$file.'" style="max-width: 200px;"/></a></div>';
                        } else {
                            $html[] = '<div style="margin-bottom:10px;"><a href="'.$file.'" target="_blank">'.$file.'</a></div>';
                        }
                    }
                }
                $html[] = '<input class="typeinput '.$randKey.'" type="file" name="'.$key.'" />';
                break;
            case "checkbox":
                $html[] = '<input class="typeinput '.$randKey.'" type="checkbox" name="'.$key.'" '.($value ? "checked" : "").' />';
                break;
        }
        return implode("", $html);
    }
}