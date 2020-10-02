<?php

function eSMAutoload($class)
{
    include __DIR__.'/libs/utils/'.$class.'.php';
}

spl_autoload_register('eSMAutoload');