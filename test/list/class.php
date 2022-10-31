<?php
use \Bitrix\Main\Loader;
use \Bitrix\Main\Application;
use \Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class TestList extends CBitrixComponent {
    private $_request;

    /**
     * Проверка наличия модулей требуемых для работы компонента
     * @return bool
     * @throws Exception
     */
    private function _checkModules() {
        if (   !Loader::includeModule('iblock')
        ) {
            throw new \Exception(Loc::getMessage("TEST_LIST_EXCEPT_IBLOCK_MODULE_NOT_INSTALLED"));
        }

        return true;
    }

    /**
     * Подготовка параметров компонента
     * @param $arParams
     * @return mixed
     */
    public function onPrepareComponentParams($arParams) {
        // тут должна быть обработка входных параметров, но в данном случае она необязательна
        return $arParams;
    }

    /**
     * Точка входа в компонент
     */
    public function executeComponent() {
        $this->_checkModules();

        $this->_request = Application::getInstance()->getContext()->getRequest();
        
        $this->arResult["NAV_RESULT"] = $this->getNavResult();
        $this->elements = $this->getElements();
        $this->resizeImage();
        $this->addActionButtons();
        $this->arResult["ITEMS"] = $this->elements;

        $this->includeComponentTemplate();
    }
    /**
     * Отправляем запрос к бд
     */
    private function getNavResult(){
        // $this->arSelect = array("PREVIEW_PICTURE","PREVIEW_TEXT","NAME"); // выбираемые поля
        $this->arFilter = array(
            'IBLOCK_ID' => $this->arParams["IBLOCK_ID"], // выборка элементов из инфоблока по ид
            'ACTIVE' => 'Y',  // выборка только активных элементов
        );
        $this->res = CIBlockElement::GetList(array(), $this->arFilter,false,array("nPageSize" => 10)); // получаем список элементов
        return $this->res;
    }

    /**
     * Получает поля и свойства элементов инфоблока
     */
    private function getElements(){
        $this->elements = array();  // массив элементов
        
        while ($this->element = $this->arResult["NAV_RESULT"]->GetNextElement()) {
            $this->fields = $this->element->GetFields(); // получаем массив полей
            $this->elements[] = array(
                "ID" => $this->fields["ID"],
                "PICTURE" => $this->fields["PREVIEW_PICTURE"],
                "TEXT" => $this->fields["PREVIEW_TEXT"],
                "NAME" => $this->fields["NAME"],
                "VOTE_COUNT" => $this->element->GetProperties()[$this->arParams["VOTE_COUNT_ID"]]["VALUE"]
            );
        }
        return $this->elements; 
    }
    /**
     * Уменьшает картинку
     */
    private function resizeImage(){
        foreach ($this->elements as &$this->element){ // в каждом элементе уменьшаем изображение
            $this->element["PICTURE"] = CFile::ResizeImageGet($this->element["PICTURE"], array('width'=>80, 'height'=>80))["src"];
        }
        unset($this->element); // разрываем ссылку на последний элемент
    }

    /**
     * Добавляет ссылки удаления и редактирования
     */
    private function addActionButtons(){
        foreach ($this->elements as &$this->element){
            $this->arButtons = CIBlock::GetPanelButtons(
                $this->arParams["IBLOCK_ID"],
                $this->element["ID"],
                0,
                array("SECTION_BUTTONS"=>false, "SESSID"=>false)
            );
            $this->element["EDIT_LINK"] = $this->arButtons["edit"]["edit_element"]["ACTION_URL"];
            $this->element["DELETE_LINK"] = $this->arButtons["edit"]["delete_element"]["ACTION_URL"];
        }
        unset($this->element); // разрываем ссылку на последний элемент
    }
}