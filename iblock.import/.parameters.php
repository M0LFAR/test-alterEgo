<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
    "GROUPS" => array(),
    "PARAMETERS" => array(
        "SITE"=> array(
            "PARENT" => "BASE",
            "NAME" => "Сайт",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "http://k.img.com.ua",
            "REFRESH" => "Y",
        ),
        "PORT"=> array(
            "PARENT" => "BASE",
            "NAME" => "Порт",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "80",
            "REFRESH" => "Y",
        ),
        "PATH"=>array(
            "PARENT" => "BASE",
            "NAME" => "Путь к скрипту",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "/rss/ru/all_news2.0.xml",
            "REFRESH" => "Y",
        ),
        "QUERY_STR"=> array(
            "PARENT" => "BASE",
            "NAME" => "Строка запроса",
            "TYPE" => "STRING",
            "MULTIPLE" => "N",
            "DEFAULT" => "",
            "REFRESH" => "Y",
        ),
        "SAVE_TO_IBLOCK"=> array(
            "PARENT" => "BASE",
            "NAME" => "Сохранять данные в инфоблок",
            "TYPE" => "CHECKBOX",
            "MULTIPLE" => "N",
            "DEFAULT" => true,
            "REFRESH" => "Y",
        ),
    ),
);
?>