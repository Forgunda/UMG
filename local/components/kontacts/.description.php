<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
// класс для работы с языковыми файлами
use Bitrix\Main\Localization\Loc;
$arComponentDescription = [
    // название компонента
    'NAME' => Loc::GetMessage("S_TEMPLATE_NAME"),
    // описание компонента
    'DESCRIPTION' =>Loc::GetMessage("S_DESCRIPTION"),
    // показывать кнопку очистки кеша
    'CACHE_PATH' => 'Y',
    // порядок сортировки в визуальном редакторе
    'SORT' => 30,
    // признак комплексного компонента
    'COMPLEX' => 'N',
    // расположение компонента в визуальном редакторе
    'PATH' => [
        // идентификатор верхнего уровеня в редакторе
        'ID' => 'cont',
        // название верхнего уровня в редакторе
        'NAME' => Loc::GetMessage("S_NAME"),
    ]
];
?>