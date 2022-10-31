<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if($arResult["ITEMS"]):?>
<div>
<?foreach ($arResult["ITEMS"] as $arItem):?>
<div id="<?=$this->GetEditAreaId($arItem['ID']);?>">
    <img src="<?=$arItem["PICTURE"]?>" alt="<?=$arItem["NAME"]?>"/>
    <div><?= $arItem["TEXT"]?></div>
    <div><?= $arItem["NAME"]?></div>
    <div><?= $arItem["VOTE_COUNT"]?></div>
    <?
    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => "Удалить?"));
    ?>
</div>
<?endforeach;?>
<?
$navString = $arResult["NAV_RESULT"]->GetPageNavString(
  'Элементы', // поясняющий текст
  '.default',   // имя шаблона
  True // показывать всегда?
);
echo $navString;
?>
</div>
<?endif;?>
