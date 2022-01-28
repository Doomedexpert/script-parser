<?php
namespace App\Http\Controllers;

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
       
        $fileInfo = new SplFileInfo($_FILES['logFile']['name']);
        if (empty($_FILES['logFile']['name']) ) {
            return redirect('/')->with('error', 'Упс. Файл отсутствует.');
        }
        if ($fileInfo->getExtension() != 'txt' ) {
            return redirect('/')->with('error', 'Упс. Файл не является txt.');
        }
       return self::parserGo();

    }
 
    public static function parserGo() {
           
        $parsInfo = array(
            'views' => '',
            'urls' => '',
            'traffic' => '',
            'crawlers' =>
            array_fill_keys(self::CRAWLERS, 0),
            'statusCodes' =>
            array_fill_keys(self::CODES, 0),
        );

        $logFile = file_get_contents($_FILES['logFile']['tmp_name']);
        $fileInfo =  explode(' ', str_replace("\r\n", " ", $logFile));
        $fileInfoForTraffic = explode('\r\n', $logFile);

        preg_match_all("#[0-9]{1,2}[/][A-z]{3,6}[/]20[0-9]{2}#", implode($fileInfo), $matches, PREG_OFFSET_CAPTURE);
        $parsInfo['views'] = count($matches[0]);

        preg_match('$(http|ftp|https)://([\w_-]+(?:(?:\.[\w_-]+)+))([\w.,@?^=%&:/~+#-]*[\w@?^=%&/~+#-])?$', implode($fileInfo), $matches, PREG_OFFSET_CAPTURE); 
        foreach ($matches as $matchesItem) {
            $unicUrl[] = $matchesItem[0];
        }
        $parsInfo['urls']=count(array_unique($unicUrl));

        preg_match_all("#[ ][0-5]{3}[ ][0-9]{1,10}#",  $fileInfoForTraffic[0], $matches);
        foreach ($matches[0] as $matchesItem) {
            $trafics[] = preg_replace('#[ ][0-5]{3}[ ]#', "",  $matchesItem);
        }
        $parsInfo['traffic']=array_sum($trafics);

        foreach (self::CODES as $codesItem) {
            $parsInfo['statusCodes'][$codesItem] = count(array_keys($fileInfo, $codesItem));
        }

        foreach (self::CRAWLERS as $crawlersItem) {
            $crawlers[$crawlersItem] = preg_match_all("/$crawlersItem/", implode($fileInfo), $matches, PREG_OFFSET_CAPTURE);
            $parsInfo['crawlers'][$crawlersItem] = $crawlers[$crawlersItem];      
        }
        
        return redirect('/')->with('json', json_encode($parsInfo));

    }
}
