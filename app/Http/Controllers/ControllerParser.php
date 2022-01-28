<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller as BaseController;
use SplFileInfo;

class ControllerParser
{
    const CRAWLERS = [
        'Google',
        'Bing',
        'Baidu',
        'Yandex',
        'Yahoo',
    ];
    const CODES = [
        '200',
        '301',
        '302',
        '304',
        '403',
        '404',
        '500',
        '502',
        '503',
        '504'
    ];
   
    public static function parserCheck() {
       
        $info = new SplFileInfo($_FILES['logFile']['name']);
        // Проверка на расширение файла
        if (empty($_FILES['logFile']['name']) ) {
            return redirect('/')->with('error', 'Упс. Файл отсутствует.');
        }
        if ($info->getExtension() != 'txt' ) {
            return redirect('/')->with('error', 'Упс. Файл не является txt.');
        }
       return self::parserGo();

    }
 
    public static function parserGo() {
           
        $jsonLog = array(
            'views' => '',
            'urls' => '',
            'traffic' => '',
            'crawlers' =>
            array_fill_keys(self::CRAWLERS, 0),
            'statusCodes' =>
            array_fill_keys(self::CODES, 0),
            );
        $logFile = file_get_contents($_FILES['logFile']['tmp_name']);
        // Получили Массив
        $logFileArray = str_replace("\r\n", " ", $logFile);
        $logFileArray = explode(' ',  $logFileArray);
        // Всего
        preg_match_all("#[0-9]{1,2}[/][A-z]{3,6}[/]20[0-9]{2}#", implode($logFileArray), $matches, PREG_OFFSET_CAPTURE);
        $jsonLog['views'] = count($matches[0]);
        // Уникальные урлы
        preg_match('$(http|ftp|https)://([\w_-]+(?:(?:\.[\w_-]+)+))([\w.,@?^=%&:/~+#-]*[\w@?^=%&/~+#-])?$', implode($logFileArray), $matches, PREG_OFFSET_CAPTURE); 
        foreach ($matches as $matchesItem) {
            $unicUrl[] = $matchesItem[0];
        }
        $jsonLog['urls']=count(array_unique($unicUrl));
        // Объем трафика
        $logFileArray1 = explode('\r\n', $logFile);
        preg_match_all("#[ ][0-5]{3}[ ][0-9]{1,10}#",  $logFileArray1[0], $matches);
        foreach ($matches[0] as $matchesItem) {
            $js[] = preg_replace('#[ ][0-5]{3}[ ]#', "",  $matchesItem);
        }
        $jsonLog['traffic']=array_sum($js);
        //Подсчет кодов
        foreach (self::CODES as $codesItem) {
            $jsonLog['statusCodes'][$codesItem] = count(array_keys($logFileArray, $codesItem));
        }
        //Подсчет поисковиков
        foreach (self::CRAWLERS as $crawlersItem) {
            $a[$crawlersItem] = preg_match_all("/$crawlersItem/", implode($logFileArray), $matches, PREG_OFFSET_CAPTURE);
            $jsonLog['crawlers'][$crawlersItem] = $a[$crawlersItem];      
        }
        
        return redirect('/')->with('json', json_encode($jsonLog));

    }
}
