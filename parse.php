<?php
error_reporting(0);
class city
{
    public $name;
    public $region;
    public $phoneCode;

    function __construct($name, $region, $phoneCode)
    {
        $this->name = $name;
        $this->region = $region;
        $this->phoneCode = $phoneCode;
    }
}

function getHtml($url, $post = null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13');
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_COOKIE, 'NID=67=pdjIQN5CUKVn0bRgAlqitBk7WHVivLsbLcr7QOWMn35Pq03N1WMy6kxYBPORtaQUPQrfMK4Yo0vVz8tH97ejX3q7P2lNuPjTOhwqaI2bXCgPGSDKkdFoiYIqXubR0cTJ48hIAaKQqiQi_lpoe6edhMglvOO9ynw; PREF=ID=52aa671013493765:U=0cfb5c96530d04e3:FF=0:LD=en:TM=1370266105:LM=1370341612:GM=1:S=Kcc6KUnZwWfy3cOl; OTZ=1800625_34_34__34_; S=talkgadget=38GaRzFbruDPtFjrghEtRw; SID=DQAAALoAAADHyIbtG3J_u2hwNi4N6UQWgXlwOAQL58VRB_0xQYbDiL2HA5zvefboor5YVmHc8Zt5lcA0LCd2Riv4WsW53ZbNCv8Qu_THhIvtRgdEZfgk26LrKmObye1wU62jESQoNdbapFAfEH_IGHSIA0ZKsZrHiWLGVpujKyUvHHGsZc_XZm4Z4tb2bbYWWYAv02mw2njnf4jiKP2QTxnlnKFK77UvWn4FFcahe-XTk8Jlqblu66AlkTGMZpU0BDlYMValdnU; HSID=A6VT_ZJ0ZSm8NTdFf; SSID=A9_PWUXbZLazoEskE; APISID=RSS_BK5QSEmzBxlS/ApSt2fMy1g36vrYvk; SAPISID=ZIMOP9lJ_E8SLdkL/A32W20hPpwgd5Kg1J');
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 20);
    if (!empty($post)) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function minfinParse($minfinPage)
{
    $doc = new DOMDocument();
    $doc->loadHTML($minfinPage);

    $tables = $doc->getElementsByTagName('table');
    if ($tables->length > 0) {
        foreach ($tables as $table) {
            $tbody = $table->getElementsByTagName('tbody');
            $trs = $tbody[0]->getElementsByTagName('tr');
            foreach ($trs as $tr) {
                $tds = $tr->getElementsByTagName('td');
                $tempArr = array();
                foreach ($tds as $td) {
                    $tempArr[] = trim($td->nodeValue);
                }
                $citiesArray[] = new city($tempArr[0], $tempArr[1], $tempArr[2]);
            }
        }
    }
    return $citiesArray;
}


$citiesArray = minfinParse(getHtml('https://minfin.com.ua/telecom/kody-gorodov-ukrainy'));
for ($i = 1; $i <= 110; $i++) {
    $citiesArray[] = minfinParse(getHtml('https://minfin.com.ua/telecom/kody-gorodov-ukrainy?field_gorod_value=&field_phone_code_value=&page=' . $i));
}

file_put_contents('minfinCodes.json', json_encode($citiesArray));
