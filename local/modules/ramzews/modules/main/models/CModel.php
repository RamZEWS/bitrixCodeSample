<?php
use Bitrix\Main\Entity;
use Bitrix\Main\Entity\Query;

class CModel extends Entity\DataManager {
    /**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName() {
		return false;
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap(){
		return array();
	}

    /**
     * Удаление записей (также групповое удаление)
     * @param mixed $primary
     */
    public static function delete($primary) {
        if(!is_array($primary) && intval($primary)){
            return parent::delete($primary);
        } else {
            if($ids = static::getIDs($primary)){
                foreach($ids as $id) {
                    parent::delete($id);
                }
                return true;
            }
        }
    }

    /**
     * Обновление записей
     * @param mixed $mixed
     */
    public static function update($mixed, $data = array()) {
        if(!is_array($mixed) && intval($mixed)){
            return parent::update($mixed, $data);
        } else {
            if($ids = static::getIDs($mixed)){
                foreach($ids as $id) {
                    parent::update($id, $data);
                }
                return true;
            }
        }
    }

    /**
     * Возвращает хедер для админки
     * @return array
     */
    public static function getAdminListHeader($whatShow = null, $needSort = true){
        $header = array();
        foreach(static::getMap() as $field => $info){
            if(!isset($info['reference'])) {
                $header[] = array(
                    'id' => $field,
                    'content' => $info['title'],
                    'default' => true,
                    'sort' => $needSort ? strtolower($field) : false
                );
            }
        }

        if($whatShow) {
            $result = array();
            foreach($header as $h) {
                if(in_array($h["id"], $whatShow)) {
                    $result[] = $h;
                }
            }
            return $result;
        } else {
            return $header;
        }
    }

    /**
     * Get empty array of fields
     * @return array
     */
    public static function getEmpty(){
        $data = array();
        foreach(static::getMap() as $field => $info) $data[$field] = null;
        return $data;
    }

    /**
     * Check row in DB
     * @param array $filter
     * @return boolean
     */
    public static function CheckExists($filter = array()){
        $rs = static::GetList(array("filter" => $filter))->Fetch();
        return $rs ? true : false;
    }

    /**
     * Transform data for DB
     * @param array $inFields
     * @return array
     */
    public static function prepareFields($inFields = array()) {
        $fields = array();
        $map = static::getMap();
        foreach(self::filterFields($inFields) as $field => $value){
            switch($map[$field]['data_type']){
                case 'date':
                case 'datetime':
                    $fields[$field] = $value ? new \Bitrix\Main\Type\DateTime(date("Y-m-d H:i:s", strtotime($value)), 'Y-m-d H:i:s') : null;
                    break;
                case "integer":
                    $value = $value ?: null;
                    $fields[$field] = $value;
                    break;
                default:
                    $fields[$field] = $value;
                    break;
            }
        }
        return $fields;
    }

    /**
     * Get array KEY => FIELD
     * @param string $key
     * @param string $field
     * @param array $filter
     * @param array $sort
     * @param boolean $needJoin
     * @return array
     */
    public static function GetArray($key = "ID", $field = "NAME", $filter = array(), $sort = array("ID" => "ASC"), $needJoin = true){
        $all = static::GetAll($filter, $sort, null, 0, null, $needJoin);

        $result = array();
        $i = 0;
        foreach($all as $ar){
            $result[ ($key ? $ar[$key] : $i++) ] = $field ? $ar[$field] : $ar;
        }
        return $result;
    }

    /**
     * Get array with IDs
     * @param array $filter
     * @param array $order
     * @return array
     */
    public static function getIDs($filter = array(), $order = array("ID" => "ASC")){
        $ids = array();
        $rs = static::GetList(array("select" => array("ID"), "filter" => $filter, "order" => $order));
        while($ar = $rs->Fetch()) $ids[] = $ar["ID"];
        return $ids;
    }

    /**
     * Get rows in array
     * @param array $filter
     * @param array $sort
     * @param integer $limit
     * @param integer $offset
     * @param string $group
     * @param boolean $needJoin
     * @return array
     */
    public static function GetAll($filter = array(), $sort = array(), $limit = null, $offset = 0, $group = null, $needJoin = true){
        $result = array();

        $rs = static::GetAllRs($filter, $sort, $limit, $offset, $group, $needJoin);
        while($ar = $rs->Fetch()) $result[$ar["ID"]] = $ar;

        return $result;
    }

    /**
     * Get rows in CDBResult object
     * @param array $filter
     * @param array $sort
     * @param integer $limit
     * @param integer $offset
     * @param string $group
     * @param boolean $needJoin
     * @return CDBResult
     */
    public static function GetAllRs($filter = array(), $sort = array(), $limit = null, $offset = 0, $group = null, $needJoin = true){
        $select = array();
        $map = static::getMap();
        foreach($map as $key => $info){
            if(isset($info['reference'])){
                if($needJoin) {
                    $f = current(array_keys($info['reference']));
                    $f = str_replace("=this.", "", $f) . "_";
                    $select[$f] = $key;
                }
            } else {
                $select[] = $key;
            }
        }

        $params = array('select' => $select, 'filter' => $filter, 'order' => $sort, "count_total" => true);

        if($limit) $params['limit'] = $limit;
        if($offset) $params['offset'] = $offset;
        if($group) $params['group'] = $group;

        return static::GetList($params);
    }

    /**
     * Get total count
     * @param array $filter
     * @param string $group
     * @return integer
     */
    public static function GetTotal($filter, $group = null){
        $query = new Query(static::getEntity());
        $query->setFilter($filter);
        if($group) {
            $query->setGroup($group);
        } else {
            $query->addSelect('ID');
        }
        $rs = $query->exec();
        return $rs->getSelectedRowsCount();
    }

    /**
     * Get row from DB
     * @param array $filter
     * @param boolean $needJoin
     * @return array | boolean
     */
    public static function Get($filter = array(), $needJoin = true) {
        $item = current(static::GetAll($filter, array(), null, 0, null, $needJoin));
        return $item ?: false;
    }

    /**
     * Upload file or files
     * @param string $key
     * @param boolean $isImage
     * @param boolean $isMulti
     * @return boolean
     */
    public static function UploadFile($key, $isImage = false, $isMulti = false) {
        if($isMulti) {
            $files = self::reArrayFiles($key);
            $ids = array();
            foreach($files as $f){
                if($isImage && !CFile::IsImage($f["name"], $f["type"])) {
                    return false;
                }
                $ids[] = CFile::SaveFile($f);
            }
            return $ids;
        } else if (isset($_FILES[$key])) {
            if($isImage && !CFile::IsImage($_FILES[$key]["name"], $_FILES[$key]["type"])) {
                return false;
            }
            return CFile::SaveFile($_FILES[$key]);
        }
        return false;
    }

    /**
     * Rearray $_FILES array for multiply uploading
     * @param string $key
     * @return array
     */
    public static function reArrayFiles($key){
        $files = array();
        if(isset($_FILES[$key])) {
            foreach(array("name", "type", "tmp_name", "error", "size") as $k){
                foreach($_FILES[$key][$k] as $i => $n) {
                    $files[$i][$k] = $n;
                }
            }
        }
        return $files;
    }
}