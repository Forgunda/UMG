<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
// класс для работы с языковыми файлами
use Bitrix\Main\Localization\Loc;
// проверяем установку модуля «Информационные блоки»
if (!CModule::IncludeModule('iblock')) {
    return;
}
// получаем массив всех типов инфоблоков для возможности выбора
$arIBlockType        = CIBlockParameters::GetIBlockTypes();
// пустой массив для вывода
$arInfoBlocks        = [];
// выбираем активные инфоблоки
$arFilterInfoBlocks  = ['ACTIVE' => 'Y'];
// сортируем по озрастанию поля сортировка
$arOrderInfoBlocks   = ['SORT' => 'ASC'];
// если уже выбран тип инфоблока, выбираем инфоблоки только этого типа
if (!empty($arCurrentValues['IBLOCK_TYPE'])) {
    $arFilterInfoBlocks['TYPE'] = $arCurrentValues['IBLOCK_TYPE'];
}
// метод выборки информационных блоков
$rsIBlock =\Bitrix\Iblock\IblockTable::getList(
    [
        'order'  =>$arOrderInfoBlocks,
        'select' =>['ID', 'NAME'],
        'filter' =>$arFilterInfoBlocks,
    ]
);
// перебираем и выводим в адмику доступные информационные блоки
while ($obIBlock = $rsIBlock->Fetch()) {
    $arInfoBlocks[$obIBlock['ID']] = '[' . $obIBlock['ID'] . '] ' . $obIBlock['NAME'];
}
// настройки компонента, формируем массив $arParams
$arComponentParameters = [
    // основной массив с параметрами
    'PARAMETERS' => [
        // выбор инфоблока
        'IBLOCK_ID' => [
            'PARENT'            => 'BASE',
            'NAME'              => Loc::GetMessage("IBLOCK_ID"),
            'TYPE'              => 'LIST',
            'VALUES'            => $arInfoBlocks,
            'REFRESH'           => 'Y',
            "DEFAULT"           => '',
            "ADDITIONAL_VALUES" => "Y",
        ],
        //Номер страницы для постраничной навигации
        "PAGE"=> [
            "NAME"      => Loc::getMessage("PAGE"),
            "TYPE"      => "STRING",
            "PARENT"    => "BASE",
            "DEFAULT"   => "={\$_REQUEST[\"page\"]}",
        ],
        // настройки кэширования
        'CACHE_TIME' => [
            'DEFAULT' => 3600
        ],
        "CACHE_TYPE" => "N"
    ],
];