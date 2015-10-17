<?php

namespace InvoicingBundle\Tests\Services;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use InvoicingBundle\Services\EmailScheduler;

class EmailSchedulerTest extends \PHPUnit_Framework_TestCase
{
    // simple test case : morning should output afternoon
    public function testIndex()
    {
        $date = new \DateTime('2015-10-05 9:00:00');
        $expected = new \DateTime('2015-10-05 14:30:00');
        $interval = new \DateInterval('PT4H'); // '04:00:00';

        $scheduler = new EmailScheduler();
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

        $scheduler = new EmailScheduler();
        $result = $scheduler->getSendDate($date, $interval);

        $this->assertEquals($result, $expected);
    }

    // simple weekend jump, friday afternoon should output monday afternoon
    public function testWeekEndJump()
    {
      $date = new \DateTime('2015-10-09 14:30:00');
      $expected = new \DateTime('2015-10-12 13:30:00');
      $interval = new \DateInterval('PT4H'); // '04:00:00';

      $scheduler = new EmailScheduler();
      $result = $scheduler->getSendDate($date, $interval);

      $this->assertEquals($result, $expected);
    }

    // full weekend jump, friday morning should output monday afternoon
    public function testFridayAfternoonExeption()
    {
      $date = new \DateTime('2015-10-09 09:00:00');
      $expected = new \DateTime('2015-10-12 13:30:00');
      $interval = new \DateInterval('PT4H'); // '04:00:00';

      $scheduler = new EmailScheduler();
      $result = $scheduler->getSendDate($date, $interval);

      $this->assertEquals($result, $expected);
    }

    public function testHolidays()
    {
      $date = new \DateTime('2015-10-18  09:00:00');
      $expected = new \DateTime('2015-11-03 09:00:00');
      $interval = new \DateInterval('PT4H'); // '04:00:00';

      $scheduler = new EmailScheduler();
      $result = $scheduler->getSendDate($date, $interval);

      $this->assertEquals($result, $expected);
    }
}
