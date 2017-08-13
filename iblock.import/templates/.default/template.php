<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->addExternalJS("https://code.jquery.com/jquery-3.2.1.min.js");
?>

<button id="refresh">Обновить</button>
Последнее обновление: <?=date("Y-m-d H:i", $arResult['lastUpdate']);?>

<?$APPLICATION->IncludeComponent(
    "bitrix:news",
    "",
    Array(

        "CATEGORY_CODE" => "THEMES",
        "CATEGORY_IBLOCK" => array($arResult['IBLOCK_ID']),
        "CHECK_DATES" => "Y",
        "DETAIL_DISPLAY_BOTTOM_PAGER" => "Y",
        "DETAIL_DISPLAY_TOP_PAGER" => "N",
        "DETAIL_FIELD_CODE" => array("",""),
        "IBLOCK_ID" => $arResult['IBLOCK_ID'],
        "IBLOCK_TYPE" => "news",
        "SEF_MODE" => "Y",
        "SEF_URL_TEMPLATES" => Array("detail"=>"#SECTION_CODE#/#ELEMENT_CODE#/","news"=>"","rss"=>"rss/","rss_section"=>"#SECTION_ID#/rss/","search"=>"search/","section"=>"#SECTION_СODE#/"),
    )
);?>

<!--
--><?/*$APPLICATION->IncludeComponent(
    "bitrix:news",
    "",
    Array(
        "CATEGORY_IBLOCK" => array("2"),
        "IBLOCK_ID" => "2",
        "IBLOCK_TYPE" => "news",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
        "LIST_ACTIVE_DATE_FORMAT" => "d.m.Y",
        "NUM_DAYS" => "360",
        "NUM_NEWS" => "120",
        "PREVIEW_TRUNCATE_LEN" => "0",
        "SEF_FOLDER" => "/imported-news/",
        "SEF_URL_TEMPLATES" => Array("detail"=>"#SECTION_CODE#/#ELEMENT_CODE#/","news"=>"","rss"=>"rss/","rss_section"=>"#SECTION_ID#/rss/","search"=>"search/","section"=>"#SECTION_СODE#/"),
    )
);*/?>

