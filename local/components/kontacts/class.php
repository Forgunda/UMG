<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
// класс для работы с языковыми файлами
use Bitrix\Main\Localization\Loc;
// класс для всех исключений в системе
use Bitrix\Main\SystemException;
// класс работы с гридом
use Bitrix\Main\Grid\Options as GridOptions;
// класс для загрузки необходимых файлов, классов, модулей
use Bitrix\Main\Loader;
// класс для простой навигации
use \Bitrix\Main\UI\PageNavigation;
// основной класс, является оболочкой компонента унаследованного от CBitrixComponent
class Kontacts extends CBitrixComponent
{
    // выполняет основной код компонента, аналог конструктора (метод подключается автоматически)
    public function executeComponent()
    {
        try {
            // подключаем метод проверки подключения модуля «Информационные блоки»
            $this->checkModules();
            // подключаем метод подготовки массива $Items
            $this->getResult();
        } catch (SystemException $e) {
            ShowError($e->getMessage());
        }
    }

    // подключение языковых файлов (метод подключается автоматически)
    public function onIncludeComponentLang()
    {
        Loc::loadMessages(__FILE__);
    }

    // обработка массива $arParams (метод подключается автоматически)
    public function onPrepareComponentParams($arParams)
    {
        // время кеширования
        if (!isset($arParams['CACHE_TYPE'])) {
            $arParams['CACHE_TYPE'] = 'N';
        } else {
            $arParams['CACHE_TYPE'] = intval($arParams['CACHE_TYPE']);
        }
        // время кеширования
        if (!isset($arParams['CACHE_TIME'])) {
            $arParams['CACHE_TIME'] = 3600;
        } else {
            $arParams['CACHE_TIME'] = intval($arParams['CACHE_TIME']);
        }
        // возвращаем в метод новый массив $arParams
        return $arParams;
    }
    //Добавление елемента
    public static function addKontact(int $IBlockID, string $fio, string $phoneNumber, int $userID)
    {
        $el = new CIBlockElement;

        $arLoadProductArray = [
            "MODIFIED_BY"    => $userID, // элемент изменен текущим пользователем
            "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
            "IBLOCK_ID"      => $IBlockID,
            "NAME"           => $fio,
            "ACTIVE"         => "Y",            // активен
            "PREVIEW_TEXT"   => $phoneNumber,
        ];
        try {
            $id=$el->Add($arLoadProductArray);
            return $id;
        } catch (SystemException $e) {
            ShowError($e->getMessage());
            return false;
        }
    }
    //Директория компонента
    public static function DIR($dir)
    {
        return str_replace($_SERVER["DOCUMENT_ROOT"],"", $dir);
    }
    //Все контакты
    public function AllKontact(int $IBlockID,int $page=1,string $del_link="")
    {
        if($del_link==""){
            $del_link=$this->DIR(__DIR__).'/templates/.default';
        }
        $nav = new \Bitrix\Main\UI\PageNavigation("nav-culture");
        $nav->setPageSize(5);
        $nav->setCurrentPage($page);
        $grid_options = new GridOptions("Kontacts");
        $sort = $grid_options->GetSorting();
        $res = \Bitrix\Iblock\ElementTable::getList(
            [
                // сортировка
                'order' => $sort['sort'],
                // выбираемые поля без свойств, свойства можно получать
                // только при обращении к ORM классу, конкретного инфоблока
                'select' => ['ID','NAME','PREVIEW_TEXT'],
                // фильтр только по полям элемента
                'filter' => ['IBLOCK_ID' => $IBlockID],
                // ограничение выбираемого кол-ва
                'limit' => $nav->getLimit(),
                // число, указывающее номер первого столбца в результате
                'offset' => $nav->getOffset(),
                // дает возможность получить кол-во элементов
                // через метод getCount()
                'count_total' => true,
                // кеш запроса
                'cache' => [
                    'ttl' => 3600,
                    'cache_joins' => true
                ],
            ]
        );
        $nav->setRecordCount($res->getCount());
        $ALL = [
            "IBLOCK_ID"=>$IBlockID,
            'GRID_ID' => "Kontacts",
            'COUNT'=>$res->getCount(),
            'NAV'=>$nav,
            'SORT'=>$sort["sort"],
            'SORT_VARS'=>$sort["vars"],
            'PAGE'=>$page,
        ];
        //Получим элементы
        //Массив строк для грида
        while ($row = $res->fetch())
        {
            $ALL['ITEMS'][]=$row;
            $ALL['ROWS'][$row['ID']] = array(
                'id' => $row['ID'],
                'data' => $row,
                'columns' => $row,
                'actions' => [ //Действия над ними
                    [
                        'text'    => 'Удалить',
                        'onclick' => 'document.location.href="'.$del_link.'/delete.php?ID='.$row['ID'].'&IBLOCK='.$IBlockID.'"',
                    ]
                ],
            );
        }
        //Заголовки таблицы
        $ALL['HEADERS'] = array(
            array(
                'id' => 'ID',
                'name' => 'ID',
                'sort' => 'ID',
                'first_order' => 'desc',
                'type' => 'number',
            ),
            array(
                'id' => 'NAME',
                'name' => Loc::getMessage("FIO"),
                'sort' => 'NAME',
                'type' => 'string',
                'default' => true,
            ),
            array(
                'id' => 'PREVIEW_TEXT',
                'name' => Loc::getMessage("PHONE"),
                'sort' => 'PREVIEW_TEXT',
                'type' => 'string',
                'default' => true,
            ),
        );
        return $ALL;
    }
    // проверяем установку модуля «Информационные блоки»
    // (метод подключается внутри класса try...catch)
    protected function checkModules()
    {
        // если модуль не подключен
        if (!Loader::includeModule('iblock'))
            // выводим сообщение в catch
            throw new SystemException(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
    }
    protected function getResult()
    {
        if ($this->startResultCache()) {
            //$this->arResult = $this->AllKontact($this->arParams["IBLOCK_ID"]);
            $this->arResult["IBLOCK_ID"]=$this->arParams["IBLOCK_ID"];
        }
        $this->IncludeComponentTemplate();
    }
}