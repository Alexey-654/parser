<?php

require_once __DIR__ . "/../vendor/autoload.php";

use GuzzleHttp\Client;
use DiDom\Document;
use DiDom\Query;

use function Parser\Func\trimURL;

$queryString = "застройщики краснодар";
$page = "&p=0";
$url = "https://www.yandex.ru/search/?text=" . $queryString . "&lr=35";

$connectOptions = [
    'verify' => __DIR__ . '/../ssl/cacert.pem',
    'connect_timeout' => 2.14
];

$client = new Client();
$responses = [];

for ($i = 0; $i < 1; $i++) {
    $url = "https://www.yandex.ru/search/?text=" . $queryString . "&lr=35" . "&p=" . $i;
    $responses[$i] = $client->request('GET', $url, $connectOptions);
}

// $response = $client->request('GET', $url, ['verify' => 'C:\Users\User\Desktop\parser\ssl\cacert.pem']);
$body = $responses[0]->getBody();

// file_put_contents( __DIR__ . "\..\data\yandexBodyResponse.html", $body);
// $body = file_get_contents(__DIR__ . "\..\data\yandexBodyResponse.html");

// for ($i = 0, $i < 2, $i++) {

// }

$document = new Document((string) $body);
$nodeLinks = $document->find('/html/body/div[3]/div[1]/div[2]/div[1]/div[1]/ul/li/div/div[1]/div[1]/a', Query::TYPE_XPATH);

$links = [];
foreach ($nodeLinks as $nodeLink) {
    if (!mb_strstr($nodeLink->getAttribute('href'), "yabs.yandex.ru")) {
        $host = trimURL($nodeLink->getAttribute('href'));
        $links[] = $host;
    }
}

$result = json_encode($links, JSON_PRETTY_PRINT);

file_put_contents(__DIR__ . "/../data/websites.json", $result, FILE_APPEND);
