<?php

class kontosecure_debug
{
    public static function log($msg)
    {
        error_log('[' . date('Y-m-d H:i:s') . '] ' . $msg . PHP_EOL, 3, '/tmp/oxid.log');
    }
}
