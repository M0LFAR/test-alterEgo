<?
CModule::IncludeModule("iblock");



class IblockRssImport extends CBitrixComponent{

    public $title='';
    public $lastExportDate='';

    private $feedExport;

    private $iblock;
    private $section;
    
    public function executeComponent()
    {

        if ($this->export() && $this->arParams['SAVE_TO_IBLOCK']) {
            $this->saveIblock();
        }

        $this->includeComponentTemplate();

        return $this->arResult;
    }


    public function onPrepareComponentParams($arParams)
    {

        $arParams = array(
            "SITE" => $arParams["SITE"]??"",
            "PORT" =>$arParams["PORT"]??80,
            "PATH" => $arParams["PATH"]??"",
            "IBLOCK_ID"=>$arParams["IBLOCK_ID"]??1,
            "IBLOCK_SELECTOR"=>$arParams["IBLOCK_SELECTOR"]??'item',
            "IBLOCK"=>array(
                "DETAIL_TEXT"=> $arParams["IBLOCK_PROPERTY_IN_DOM"]["DETAIL_TEXT"]??"description",
                "NAME"=>$arParams["IBLOCK_PROPERTY_IN_DOM"]["NAME"]??"title",
                "DETAIL_PICTURE"=>$arParams["IBLOCK_PROPERTY_IN_DOM"]["DETAIL_PICTURE"]??"enclosure",
                "SECTION_NAME"=>$arParams["IBLOCK_PROPERTY_IN_DOM"]["SECTION_NAME"]??""
            ),
            "SAVE_TO_IBLOCK" => (boolean) $arParams['SAVE_TO_IBLOCK'],
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => isset($arParams["CA CHE_TIME"]) ?$arParams["CACHE_TIME"]: 36000000,

        );


        $this->title = 'test';
        $this->arResult['lastUpdate'] = COption::GetOptionInt("news", "last_update");
        $this->iblock = new CIBlockElement;
        $this->section = new CIBlockSection;

        return $arParams;
    }


    private function export(){

        $arXML= CIBlockRSS::GetNewsEx($this->arParams["SITE"], $this->arParams["PORT"], $this->arParams["PATH"], $this->arParams["QUERY_STR"]);

        if(count($arXML) > 0)
        {
            $this->feedExport = CIBlockRSS::FormatArray($arXML);
            $this->title = $this->feedExport ["title"];
        }

        $this->arResult['lastUpdate'] = time();
            COption::SetOptionInt("news", "last_update", $this->arResult['lastUpdate']);


        return (bool)$this->feedExport;
    }


    private function saveIblock(){

        $feedItems =$this->feedExport[$this->arParams['IBLOCK_SELECTOR']];
        if (is_array($feedItems))
            foreach ($feedItems as $feedNode){
                $sectionName = $feedNode[$this->arParams['IBLOCK']['SECTION_NAME']];
                $sectionId =$this->getSectionId($sectionName);
                $namefeedNode  = $feedNode[$this->arParams['IBLOCK']['NAME']];
                $arLoadProductArray = Array(
                    "IBLOCK_SECTION_ID" => $sectionId,
                    "IBLOCK_ID"      => $this->arParams['IBLOCK_ID'],
                    "NAME"           => $namefeedNode,
                    "CODE"  => $this->translitWord($namefeedNode),
                    "ACTIVE"         => "Y",
                    "DETAIL_TEXT"    => $feedNode[$this->arParams['IBLOCK']['DETAIL_TEXT']],
                    "DETAIL_PICTURE" => $feedNode[$this->arParams['IBLOCK']['DETAIL_PICTURE']]
                );

                if($newIblockId = $this->iblock->Add($arLoadProductArray))
                    $this->arResult['newIbloksFeed'][] = $feedNode;
            }

    }


    private function getSectionId($name = false)
    {
        if($name) {
            $result = CIBlockSection::GetList(false, array('NAME' => $name), false, array('ID'));
            $idSection = $result->Fetch()["ID"];
        }


        if (!$idSection) {
            $arFields = Array(
                "ACTIVE" => 'Y',
                "IBLOCK_ID" => $this->arParams['IBLOCK_ID'],
                "NAME" => $name,
                "CODE" => $this->translitWord($name)
            );

            $idSection = $this->section->Add($arFields);
        }
            return $idSection;
    }


    private function translitWord($word=''){
        $arParams = array("replace_space"=>"-","replace_other"=>"-");
        $trans = Cutil::translit($word,"ru",$arParams);
        return $trans;
    }
}
