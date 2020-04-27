<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * This is custom component of bitrix:currency.rates
 */

if (!isset($arParams["CACHE_TIME"]))
    $arParams["CACHE_TIME"] = 36000;
$arParams["CACHE_TIME"] = intval($arParams["CACHE_TIME"]);


if($this->StartResultCache()) {
    $arResult = array();
    $bWarning = false;

    // Request data for the current day
    $obHttp = new CHTTP();
    $obHttp->Query(
        'GET',
        'www.cbr.ru',
        80,
        "/scripts/XML_daily.asp",
        false,
        '',
        'N'
    );

    $strQueryText = $obHttp->result;
    if (empty($strQueryText))
        $bWarning = true;

    if (!$bWarning)
    {
        if (SITE_CHARSET != "windows-1251")
            $strQueryText = $APPLICATION->ConvertCharset($strQueryText, "windows-1251", SITE_CHARSET);

        require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/xml.php");

        $strQueryText = preg_replace("#<!DOCTYPE[^>]+?>#i", "", $strQueryText);
        $strQueryText = preg_replace("#<"."\\?XML[^>]+?\\?".">#i", "", $strQueryText);

        $objXML = new CDataXML();
        $objXML->LoadString($strQueryText);
        $arData = $objXML->GetArray();

        $arResult["CURRENCY"] = array();

        if (!empty($arData) && is_array($arData))
        {
            if (!empty($arData["ValCurs"]) && is_array($arData["ValCurs"]))
            {
                if (!empty($arData["ValCurs"]["#"]) && is_array($arData["ValCurs"]["#"]))
                {
                    if (!empty($arData["ValCurs"]["#"]["Valute"]) && is_array($arData["ValCurs"]["#"]["Valute"]))
                    {
                        $arCBVal = $arData["ValCurs"]["#"]["Valute"];
                        foreach($arCBVal as &$arOneCBVal)
                        {
                            if (in_array($arOneCBVal["#"]["CharCode"][0]["#"], $arParams["CURRENCY_SELECTION"]))
                            {
                                $arCurrency = array(
                                    "CURRENCY" => $arOneCBVal["#"]["CharCode"][0]["#"],
                                    "RATE_CNT" => intval($arOneCBVal["#"]["Nominal"][0]["#"]),
                                    "RATE" => floatval(str_replace(",", ".", $arOneCBVal["#"]["Value"][0]["#"]))
                                );

                                $arResult["CURRENCY"][] = array(
                                    "FROM" => CCurrencyLang::CurrencyFormat($arCurrency["RATE_CNT"], $arCurrency["CURRENCY"], true),
                                    "BASE" => CCurrencyLang::CurrencyFormat($arCurrency["RATE"], "RUB", true),
                                );
                            }
                        }
                        if (isset($arOneCBVal))
                            unset($arOneCBVal);
                    }
                    else {
                        $bWarning = true;
                    }
                }
                else {
                    $bWarning = true;
                }
            }
            else {
                $bWarning = true;
            }
        }
        else {
            $bWarning = true;
        }
    }
    else {
        $bWarning = true;
    }
    if($bWarning) {
        $this->AbortResultCache();
        $arResult["CURRENCY"][] = array(
            "FROM" => "Ошибка",
            "BASE" => "Сообщите пожалуйста нам об этом"
        );
    }

    $this->IncludeComponentTemplate();
}

?>