<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?><?$APPLICATION->IncludeComponent(
    "kontacts",
    "",
    Array(
        "CACHE_TYPE" => "N",
        "IBLOCK_ID" => 16
    )
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>