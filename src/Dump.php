<?php

namespace Source;

class Dump
{
    public static function dda(mixed $var)
    {
        header("Content-Type: application/json");
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
        $file = $backtrace['file'];
        $line = $backtrace['line'];

        if (is_array($var)) {
            $size = 'Array [' . count($var) . '] ';
        } elseif (is_string($var)) {
            $size = 'Srting [' . strlen($var) . '] ';
        } elseif (is_bool($var)) {
            $size = 'Boolean ';
        } elseif (is_int($var)) {
            $size = 'Integer ';
        } else {
            $size = 'unkwon var ';
        }

        $res = ['file' => $file, 'line' => $line, $size => $var];

        echo (json_encode($res));

        ob_start();
        var_dump($var);
        $log = ob_get_clean();
        self::logWithFileAndLine($log, $file, $line, 'json');

        die;
    }

    public static function dd(mixed $var)
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
        $file = $backtrace['file'];
        $line = $backtrace['line'];


        echo "<style>html{background-color: #000;}</style><div style='position: absolute; z-index: 99999999999999; background-color: #000; color: #fff; padding: 10px;'>";
        echo "<p style='white-space: normal; max-width: 95vw;font-family: \"Fira Code\"; font-size: 1.3rem;'>Appel de dd() dans le fichier <span style='background-color: #00f; padding-inline: 5px'>$file</span> à la ligne <span style='background-color: #00f; padding-inline: 5px'>$line</span> :</p>";
        echo "<pre style=' max-width: 95vw; padding-block: 15px; padding-inline: 10px; background-color: #222; color: #fff; font-size: 1.3rem; line-height: 2rem; font-family: \"Fira Code\"; letter-spacing: 0.05rem;'>";

        ob_start();
        var_dump($var);
        $log = ob_get_clean();
        echo $log;

        echo "</pre>";
        echo "</div>";

        self::logWithFileAndLine($log, $file, $line, 'dd');
        die;
    }

    public static function vd(mixed $var)
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
        $file = $backtrace['file'];
        $line = $backtrace['line'];

        echo "<div style='position: relative; z-index: 9999; background-color: #000; color: #fff; padding: 10px;'>";
        echo "<p style='font-family: \"Fira Code\"; font-size: 1.5rem;'>Appel de vd() dans le fichier <span style='background-color: #00f; padding-inline: 5px'>$file</span> à la ligne <span style='background-color: #00f; padding-inline: 5px'>$line</span> :</p>";
        echo "<pre style='background-color: #222; color: #fff; font-size: 1.6rem; line-height: 2.2rem; font-family: \"Fira Code\"; letter-spacing: 0.05rem;'>";

        ob_start();
        var_dump($var);
        $log = ob_get_clean();
        echo $log;

        echo "</pre>";
        echo "</div>";

        self::logWithFileAndLine($log, $file, $line, 'vd');
    }

    public static function log(mixed $log, string $type = null)
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 1)[0];
        $file = $backtrace['file'];
        $line = $backtrace['line'];

        self::logWithFileAndLine($log, $file, $line, $type);
    }

    public static function logWithFileAndLine(mixed $log, string $file, int $line, $name = 'debug')
    {
        $name = $name ?? 'debug';
        // Append debug info to a log file
        $filePath = dirname(__DIR__) . "/logs/" . $name . '.log';
        // IF NOT STRING, WE MAKE A VAR_DUMP TO GET THE STRINGIFY VERSION OF THE VAR
        if (!is_string($log)) {
            ob_start();
            var_dump($log);
            $logMessage = ob_get_clean();
        } else {
            $logMessage = $log;
        }
        // LET'S THE DATE, FILE NAME AND LINE
        $log = "--: " . date('Y-m-d H:i:s') . " " . Constant::DOMAIN . PHP_EOL . ' File: ' . $file  . ' @ Line: ' . $line;
        // LET'S PUT THE MESSAGE WITH SOME SPACING ALL ARROUND
        $log .= PHP_EOL . PHP_EOL . $logMessage . PHP_EOL;
        // LET'S GET THE SYSTEME INFORMATION AT THE CURRENT EXECUTION OF THE CODE
        $log .= self::getRequestInfo();
        // FINALY, WE LOG IT IN OUR LOGING FILE
        file_put_contents($filePath, $log . PHP_EOL  . ':-- END --' . PHP_EOL . PHP_EOL, FILE_APPEND);
    }

    private static function getRequestInfo(): string
    {
        $log = PHP_EOL . "REQUEST METHOD: [\"" . $_SERVER['REQUEST_METHOD'] . "\"]";
        $log .= PHP_EOL . "REQUEST URI: [\"" . $_SERVER['REQUEST_URI'] . "\"]";
        $log .= PHP_EOL . "QUERY STRING: [\"" . $_SERVER['QUERY_STRING'] . "\"]";
        $log .= PHP_EOL . "REMOTE ADDRESS: [\"" . $_SERVER['REMOTE_ADDR'] . "\"]";
        $log .= PHP_EOL . "REQUEST PORT: [" . $_SERVER['REMOTE_PORT'] . "]";
        $log .= PHP_EOL;
        $log .= PHP_EOL . "SERVER PROTOCOL: [\"" . $_SERVER['SERVER_PROTOCOL'] . "\"]";
        $log .= PHP_EOL . "REQUEST TIME FLOAT: [" . $_SERVER['REQUEST_TIME_FLOAT'] . "]";
        $log .= PHP_EOL;
        $log .= PHP_EOL . "USER AGENT: [\"" . $_SERVER['HTTP_USER_AGENT'] . "\"]";
        $log .= PHP_EOL . "SERVER_SOFTWARE: [\"" . $_SERVER['SERVER_SOFTWARE'] . "\"]";

        return $log;
    }
}
