<?php

namespace Parser\Func;

function trimURL($url)
{
    $parts = mb_split("/", $url);
    $host = $parts[0] . "//" . $parts[2] . "/";
    return $host;
}
