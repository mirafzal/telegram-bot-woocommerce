<?php

require_once("database/db_connect.php");

class User
{
    /**
     * @var integer
     */
    public $chatID;


    function __construct($chatID)
    {

        $this->chatID = $chatID;


        if (!$this->isUserSet()) $this->makeUser();


        if ($this->getKeyValue('lang') == "") $this->setLanguage('uz');
    }

    function setPage($page)
    {

        return $this->setKeyValue('page', $page);

    }

    function getPage()
    {

        return $this->getKeyValue('page');

    }

    function setLanguage($lang)
    {

        $this->setKeyValue('lang', $lang);

    }

    function getLanguage()
    {

        return $this->getKeyValue('lang');

    }

    private function makeUser()
    {

        global $db;

        $chatID = $db->real_escape_string($this->chatID);


        $query = "insert into `users`(chatID) values('{$chatID}')";

        if (!$db->query($query))

            die("пользователя создать не удалось");

    }


    private function isUserSet()
    {

        global $db;

        $chatID = $db->real_escape_string($this->chatID);


        $result = $db->query("select * from users where chatID='$chatID' LIMIT 1");


        $myArray = (array)($result->fetch_array());


        if (!empty($myArray)) return true;

        return false;

    }


    private function getData()
    {

        global $db;

        $res = array();

        $chatID = $db->real_escape_string($this->chatID);

        $result = $db->query("select * from `users` where chatID='$chatID'");

        $arr = $result->fetch_assoc();

        if (isset($arr['data_json'])) {

            $res = json_decode($arr['data_json'], true);

        }


        return $res;

    }


    private function setKeyValue($key, $value)
    {

        global $db;

        $chatID = $db->real_escape_string($this->chatID);

        $value = base64_encode($value);

        if (!$this->isUserSet()) {

            $this->makeUser(); // если каким-то чудом этот пользователь не зарегистрирован в базе

        }

        $data = $this->getData();

        $data[$key] = $value;

        $data = json_encode($data, JSON_UNESCAPED_UNICODE);

        return $db->query("update `users` SET data_json = '{$data}' WHERE chatID = '{$chatID}'"); // обновляем запись в базе

    }


    private function getKeyValue($key)
    {

        $data = $this->getData();

        if (array_key_exists($key, $data)) {

            return base64_decode($data[$key]);

        }

        return "";


    }

}