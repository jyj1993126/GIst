<?php
require('vendor/autoload.php');

$valid = true;
$base = 'https://ninghao.net';
$api = 'https://ninghao.net/blog?page=%d';
$page = 1;

$filepath = 'results/ninghaoCrawler.csv';
$origin = file_get_contents($filepath);

list($title) = explode(',', substr($origin, 0, strpos($origin, PHP_EOL)));
$title = trim($title,'"');

$file = fopen($filepath, 'w+');

while( $valid )
{
    $dom = new simple_html_dom(file_get_contents(sprintf($api, $page ++)));
    $nodes = $dom->find('.node-title a');
    $valid = !empty($nodes);
    foreach($nodes as $node )
    {
        if( $node->innerText() == $title )
        {
            $valid = false;
            break;
        }
        fputcsv($file, [$node->innerText(), $base . $node->getAttribute('href')]);
    }
    sleep(1);
}

fclose($file);

file_put_contents($filepath, $origin, FILE_APPEND );
