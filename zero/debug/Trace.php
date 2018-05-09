<?php
/**
 * Created by PhpStorm.
 * User: Unixeno
 * Date: 2018/5/7
 * Time: 17:31
 */

namespace zero\debug;


class Trace {
    private static $start_time;
    private static $time_list = array();

    public static function start() {
        self::$start_time = microtime(true);
        array_push(self::$time_list, ['event' => 'starting', 'time' => '0.000']);
    }

    public static function tick($event) {
        array_push(self::$time_list, ['event' => $event,
            'time' => round(1000 * (microtime(true) - self::$start_time), 3)]);
    }

    public static function getTimeList() {
        return self::$time_list;
    }

    public static function printTime() {
        $html = '<h3>Tracing:</h3><table border="1" cellspacing="0">
<thead>
<tr>
<th>event</th>
<th>timestamp</th>
</tr>
</thead>';
        foreach (self::$time_list as $item) {
            $html .= '<tr>
<td>'.$item['event'].'</td>
<td>'.$item['time'].'ms</td>
</tr>';
        }
        $html .= '</table>';
        return $html;
    }
}