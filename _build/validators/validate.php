<?php
$minPhp = '7.2.0';
if (version_compare(PHP_VERSION, $minPhp, '<')) {
    $modx->log(modX::LOG_LEVEL_ERROR, 'PHP >= '.$minPhp.' is required.');
    return false;
}
$modx->log(modX::LOG_LEVEL_INFO, 'Validation passed.');
return true;
