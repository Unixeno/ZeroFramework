<?php
/**
 * Created by PhpStorm.
 * User: Unixeno
 * Date: 2018/5/7
 * Time: 17:30
 */

namespace zero\debug;


class DebugCore {
    private $message_list = array();

    public function __construct($level) {

    }

    public function errorHandler($errno, $errstr, $errfile, $errline) {
        if ($errno == E_USER_ERROR) {
            array_push($this->message_list, ['type' => 'E_USER_ERROR',
                'message' => $errstr,
                'file'    => $errfile,
                'line'    => $errline
            ]);
            $this->errorPrinter(True);
            exit(1);
        } elseif ($errno == E_ERROR) {
            array_push($this->message_list, ['type' => 'E_ERROR',
                'message' => $errstr,
                'file'    => $errfile,
                'line'    => $errline
            ]);
            $this->errorPrinter(True);
            exit(1);
        }



        switch ($errno) {
            case E_USER_WARNING:
                $type = 'E_USER_WARNING';
                break;
            case E_USER_NOTICE:
                $type = 'E_USER_NOTICE';
                break;
            case E_NOTICE:
                $type = 'E_NOTICE';
                break;
            case E_DEPRECATED:
                $type = 'E_DEPRECATED';
                break;
            case E_RECOVERABLE_ERROR:
                $type = 'E_RECOVERABLE_ERROR';
                break;
            case E_WARNING:
                $type = 'E_WARNING';
                break;
            default:
                $type = 'E_OTHER';
        }
        array_push($this->message_list, ['type' => $type,
            'message' => $errstr,
            'file'    => $errfile,
            'line'    => $errline
        ]);
        return true;
    }

    public function errorPrinter($printStake = false) {
        if (count($this->message_list) == 0)
            return;
        echo '---------------------------------------<br>';
        echo '<h2>It must be something wrong! :(</h2>';
        echo '<h3>Messages:</h3>';
        echo '<table border="1" cellspacing="0"><tr><th>Type</th><th>File and Lines</th><th>Message</th></tr>';
        $message = array_pop($this->message_list);
        while ($message != NULL) {
            echo '<tr><td>'.$message['type'].'</td>
<td>'.$message['file'].':'.$message['line'].'</td>
<td>'.$message['message'].'</td>
</tr>';
            $message = array_pop($this->message_list);
        }
        echo '</table>';
        if ($printStake) {
            $call_stake = debug_backtrace();
            array_shift($call_stake);
            array_shift($call_stake);
            echo '<h3>Call Stake:</h3>';
            echo '<pre>';
            print_r($call_stake);
            echo '</pre>';
        }
    }
}