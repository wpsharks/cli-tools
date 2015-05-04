<?php
/**
 * PHAR Stub.
 *
 * @since 150424 Initial release.
 */
namespace WebSharks\CliTools;

require_once dirname(dirname(__FILE__)).'/vendor/autoload.php';

const GLOBAL_NS      = 'ws';
$GLOBALS[GLOBAL_NS]  = null;
$GLOBALS[GLOBAL_NS]  = new Ws();
