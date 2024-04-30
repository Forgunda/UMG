
<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
$request = Application::getInstance()->getContext()->getRequest();
$page=1;
if(isset($request["nav-culture"])) {
    $page=str_replace("page-","",$request["nav-culture"]);
}
//Получим контакты для таблицы
if(isset($arParams["IBLOCK_ID"])) {
    $Items = Kontacts::AllKontact($arParams["IBLOCK_ID"], $page);
    // Таблица
    if ($Items['COUNT'] > 0) {
        echo '<div class="container">';
        $APPLICATION->IncludeComponent(
            'bitrix:main.ui.grid',
            '',
            array(
                'GRID_ID' => $Items['GRID_ID'],
                'HEADERS' => $Items['HEADERS'],
                'SHOW_ROW_CHECKBOXES' => false,
                'ROWS' => $Items['ROWS'],
                'TOTAL_ROWS_COUNT' => $Items['COUNT'],
                'ALLOW_COLUMNS_SORT' => true,
                'SHOW_SELECTED_COUNTER' => false,
                'ALLOW_SORT' => true,
                'ENABLE_FIELDS_SEARCH' => true,
                'ENABLE_LABEL' => true,
                'NAV_OBJECT' => $Items['NAV'],
                'PAGINATION' => array(
                    'PAGE_NUM' => $page,
                    'ENABLE_NEXT_PAGE' => $Items['NAV']->getCurrentPage() < $Items['NAV']->getPageCount(),
                ),
                'SORT' => $Items['SORT'],
                'SORT_VARS' => $Items['SORT_VARS'],
                'AJAX_MODE' => 'Y',
                'AJAX_LOADER' => null,
                'AJAX_ID' => '',
                'AJAX_OPTION_JUMP' => 'N',
            ),
            null,
            array('HIDE_ICONS' => 'Y',)
        );
        echo '</div>';
    }
}
?>

