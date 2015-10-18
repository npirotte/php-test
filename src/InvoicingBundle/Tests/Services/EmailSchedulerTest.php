<?php

namespace InvoicingBundle\Tests\Services;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use InvoicingBundle\Services\EmailScheduler;
use InvoicingBundle\Services\HolidaysCalendar;

class EmailSchedulerTest extends \PHPUnit_Framework_TestCase
{
    // simple test case : morning should output afternoon
    public function testIndex()
    {
        $date = new \DateTime('2015-10-05 9:00:00');
        $expected = new \DateTime('2015-10-05 14:30:00');
        $interval = new \DateInterval('PT4H'); // '04:00:00';

        //  week calendar and monday / friday exclusions are hardcoded, but could be loaded from database, a config file, an iCal file...
        $am = array('09:00:00', '12:00:00');
        $pm = array('13:30:00', '17:00:00');
        $classicDay = array($am, $pm);

        $timeTable = array(
          array(), // sunday
          $classicDay, // monday
          $classicDay, // thuesday
          $classicDay, // wedesday
          $classicDay, //thurday
          $classicDay, // friday
          array(), // saturday
        );

        $timeExclusions = array(
          array(), // sunday
          $am, // monday
          array(), // thuesday
          array(), // wedesday
          array(), //thurday
          $pm, // friday
          array(), // saturday
        );

        // create a holliday callendar object
        $holidaysCalendar = new HolidaysCalendar('C:/websites/invoicing-app2/data/Holidays.ics');

        $scheduler = new EmailScheduler($timeTable, $timeExclusions, $holidaysCalendar);
        $result = $scheduler->getSendDate($date, $interval);

        $this->assertEquals($result, $expected);
    }

    // day jump case : afternoon should output next mornign
    public function testDayJump()
    {
        //$client = static::createClient();
        $date = new \DateTime('2015-10-05 14:30:00');
        $expected = new \DateTime('2015-10-06 10:30:00');
        $interval = new \DateInterval('PT4H'); // '04:00:00';

        //  week calendar and monday / friday exclusions are hardcoded, but could be loaded from database, a config file, an iCal file...
        $am = array('09:00:00', '12:00:00');
        $pm = array('13:30:00', '17:00:00');
        $classicDay = array($am, $pm);

        $timeTable = array(
          array(), // sunday
          $classicDay, // monday
          $classicDay, // thuesday
          $classicDay, // wedesday
          $classicDay, //thurday
          $classicDay, // friday
          array(), // saturday
        );

        $timeExclusions = array(
          array(), // sunday
          $am, // monday
          array(), // thuesday
          array(), // wedesday
          array(), //thurday
          $pm, // friday
          array(), // saturday
        );

        // create a holliday callendar object
        $holidaysCalendar = new HolidaysCalendar('C:/websites/invoicing-app2/data/Holidays.ics');

        $scheduler = new EmailScheduler($timeTable, $timeExclusions, $holidaysCalendar);
        $result = $scheduler->getSendDate($date, $interval);

        $this->assertEquals($result, $expected);
    }

    // simple weekend jump, friday afternoon should output monday afternoon
    public function testWeekEndJump()
    {
      $date = new \DateTime('2015-10-17 13:30:00');
      $expected = new \DateTime('2015-10-19 14:30:00');
      $interval = new \DateInterval('PT4H'); // '04:00:00';

      var_dump($date->format('w'));

      //  week calendar and monday / friday exclusions are hardcoded, but could be loaded from database, a config file, an iCal file...
      $am = array('09:00:00', '12:00:00');
      $pm = array('13:30:00', '17:00:00');
      $classicDay = array($am, $pm);

      $timeTable = array(
        array(), // sunday
        $classicDay, // monday
        $classicDay, // thuesday
        $classicDay, // wedesday
        $classicDay, //thurday
        $classicDay, // friday
        array(), // saturday
      );

      $timeExclusions = array(
        array(), // sunday
        $am, // monday
        array(), // thuesday
        array(), // wedesday
        array(), //thurday
        $pm, // friday
        array(), // saturday
      );

      // create a holliday callendar object
      $holidaysCalendar = new HolidaysCalendar('C:/websites/invoicing-app2/data/Holidays.ics');

      $scheduler = new EmailScheduler($timeTable, $timeExclusions, $holidaysCalendar);
      $result = $scheduler->getSendDate($date, $interval);

      $this->assertEquals($result, $expected);
    }

    // full weekend jump, friday morning should output monday afternoon
    public function testFridayAfternoonExeption()
    {
      $date = new \DateTime('2015-10-09 09:00:00');
      $expected = new \DateTime('2015-10-12 13:30:00');
      $interval = new \DateInterval('PT4H'); // '04:00:00';

      //  week calendar and monday / friday exclusions are hardcoded, but could be loaded from database, a config file, an iCal file...
      $am = array('09:00:00', '12:00:00');
      $pm = array('13:30:00', '17:00:00');
      $classicDay = array($am, $pm);

      $timeTable = array(
        array(), // sunday
        $classicDay, // monday
        $classicDay, // thuesday
        $classicDay, // wedesday
        $classicDay, //thurday
        $classicDay, // friday
        array(), // saturday
      );

      $timeExclusions = array(
        array(), // sunday
        $am, // monday
        array(), // thuesday
        array(), // wedesday
        array(), //thurday
        $pm, // friday
        array(), // saturday
      );

      // create a holliday callendar object
      $holidaysCalendar = new HolidaysCalendar('C:/websites/invoicing-app2/data/Holidays.ics');

      $scheduler = new EmailScheduler($timeTable, $timeExclusions, $holidaysCalendar);
      $result = $scheduler->getSendDate($date, $interval);

      $this->assertEquals($result, $expected);
    }

    public function testHolidays()
    {
      $date = new \DateTime('2015-12-26  09:00:00');
      $expected = new \DateTime('2016-01-01 09:00:00');
      $interval = new \DateInterval('PT4H'); // '04:00:00';

      //  week calendar and monday / friday exclusions are hardcoded, but could be loaded from database, a config file, an iCal file...
      $am = array('09:00:00', '12:00:00');
      $pm = array('13:30:00', '17:00:00');
      $classicDay = array($am, $pm);

      $timeTable = array(
        array(), // sunday
        $classicDay, // monday
        $classicDay, // thuesday
        $classicDay, // wedesday
        $classicDay, //thurday
        $classicDay, // friday
        array(), // saturday
      );

      $timeExclusions = array(
        array(), // sunday
        $am, // monday
        array(), // thuesday
        array(), // wedesday
        array(), //thurday
        $pm, // friday
        array(), // saturday
      );

      // create a holliday callendar object
      $holidaysCalendar = new HolidaysCalendar('C:/websites/invoicing-app2/data/Holidays.ics');

      $scheduler = new EmailScheduler($timeTable, $timeExclusions, $holidaysCalendar);
      $result = $scheduler->getSendDate($date, $interval);

      $this->assertEquals($result, $expected);
    }
}
