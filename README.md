## Компонент імпорту rss стрічки ###

[Результат роботи компонента](https://bitrix.nbrz.ru/imported-news/)

**Ініціалізація компонента:**

```
$APPLICATION->IncludeComponent(
    "rss:iblock.import",
    ".default",
    Array(
        "SITE" => "http://k.img.com.ua",
        "PORT" =>80,
        "PATH" => "/rss/ru/all_news2.0.xml",
        "IBLOCK_SELECTOR"=>'item',
        "IBLOCK_ID"=>2,
        "IBLOCK_PROPERTY_IN_DOM"=>array(
            "DETAIL_TEXT"=>"description",
            "NAME"=>"title",
            "DETAIL_PICTURE"=>"enclosure",
            "SECTION_NAME" =>"category"
            ),
        "SAVE_TO_IBLOCK" => true
    ),
    false
);
  ```