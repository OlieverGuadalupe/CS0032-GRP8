<?php
class Logger {
    private static $logFile = __DIR__ . '/logs/app.log'; // Ensure this dir is NOT web-accessible

    public static function log($message, $level = 'INFO', $context = []) {
        $timestamp = date('Y-m-d H:i:s');
        $contextString = !empty($context) ? json_encode($context) : '';
        $formattedMessage = "[$timestamp] [$level] $message $contextString" . PHP_EOL;
        
        // Ensure log directory exists
        if (!is_dir(dirname(self::$logFile))) {
            mkdir(dirname(self::$logFile), 0755, true);
        }

        file_put_contents(self::$logFile, $formattedMessage, FILE_APPEND);
    }

    public static function error($message, $context = []) { self::log($message, 'ERROR', $context); }
    public static function info($message, $context = []) { self::log($message, 'INFO', $context); }
    public static function debug($message, $context = []) { self::log($message, 'DEBUG', $context); }
}