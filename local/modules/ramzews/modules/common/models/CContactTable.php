<?php
class CContactTable extends CModel {

    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName() {
        return 'rm_contact';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap() {
        return array(
            'ID' => array('data_type' => 'integer', 'primary' => true, 'autocomplete' => true, 'title' => "ID"),
            'FIRST_NAME' => array('data_type' => 'string', 'title' => "Имя"),
            'LAST_NAME' => array('data_type' => 'string', 'title' => "Фамилия"),
            'MIDDLE_NAME' => array('data_type' => 'string', 'title' => "Отчество"),
            'FIRST_NAME_EN' => array('data_type' => 'string', 'title' => "First name"),
            'LAST_NAME_EN' => array('data_type' => 'string', 'title' => "Second name"),
            'MIDDLE_NAME_EN' => array('data_type' => 'string', 'title' => "Middle name"),
            'PHONE' => array('data_type' => 'string', 'title' => "Телефон"),
            'POST' => array('data_type' => 'string', 'title' => "Должность"),
            'POST_EN' => array('data_type' => 'string', 'title' => "Post"),
            'SKYPE' => array('data_type' => 'string', 'title' => "Скайп"),
            'EMAIL' => array('data_type' => 'string', 'title' => "Email"),
            'MOBILE_PHONE' => array('data_type' => 'string', 'title' => "Мобильный телефон"),
            'ABOUT' => array('data_type' => 'text', 'title' => "О себе"),
            'ABOUT_EN' => array('data_type' => 'text', 'title' => "About"),
            'TERRITORY' => array('data_type' => 'string', 'title' => "Территория"),
            'TERRITORY_EN' => array('data_type' => 'string', 'title' => "Territory"),
            'PHOTO_ID' => array('data_type' => 'integer', 'title' => "Фото"),
            'SORT' => array('data_type' => 'integer', 'title' => "Сортировка"),
            'CREATED_AT' => array('data_type' => 'datetime', 'title' => "Дата создания"),

            "PHOTO" => array('data_type' => 'CFile', 'reference' => array('=this.PHOTO_ID' => 'ref.ID')),
        );
    }

    public static function OnBeforeUpdate (\Bitrix\Main\Entity\Event $event) {
		$item = self::Get($event->getParameter("primary"));
		$fields = $event->getParameter("fields");

        if($fields && $fields["PHOTO_ID"] && $item["PHOTO_ID"]) {
			$path = CFile::GetPath($item["PHOTO_ID"]);
			if(file_exists($_SERVER["DOCUMENT_ROOT"].$path)) {
            	CFile::Delete($item["PHOTO_ID"]);
			}
            return true;
        }
        return false;
    }
}