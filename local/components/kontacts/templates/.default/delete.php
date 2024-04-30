<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
// класс для работы с языковыми файлами
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
global $DB;
// проверяем установку модуля «Информационные блоки»
if (!CModule::IncludeModule('iblock')) {
    return;
}
$dir=str_replace($_SERVER["DOCUMENT_ROOT"],"", __DIR__);
$request = Application::getInstance()->getContext()->getRequest();
if(isset($request["ID"])&&isset($request["IBLOCK"])){
    if(CIBlock::GetPermission($request["IBLOCK"])>='W')
    {
       $DB->StartTransaction();
        if(!CIBlockElement::Delete($request["ID"]))
        {
            $mess = 'Не удалось удалить';
            $DB->Rollback();
        }
        else{
            $DB->Commit();
            $mess = 'Контакт удален';
       }
    }
}else{
    $mess="Не получены данные о контакте";
}
?>
<!--Подключение скриптов и стилей-->
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="<?echo $dir?>/js/bootstrap.bundle.min.js">
</script>
<link href="<?echo $dir?>/css/bootstrap.min.css" rel="stylesheet">
<script src="<?echo $dir?>/js/phonemask.js">
</script>
<div class="container">
    <p class="--bs-success-text-emphasis text-success"><?echo $mess?></p>
</div>
<a class="mt-3" href = "javascript:history.back()">Вернуться назад</a>