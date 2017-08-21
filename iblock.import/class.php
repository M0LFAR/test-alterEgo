<?
CModule::IncludeModule("iblock");

class IblockRssImport extends CBitrixComponent{

    private $iblock;
    private $section;

    const IBLOCK_ID= "IBLOCK_ID";
    private $feedExport;
    private $defaultParams =array(
                                "SITE" => "",
                                "PORT" =>80,
                                "PATH" =>"",
                                "IBLOCK_ID"=>1,
                                "IBLOCK_SELECTOR"=>'item',
                                "IBLOCK_PROPERTY_IN_DOM"=>array(
                                    "DETAIL_TEXT"=>"description",
                                    "NAME"=>"title",
                                    "DETAIL_PICTURE"=>"enclosure",
                                    "SECTION_NAME"=>""
                                ),
                                "CACHE_TIME" =>  36000000
                            );

    public function executeComponent(){


        if ($this->arParams['TYPE_ANSWER']=='json'){

           $this->export() && $this->saveIblocks();

            $GLOBALS['APPLICATION']->RestartBuffer();

           echo  json_encode($this->arResult['newIblocksFeed']);

           exit();

        }else{

            $this->includeComponentTemplate();

            return $this->arParams;
        }
    }


    public function onPrepareComponentParams($arParams){

        $arParams = array_replace($this->defaultParams, $arParams);

        $this->arResult['lastUpdate'] = COption::GetOptionInt("news", "last_update");

        $this->iblock = new CIBlockElement();
        $this->section = new CIBlockSection();

        return $arParams;
    }


    private function export(){

        $arXML= CIBlockRSS::GetNewsEx($this->arParams["SITE"], $this->arParams["PORT"], $this->arParams["PATH"], $this->arParams["QUERY_STR"]);

        if(count($arXML) > 0){
            $this->feedExport = CIBlockRSS::FormatArray($arXML);
            $this->title = $this->feedExport ["title"];
        }

        if($status = (bool)$this->feedExport) {

            $this->arResult['newIblocksFeed']['lastUpdate'] = $this->arResult['lastUpdate'] = time();

            COption::SetOptionInt("news", "last_update", $this->arResult['lastUpdate']);

        }
        return $status;
    }


    private function saveIblocks(){

        $selector = $this->arParams['IBLOCK_SELECTOR'];
        $feedItems =$this->feedExport[$selector];

        $paramsSelect = $this->arParams['IBLOCK_PROPERTY_IN_DOM'];
        $needSelectorField = array_flip($paramsSelect);

        if (is_array($feedItems)) {

            foreach ($feedItems as $feedNode) {
                $gettingValues = array_intersect_key($feedNode, $needSelectorField);


                foreach ($paramsSelect as $property => $feedSelector) {
                    $iblockField[$property] = $gettingValues[$feedSelector];
                }


                $iblockField = array_merge(
                    (array)$iblockField,
                    array(
                        "IBLOCK_SECTION_ID" => $this->getSectionId($iblockField["SECTION_NAME"], $this->arParams['IBLOCK_ID']),
                        "CODE" => self::translitWord($iblockField["NAME"]),
                        "ACTIVE" => "Y",
                        "IBLOCK_ID" => $this->arParams['IBLOCK_ID'],
                        "DETAIL_PICTURE" => $iblockField["DETAIL_PICTURE"]["url"]
                    )
                );


                if ($this->iblock->Add($iblockField)) { //оскільки символьний код гарантує унікальність то інфоблоки не будуть добавлені повторно
                    $feedNode['link'] = self::translitWord($iblockField["SECTION_NAME"]) . '/' . $iblockField["CODE"];
                    $this->arResult['newIblocksFeed']['elements'][] = $feedNode;
                }

            }
        }
    }


    private function getSectionId($name, $iblockId){

        if($name) {
            $result = CIBlockSection::GetList(false, array('NAME' => $name), false, array('ID'));
            $idSection = $result->Fetch()["ID"];
        }

        if (!$idSection) {
            $arFields = Array(
                "ACTIVE" => 'Y',
                "IBLOCK_ID" => $iblockId,
                "NAME" => $name,
                "CODE" => self::translitWord($name)
            );

            $idSection = $this->section->Add($arFields);
        }
            return $idSection;
    }


    static function translitWord($word=''){

        $arParams = array("replace_space"=>"-","replace_other"=>"-");
        return Cutil::translit($word,"ru",$arParams);

    }

}
