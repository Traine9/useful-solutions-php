<?php
//php 7.1 code

/**
 * example of solution's date sorting
 * ...
 * 30.10.2022 02:00:00
 * 30.10.2022 02:15:00
 * 30.10.2022 02:30:00
 * 30.10.2022 02:45:00
 * 30.10.2022 03:00:00
 * 30.10.2022 02:00:00
 * 30.10.2022 02:15:00
 * 30.10.2022 02:30:00
 * 30.10.2022 02:45:00
 * 30.10.2022 03:00:00
 * ...
 */
include __DIR__ .'/PointPeriod.php';
date_default_timezone_set('Europe/Berlin');

$start = DateTime::createFromFormat('!Y-m-d', '2022-01-01');
$interval = new \DateInterval('PT15M');
$end = DateTime::createFromFormat('!Y-m-d', '2023-01-01');
$iterator = new DatePeriod($start, $interval, $end);
$dstDuplicatesStart = DateTime::createFromFormat('!Y-m-d H:i:s', '2022-10-30 02:00:00');
$dstDuplicatesEnd = DateTime::createFromFormat('!Y-m-d H:i:s', '2022-10-30 03:00:00');
$periods = [];
foreach ($iterator as $date) {
    $period = new PointPeriod();
    $period->setDate($date);
    $period->setTime($date);
    //too lazy to extract clear date and time :)
    //dst second hour values need to be BEFORE non dst with same time
    if ($date <= $dstDuplicatesEnd && $date >= $dstDuplicatesStart) {
        $period2 = clone $period;
        $period2->setIsDstSecondHour(true);
        $periods[] = $period2;
    }
    $periods[] = $period;

}


$tmp = [];
$dstHours = [];
$timezoneUTC = new \DateTimeZone('UTC');
$timeZoneBerlin = new \DateTimeZone('Europe/Berlin');
/*
 *  In some european metrics hour is 60 minutes so 03:00 have two values as start of 03:00 hour and as end of 02:00 hour.
 *  I added 1S to interval to handle collision 03:00(end of hour) with real 03:00 (it's not real dst but in my project logic it was)
 *  If you don't have to handle logic above - remove 1S part from interval
*/
$interval = new \DateInterval('PT1H1S');
$intervalHour = new \DateInterval('PT1H');


foreach ($periods as $key => $element) {
    /*
     * Array must be pre sorted dateTime ASC, isDstSecondHour DESC
     *
     * Here we convert GMT time with daylight saving (DST) to UTC without DST yeah!
     * For example at 2022-10-30 berlin time we have 02:00-02:59 period twice (25 hours day in dst time)
     * IsDstSecondHour means that this is second duplicated period
     * in this solution we find first period and sub one hour and fill gap created by DST
     * because when DateTime convert time to utc it sets any time as second period
     *
     * 0.6 seconds on 36000 array
     */

    $time = \DateTime::createFromFormat(
        'Y-m-d H:i:s',
        $element->getDate()->format('Y-m-d ') . $element->getTime()->format('H:i:s'),
        $timeZoneBerlin
    );
    $isDst = (int) $time->format('I');

    $time->setTimezone($timezoneUTC);
    $timestamp = $time->getTimestamp();

    /*
     * When convert dates we have empty period 00:00 - 01:00 which was shifted from march (winter -> summer dst)
     * We shift all dates that not in summer dst 1 hour back
     */
    if (!$isDst) { //is in dst interval?
        $time->sub($intervalHour);
    }

    // but if we find duplicate (IsDstSecondHour) we need to create empty period like 00:00 - 01:00 for values
    // which duplicated but IsDstSecondHour = 0
    if ($element->getIsDstSecondHour()) {
        $dstHours[$timestamp] = true;
        $time->add($interval);
    } elseif (!empty($dstHours) && !isset($dstHours[$timestamp])) {
        $time->add($interval);
    }

    $tmp[$key] = $time->getTimestamp();
}
//fastest way to sort is key sort, here we use key sort and plain array sort
array_multisort($tmp, SORT_ASC, SORT_REGULAR, $periods);

print_r(array_map('strval',$periods));
