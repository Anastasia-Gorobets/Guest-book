<?php
function checkTags($text)
{
    //проверить, есть ли вообще теги в тексте
    $tmp = $text;
    $tmp2 = strip_tags($tmp);
    if ($tmp == $tmp2) {
        $valid = "true";
    } else {
        $cl = ["</code>", "</i>", "</strike>", "</strong>"];
        $c = preg_match_all('$</?\\w+((\\s+\\w+(\\s*=\\s*(?:".*?"|\\\'.*?\\\'|[^\'">\\s]+))?)+\\s*|\\s*)/?>$', $text, $arr, PREG_PATTERN_ORDER);
        $allTags = [];
        for ($i = 0; $i < $c; $i++) {
            array_push($allTags, $arr[0][$i]);
        }
        $openTags = $allTags;
        $countOpenTags = count($openTags);
        $closeTags = [];
//delete close tags
        for ($i = 0; $i < $countOpenTags; $i++) {
            if (in_array($openTags[$i], $cl)) {
                unset($openTags[$i]);
            }
        }
        $openTags = array_reverse($openTags);
        $countAllTags = count($allTags);
//read all tags
        $valid = "";
        for ($i = 0; $i < $countAllTags; $i++) {
            if (in_array($allTags[$i], $cl)) {//встретили закрывающий тег
                //проверить, последний запушенный элемент является таким же тегом?
                if (empty($openTags)) { //если открывающие теги закончились ...
                    $valid = "false";
                    break;
                } else {
                    $lastPushedElement = $openTags[count($openTags) - 1];
                    $repl = ["<", ">", "/"];
                    $symbolOpen = str_replace($repl, "", $lastPushedElement);
                    $symbolClose = str_replace($repl, "", $allTags[$i]);
                    if ($symbolOpen == $symbolClose) {
                        $valid = "true";
                        array_pop($openTags);
                    } else {
                        $valid = "false";
                    }
                }
            } else $valid = "false";
        }

    }
    return $valid;
}