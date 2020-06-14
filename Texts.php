<?php

require_once 'database/db_connect.php';

class Texts
{
    private $lang;

    public function __construct($lang)
    {
        $this->lang = $lang;
    }

    function getText($keyword)
    {
        global $db;
        $res = "";
        $keyword = $db->real_escape_string($keyword);
        $result = $db->query("SELECT * FROM `texts` WHERE `keyword` = '{$keyword}' LIMIT 1");
        $arr = $result->fetch_assoc();
        if (isset($arr[$this->lang])) {
            $res = $arr[$this->lang];
        }
        return $res;
    }

    function getArrayLike($keyword)
    {
        global $db;
        $res = [];
        $keyword = $db->real_escape_string($keyword);
        $result = $db->query("SELECT * FROM `texts` WHERE `keyword` LIKE '{$keyword}%'");
        while ($arr = $result->fetch_assoc()) {
            if (isset($arr[$this->lang])) {
                $res[] = $arr[$this->lang];
            }
        }
        return $res;
    }

}