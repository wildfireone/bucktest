<?php
/**
 * Created by PhpStorm.
 * User: Andrew Tait (1504693)
 * Date: 26/10/2017
 * Time: 11:29
 *
 * Swim time migration script from old table (varchar) to new table (time)
 */

include('./inc/connection.inc.php');
require('./obj/swim_times.obj.php');

$conn = dbConnect();

$swim_times = new Swim_Times();

$oldTimes = $swim_times->migrateSwimTimes($conn);

$newTimes = array();

$hour = "00:";

foreach ($oldTimes as $times) {

    $time = $hour . $times['time'];


    //Convert mysql date format to UK format
    $date = new DateTime($time);
    $date->setTimezone(new DateTimeZone('Europe/London'));
    $newDate = $date->format('H:i:s.u');

    //format:   23:59:59.590000

    $removeHour = substr($newDate, 3);

    $removeSeconds = substr($removeHour, 0, 8);
    $newTime = $hour . $removeSeconds;

    $newTimes []['time'] = $newTime;

}


$count = 0;
for ($i = 0; $i < count($newTimes); ++$i) {
    $swim_times->updateSwimTimes($conn, $newTimes[$i]['time'], $i + 1);
    $count++;
}

if ($count == count($newTimes)) {
    echo 'Migration success, check mysql log for results';
} else {
    echo 'Fail';
}
