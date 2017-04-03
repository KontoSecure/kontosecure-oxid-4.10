<?php

class kontosecure_base
{
    public static function l($msg)
    {
        error_log('[' . date('Y-m-d H:i:s') . '] ' . $msg . PHP_EOL, 3, '/tmp/oxid.log');
    }
}
