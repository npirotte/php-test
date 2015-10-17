<?php
namespace InvoicingBundle\Services;
use InvoicingBundle\Services\HolidaysCalendar;

class EmailScheduler
{
  private $timeTable;

  private $timeExclusions;

  private $delay;

  private $end;

  public function __construct() {
    $am = array('09:00:00', '12:00:00');
    $pm = array('13:30:00', '17:00:00');
    $classicDay = array($am, $pm);

    $this->timeTable = array(
      array(), // sunday
      $classicDay, // monday
      $classicDay, // thuesday
      $classicDay, // wedesday
      $classicDay, //thurday
      $classicDay, // friday
      array(), // saturday
    );

    $this->timeExclusions = array(
      array(), // sunday
      $am, // monday
      array(), // thuesday
      array(), // wedesday
      array(), //thurday
      $pm, // friday
      array(), // saturday
    );
  }

  public function getSendDate(\DateTime $approvingDate, \DateInterval $delay)
  {
    $this->delay = $delay;
    $this->end = false;

    $date = $this->recurce($approvingDate);

    return $date;
    //return $approvingDate;
  }

  private function recurce(\DateTime $approvingDate)
  {
    $currentDay = intval($approvingDate->format('w'));
    $dayTimeTable = $this->timeTable[$currentDay];

    (int)$dayTimeTableCount = count($dayTimeTable);
    if ($dayTimeTableCount === 2)
    {
      // full working day
      $approvingDate = $this->handleFullDay($approvingDate, $dayTimeTable);
    }
    else if ($dayTimeTableCount === 1)
    {
      // half day
      $approvingDate = $this->handleHalfDay($approvingDate, $dayTimeTable[0]);
    }
    else
    {
      // no work today, see you tomorow !
      $approvingDate->modify('+ 1 day');
    }

    if(!$this->end) {
      return $this->recurce($approvingDate);
    }

    // catch exeptions
    $this->delay = new \DateInterval('PT0H'); // '00:00:00';
    $this->end = false;
    $approvingDate = $this->handleExlusions($approvingDate);
    $approvingDate = $this->handleHolidays($approvingDate);

    return $approvingDate;
  }

  /**
    * @param DateTime array
    * @return DateTime
    */
  private function handleFullDay(\DateTime $approvingDate, $dayTimeTable)
  {
    // get the end of the morning in timestamp
    $currentHour = strtotime($approvingDate->format('H:i:s'));

    // test if we are working on morning, on aftenoon or evening
    if ($currentHour < strtotime($dayTimeTable[0][1]))
    {
      // morning
      $approvingDate = $this->handleHalfDay($approvingDate, $dayTimeTable[0]);
    }
    else if ($currentHour < strtotime($dayTimeTable[1][1]))
    {
      // afternoon
      $approvingDate = $this->handleHalfDay($approvingDate, $dayTimeTable[1]);
    }
    else
    {
      // evening
      $approvingDate->modify('+ 1 day');
      // remove hours
      $approvingDate->setTime(0, 0, 0);
    }

    return $approvingDate;
  }

  /**
    * @param DateTime array
    * @return DateTime
    */
  private function handleHalfDay(\DateTime $approvingDate, $halfDayTimeTable)
  {
    $currentHour = strtotime($approvingDate->format('H:i:s'));

    // if date is before begining, we set date to the begening
    if ($currentHour < strtotime($halfDayTimeTable[0]))
    {
        $startPeriodHours = explode(':', $halfDayTimeTable[0]);
        $approvingDate->setTime($startPeriodHours[0], $startPeriodHours[1]);
    }

    //$delay = explode($this->delay)
    $approvingDate->add($this->delay);

    // test the end of th day date
    $currentHour = strtotime($approvingDate->format('H:i:s'));
    if ($currentHour > strtotime($halfDayTimeTable[1]))
    {
      $limitTime = new \DateTime($approvingDate->format('Y-m-d').' '.$halfDayTimeTable[1]);
      $this->delay = date_diff($approvingDate, $limitTime, true);
      $endPeriodHours = explode(':', $halfDayTimeTable[1]);
      $approvingDate->setTime($endPeriodHours[0], $endPeriodHours[1]);
    }
    else
    {
      $this->end = true;
    }

    return $approvingDate;
  }

  /**
    * @param DateTime
    * @return DateTime
    */
  private function handleExlusions(\DateTime $approvingDate)
  {
    $currentDay = intval($approvingDate->format('w'));
    $dayExlusion = $this->timeExclusions[$currentDay];

    // test if day has exlusions
    if (count($dayExlusion) > 0) {
      $currentHour = strtotime($approvingDate->format('H:i:s'));
      $startExclusionHour = strtotime($dayExlusion[0]);
      $endExclusionHour = strtotime($dayExlusion[1]);

      // test if we are in the exclusion
      if ($startExclusionHour <= $currentHour && $currentHour <= $endExclusionHour) {
        $newTime = explode(':', $dayExlusion[1]);
        $approvingDate->setTime($newTime[0], $newTime[1] + 1);

        // try again until we get a valid date
        $approvingDate = $this->recurce($approvingDate);
      }
    }

    // do nothing if no date modification
    return $approvingDate;
  }

  /**
    * Check if the date is in a Holidays period
    *
    * @param DateTime
    * @return DateTime
    */
  public function handleHolidays(\DateTime $approvingDate) {
    $holidaysCalendar = new HolidaysCalendar('C:/websites/invoicing-app2/data/Holidays.ics');
    $periods = $holidaysCalendar->getEventsPeriod();
    $hasChanged = false;

    foreach ($periods as $period) {
      if ($period[0] <= $approvingDate && $approvingDate < $period[1])
      {
        $hasChanged = true;
        // set approvingDate to the end of the Holidays
        $approvingDate = clone $period[1];
      }
    }

    // if date has change, redo the all test
    if($hasChanged)
    {
        $approvingDate = $this->recurce($approvingDate);
    }

    return $approvingDate;
  }
}
