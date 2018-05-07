<?php
/**
 * Created by PhpStorm.
 * User: Unixeno
 * Date: 2018/5/7
 * Time: 17:31
 */

namespace Zero\Debug;


class Trace {
    private $start_time;
    private $time_list = array();

    public function __construct() {
        $this->start_time = microtime(true);
        array_push($this->time_list, ['event' => 'starting', 'time' => '0.000']);
    }

    public function tick($event) {
        array_push($this->time_list, ['event' => $event,
            'time' => round(1000 * (microtime(true) - $this->start_time), 3)]);
    }

    public function getTimeList() {
        return $this->time_list;
    }

    public function printTime() {
        $html = '<h3>Tracing:</h3><table border="1" cellspacing="0">
<thead>
<tr>
<th>event</th>
<th>timestamp</th>
</tr>
</thead>';
        foreach ($this->time_list as $item) {
            $html .= '<tr>
<td>'.$item['event'].'</td>
<td>'.$item['time'].'ms</td>
</tr>';
        }
        $html .= '</table>';
        return $html;
    }
}