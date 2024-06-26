
<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
$request = Application::getInstance()->getContext()->getRequest();
//заголовок
$APPLICATION->SetTitle(Loc::getMessage("PAGE_TITLE"));
//текущая директория
$dir=Kontacts::DIR(__DIR__);
//обнулим переменную id
unset($id);
?>
<!--Подключение скриптов и стилей-->
<meta name="viewport" content="width=device-width, initial-scale=1">
<script src="<?echo $dir?>/js/bootstrap.bundle.min.js">
</script>
<link href="<?echo $dir?>/css/bootstrap.min.css" rel="stylesheet">
<script src="<?echo $dir?>/js/phonemask.js">
</script>
<?php
if($request->isPost()) {
    //Добавление контакта
    if(isset($request["FIO"])&&isset($request["phone"])&&isset($arResult["IBLOCK_ID"])) {
        $id = Kontacts::addKontact($arResult["IBLOCK_ID"], $request["FIO"], $request["phone"], $USER->GetID());
    }
}
?>
<?php
if(isset($arResult["IBLOCK_ID"])){
?>
<div class="container">
    <form method="post" class="mt-3">
        <div class="mb-3">
            <?if($id>0){?>
            <p class="--bs-success-text-emphasis text-success"><?echo Loc::getMessage("ADDED")?></p>
            <?}?>
            <label for="FIO" class="form-label"><?echo Loc::getMessage("FIO_LABEL")?></label>
            <input type="text" class="form-control" id="FIO" name="FIO"
                   aria-describedby="FIOHelp"
                   minlength="3"
                   required>
            <div id="FIOHelp" class="form-text"><?echo Loc::getMessage("FIO_HELP")?></div>
        </div>
        <div class="mb-3">
            <label for="online_phone" class="form-label"><?echo Loc::getMessage("PHONE_LABEL")?></label>
            <input type="phone" class="form-control" id="online_phone" name="phone"
                   aria-describedby="PHONEHelp"
                   pattern="\+7\s?[\(]{0,1}9[0-9]{2}[\)]{0,1}\s?\d{3}[-]{0,1}\d{2}[-]{0,1}\d{2}"
                   placeholder="+7(___)___-__-__"
                   required>
            <div id="PHONEHelp" class="form-text"><?echo Loc::getMessage("PHONE_HELP")?></div>
        </div>
        <button type="submit" class="btn btn-primary">Добавить</button>
    </form>
</div>
<?php }else{?>
    <div class="container">
        <p class="--bs-danger-text-emphasis text-danger">Выберите инфоблок</p>
    </div>
<?php }?>