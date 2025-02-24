<?php

/**
 * @description
 * Tools for debug info print data
 */
function pr(mixed $obj, bool $visibleForEveryone = false) : bool
{
    return \Core\Tools\Debug::pr($obj, $visibleForEveryone);
}
