<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>

<div class="container newspage">
    <?
    $APPLICATION->IncludeComponent("ramzews:breadcrumbs", "", array(
        "BREADCRUMBS" => array(
            "/project/" => GetMessage("CNT_TITLE"),
        )
    ));
    ?>

    <div class="col-xs-12 nospace ">
        <div >
            <h1><?= GetMessage("CNT_TITLE"); ?></h1>
        </div>
    </div>

    <div class="col-xs-12 nospace">
        <div class="row contacts">
            <?
            if($arResult["CONTACTS"]) {
                foreach($arResult["CONTACTS"] as $c) {
                    $n = implode(" ", array($c["LAST_NAME"], $c["FIRST_NAME"], $c["MIDDLE_NAME"]));
                    ?>
                    <div class="col-sm-6 col-md-6 col-xs-12 cont_profile" style="min-height:240px;">
                        <?
                        $img = false;
                        if($c["PHOTO_ID"]) {
                            $file = CFile::ResizeImageGet($c["PHOTO_ID"] , array("width" => 81, "height" => 81), BX_RESIZE_IMAGE_EXACT);
                            $img = $file["src"];
                        }
                        if($img) {
                        ?>
                            <div class="contact_pic"><img src="<?= $img; ?>"></div>
                        <?
                        }
                        ?>
                        <div class="contact_info">
                            <span class="bold name"><?= $n; ?></span>
                            <?
                            if($c["POST"]) {
                            ?>
                            <p class="work"><?= $c["POST"]; ?></p>
                            <?
                            }
                            if($c["TERRITORY"]){
                            ?>
                            <p><span class="info_type"><?= GetMessage("CNT_TERRITORY"); ?>:</span><?= $c["TERRITORY"]; ?></p>
                            <?
                            }
                            if($c["MOBILE_PHONE"]){
                            ?>
                            <p><span class="info_type"><?= GetMessage("CNT_MOB_PHONE"); ?>:</span><?= $c["MOBILE_PHONE"]; ?></p>
                            <?
                            }
                            if($c["PHONE"]){
                            ?>
                            <p><span class="info_type"><?= GetMessage("CNT_PHONE"); ?>:</span><?= $c["PHONE"]; ?></p>
                            <?
                            }
                            if($c["SKYPE"]){
                            ?>
                            <p><span class="info_type">Skype:</span><a href="skype:<?= $c["SKYPE"]; ?>"><?= $c["SKYPE"]; ?></a></p>
                            <?
                            }
                            if($c["EMAIL"]){
                            ?>
                            <p><span class="info_type">email:</span><a href="mailto:<?= $c["EMAIL"]; ?>"><?= $c["EMAIL"]; ?></a></p>
                            <?
                            }
                            if($c["ABOUT"]){
                            ?>
                            <p><span class="info_type"><?= GetMessage("CNT_ABOUT"); ?>:</span><?= $c["ABOUT"]; ?></p>
                            <?
                            }
                            ?>
                        </div>
                    </div>
                <?
                }
                if($arResult["CONTACTS"]) {
                ?>
                   <div class="project_filter_wrapper">
                       <form action="" method="POST" class="col-xs-12 project_filter callback_block mrg0">
                            <p class="pull-left padding15 OpenSans16"><?= GetMessage("CNT_SEND_PHONE_OR_EMAIL"); ?></p>
                            <div class="clearfix"></div>
                            <div class="col-sm-5 col-xs-12">
                                <label for="telNomer"><?= GetMessage("CNT_YOUR_PHONE_OR_EMAIL"); ?>:</label>
                                <input type="text" name="telNomer" id="telNomer" class="phone-or-email">
                            </div>
                            <div class="col-sm-4 col-xs-12">
                                <label><?= GetMessage("CNT_MANAGER"); ?>:</label>
                                <select class="selectpicker">
                                    <?
                                    foreach($arResult["CONTACTS"] as $c) {
                                        $n = implode(" ", array($c["LAST_NAME"], $c["FIRST_NAME"], $c["MIDDLE_NAME"]));
                                    ?>
                                        <option value="<?= $c["ID"]; ?>"><?= $n; ?></option>
                                    <?
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-3 col-xs-12">
                                <input type="button" value="<?= GetMessage("CNT_CALL_ME"); ?>" class="yellow_btn cont" id="recall">
                            </div>
                        </form>
                   </div>
                    <?
                }
                ?>
            <?
            }
            ?>
            <div class="col-xs-12 bottom-text">
                <?= GetMessage("CNT_CTC_MEDIA"); ?>
            </div>
        </div>

    </div>

</div>

<div class="dm-overlay" id="popup_send_confirm">
    <div class="dm-table">
        <div class="dm-cell">
            <div class="dm-modal">
                <a href="javascript:void(0)" class="close"></a>
                <p class="header"><?= GetMessage("CNT_PHONE_SENT"); ?></p>
                <a href="javascript:void(0)" class="yellow_btn ok ok_close">OK</a>
            </div>
        </div>
    </div>
</div>


