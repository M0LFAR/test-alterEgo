<?
CModule::IncludeModule("iblock");

class IblockRssImport extends CBitrixComponent{

    const SELECTOR_IBLOCK_PROPERTY = "IBLOCK_PROPERTY_IN_DOM";
    const SELECTOR_IBLOCK= "IBLOCK_SELECTOR";
    const IBLOCK_ID= "IBLOCK_ID";

    private $feedExport;
    private $defaultParams =array(
                                "SITE" => "",
                                "PORT" =>80,
                                "PATH" =>"",
                                "IBLOCK_ID"=>1,
                                self::SELECTOR_IBLOCK =>'item',
                                self::SELECTOR_IBLOCK_PROPERTY=>array(
                                    "DETAIL_TEXT"=>"description",
                                    "NAME"=>"title",
                                    "DETAIL_PICTURE"=>"enclosure",
                                    "SECTION_NAME"=>""
                                ),
                                "CACHE_TIME" =>  36000000
                            );
    private $iblock;
    private $section;
    
    public function executeComponent(){


        $this->export() && $this->saveIblock();

        if ($this->arParams['TYPE_ANSWER']=='json'){

           $this->export() && $this->saveIblock();

            $GLOBALS['APPLICATION']->RestartBuffer();

           echo  $this->arResult = json_encode($this->arResult['newIblocksFeed']);

           exit();
        }else{

            $this->includeComponentTemplate();

            return $this->arParams;
        }
    }


    public function onPrepareComponentParams($arParams){

        $arParams = array_replace($this->defaultParams, $arParams);

        $request = Bitrix\Main\Context::getCurrent()->getRequest();
        $curDir = $request->getRequestedPageDirectory();

        $this->arResult['lastUpdate'] = COption::GetOptionInt("news", "last_update");

        $this->iblock = new CIBlockElement();
        $this->section = new CIBlockSection();

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

        $paramsSelect = $this->arParams['IBLOCK_PROPERTY_IN_DOM'];
        $needSelectorField = array_flip($paramsSelect);

        if (is_array($feedItems))
        foreach ($feedItems as $feedNode){
            $selectedValue = array_intersect_key($feedNode, $needSelectorField);


            foreach ($paramsSelect as $property => $feedSelector){
                $selectedValue[$property] = $selectedValue[$feedSelector];
                unset($selectedValue[$feedSelector]);
            }


            $selectedValue = array_merge(
                $selectedValue,
                array(
                    "IBLOCK_SECTION_ID" => $this->getSectionId($selectedValue["SECTION_NAME"], $this->arParams['IBLOCK_ID']),
                    "CODE"  => $this->translitWord("CODE"),
                    "ACTIVE"         => "Y",
                    "IBLOCK_ID"      => $this->arParams['IBLOCK_ID'],
                    "DETAIL_PICTURE" =>$selectedValue["DETAIL_PICTURE"]["url"]
                )
            );

                if($this->iblock->Add($selectedValue )) {
                    $this->arResult['newIblocksFeed'][] = $feedNode;
                }
            }
    }


    private function getSectionId($name = false, $iblockId = 2){

        if($name) {
            $result = CIBlockSection::GetList(false, array('NAME' => $name), false, array('ID'));
            $idSection = $result->Fetch()["ID"];
        }

        if (!$idSection) {
            $arFields = Array(
                "ACTIVE" => 'Y',
                "IBLOCK_ID" => $iblockId,
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
