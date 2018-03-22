<?php
/**
 * Created by PhpStorm.
 * User: shobl
 * Date: 3/21/2018
 * Time: 5:23 PM
 */

$output = [
  'success' => true,
];

$url = $_POST['url'];
$content = @file_get_contents($url);

$pattern = '/entry_data/';
preg_match($pattern, $content, $matches, PREG_OFFSET_CAPTURE);
$length = strlen($content);

$start = $matches[0][1];
$start = $start + 12;
$curlyBraceCount = NULL;

for ($i = $start; $i < $length; $i++) {
    if ($curlyBraceCount === 0) {
        $endingIndex = $i;
        break;
    }
    else if ($content[$i] === '{') {
        $curlyBraceCount++;
    }
    else if ($content[$i] === '}') {
        $curlyBraceCount--;
    }
}
$output['startOfObj'] = $start;
$output['endOfObj'] = $endingIndex;

$objectStr = substr($content, $start, $endingIndex - $start);

$object = json_decode($objectStr);
$commentObj = $object->PostPage[0]->graphql->shortcode_media->edge_media_to_comment->edges;

$output['comments'] = $commentObj;
$output['commentCount'] = $object->PostPage[0]->graphql->shortcode_media->edge_media_to_comment->count;

$jsonOutput = json_encode($output);
print($jsonOutput);
?>