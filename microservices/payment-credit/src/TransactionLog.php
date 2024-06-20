<?php
class TransactionLog {
    
    const LOG_FILE_PATH = 'logs.txt';

    public static function write($message) 
    {
        $timestamp = date('Y-m-d H:i:s');
        $logEntry = "[$timestamp] $message" . PHP_EOL;

        file_put_contents(self::LOG_FILE_PATH, $logEntry, FILE_APPEND);
    }
}