<?php
class CFileTable extends CModel {

    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName() {
        return 'b_file';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap() {
        return array(
            'ID' => array('data_type' => 'integer', 'primary' => true, 'autocomplete' => true, 'title' => "ID"),
            'TIMESTAMP_X' => array('data_type' => 'datetime', 'required' => true, 'title' => "Дата изменения"),
            'MODULE_ID' => array('data_type' => 'string', 'title' => "Модуль"),
            'HEIGHT' => array('data_type' => 'integer', 'title' => "Высота"),
            'WIDTH' => array('data_type' => 'integer', 'title' => "Ширина"),
            'FILE_SIZE' => array('data_type' => 'integer', 'title' => "Размер файла"),
            'CONTENT_TYPE' => array('data_type' => 'string', 'title' => "Тип контента"),
            'SUBDIR' => array('data_type' => 'string', 'title' => "Поддериктория"),
            'FILE_NAME' => array('data_type' => 'string', 'title' => "Название файла"),
            'ORIGINAL_NAME' => array('data_type' => 'string', 'title' => "Оригинальное название"),
            'DESCRIPTION' => array('data_type' => 'string', 'title' => "Описание"),
            'HANDLER_ID' => array('data_type' => 'string', 'title' => "HANDLER_ID"),
            'EXTERNAL_ID' => array('data_type' => 'string', 'title' => "EXTERNAL_ID"),
        );
    }
}