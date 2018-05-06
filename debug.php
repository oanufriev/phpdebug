<?php

/**
 * Debug class :: colletcion of utilities to work with browser JSConsole, alarms and debug printing
 */
defined('DEBUG') OR define('DEBUG', FALSE);
defined('DEBUG_PAGE') OR define('DEBUG_PAGE', FALSE);

class debug {

    const NL = "\r\n";
    public static $config = [
        'extension'      => 'log',
        'folder'         => 'log',
        'dateFormat'     => 'Y-m-d H:i:s',
        'path'           => '/../..',
    ];

    public static function isAjax() {
        return (strtolower(filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest') ? TRUE : FALSE;
    }

    /**
    * Inserts jscript snippet that will show data in js developer console
    * no tests for console presense is made though
    * @param $key any string
    * @param $value any php value -> gets json_encoded @TODO don't encode json
    * @param $type string log || error || warn || info || table
    */
    public static function jsconsole($key, $value = NULL, $type = 'log') {
        echo '<script type="text/javascript">' . self::NL;
        $msg = "console.$type('PHP: $key'";
        if (isset($value)) {
            $value_json_encoded = json_encode($value);
            list($usec, $sec) = explode(" ", microtime());
            $uuid = $sec . '_' . $usec * 100000000;
            echo "let phpvalue_$uuid = $value_json_encoded;";
            $msg .= ", phpvalue_$uuid";
        }
        $msg .= ");";
        echo $msg . self::NL;
        echo '</script>' . self::NL;
    }

    /**
     * Builds console.log on client
     * @param type $key
     * @param type $value
     */
      public static function consolelog($key, $value = NULL) {
        if (!self::isAjax()) {
            self::jsconsole($key, $value);
        }
    }  

    /**
     * Builds console.error on client
     * @param type $key
     * @param type $value
     */
    public static function consoleerror($key, $value = NULL) {
        if (!self::isAjax()) {
            self::jsconsole($key, $value, 'error');
        }
    }

    /**
     * Builds console.warn on client
     * @param type $key
     * @param type $value
     */
    public static function consolewarn($key, $value = NULL) {
        if (!self::isAjax()) {
            self::jsconsole($key, $value, 'warn');
        }
    }

    /**
     * Switchable console log. Will be silenced if DEBUG is not defined
     * @param type $key
     * @param type $data
     * @param type $debug
     */
    public static function info($key, $data = NULL, $debug = FALSE) {
        if (!self::isAjax() && ($debug || DEBUG || DEBUG_PAGE)) {
            self::consolelog($key, $data);
        }
    }

    /**
     * Inserts JS code that fires alert on client.
     * @param type $data
     */
    public static function alert($data) {
        if (is_array($data) || is_object($data)) {
            echo("<script>window.alert('PHP: " . json_encode($data) . "');</script>");
        } else {
            echo("<script>window.alert('PHP: " . $data . "');</script>");
        }
    }

    /**
     * Dumps data into PHP output if DEBUG
     * @param type $data
     * @param type $debug
     */
    public static function dump($data, $debug = FALSE) {
        if ($debug || DEBUG || DEBUG_PAGE) {
            print('<pre>');
            var_dump($data);
            print('</pre>');
        }
    }

    /**
     * Same as dump with a different output formatting
     * @param type $key
     * @param type $value
     * @param type $debug
     */
    public static function display($key, $value, $debug = FALSE) {
        if ($debug || DEBUG || DEBUG_PAGE) {
            echo '' . $key . " = ";
            switch (gettype($value)) {
                case 'string' :
                    echo $value;
                    break;
                case 'array' :
                case 'object' :
                default :
                    echo '';
                    print_r($value);
                    echo '';
                    break;
            }
        }
    }

    /**
    * Logs data from PHP into log file if DEBUG
    * @param type string builds file-name
    * @param msg string logged
    * @param level string added to msg for logging
    */
    public static function log($type, $msg, $level = 'DEBUG') {
        //cleanse message
        $msg = (is_array($msg) || is_object($msg)) ? json_encode($msg) : $msg;
        $msg = (is_null($msg) || is_bool($msg)) ? var_export($msg, true) : $msg;

        // from config
        $conf = self::$config;

        $logdir = realpath(__DIR__ . $conf['path']) . DIRECTORY_SEPARATOR . $conf['folder'] . DIRECTORY_SEPARATOR . date('Ym');
        if (!is_dir($logdir)) {
            mkdir($logdir);
        }
        $logfile = $logdir . DIRECTORY_SEPARATOR . $type . '_' . date('Ymd') . '.' . $conf['extension'];
        $res = '[' . date($conf['dateFormat']) . '] [' . $level . '] ' . $msg . PHP_EOL;
        file_put_contents($logfile, $res, FILE_APPEND);
    }

    // @TODO client-side messages logged into server-side log

}

?>