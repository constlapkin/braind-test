<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
    "GROUPS" => array(),
    "PARAMETERS" => array(
        "CURRENCY_SELECTION" => array(
            "PARENT" => "BASE",
            "NAME" => "Выбор валюты",
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "VALUES" => array(
                "USD" => "USD",
                "EUR" => "EUR",
            ),
            "SIZE" => 2,
            "DEFAULT" => "USD",
        ),
        "CACHE_TIME" => array("DEFAULT"=>"36000"),
    ),
);

?>