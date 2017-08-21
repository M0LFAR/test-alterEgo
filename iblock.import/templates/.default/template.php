<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->addExternalJS("https://code.jquery.com/jquery-3.2.1.min.js");
?>

<button id="refresh">Обновить</button>
Последнее обновление: <span id="last-update"><?=date("d.m.Y, H:i:s", $arResult['lastUpdate']);?></span>

<?$APPLICATION->IncludeComponent(
    "bitrix:news",
    "",
    Array(
        "CATEGORY_CODE" => "THEMES",
        "CATEGORY_IBLOCK" => array($arResult['IBLOCK_ID']),
        "CHECK_DATES" => "Y",
        "IBLOCK_ID" => $arResult['IBLOCK_ID'],
        "IBLOCK_TYPE" => "news",
        "SEF_MODE" => "Y",
        "SEF_FOLDER"=>"/imported-news/",
        "SEF_URL_TEMPLATES" => Array("detail"=>"#SECTION_CODE#/#ELEMENT_CODE#/",
                                    "section"=>"#SECTION_СODE#/"),
    )
);?>

