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
        var_dump($var);
        echo "</pre>";
        echo "</div>";

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
        var_dump($var);
        echo "</pre>";
        echo "</div>";
    }
}
