<?php
namespace InvoicingBundle\Services;

use Sabre\VObject;

class HolidaysCalendar
{
  private $vcalendar;

  public function __construct($filename)
  {
    $this->vcalendar = VObject\Reader::read(
        fopen($filename, 'r')
    );

    //var_dump($vcalendar->getBaseComponents()[0]->DTSTART->getDateTime());
  }

  /**
   * Returns an array of start - end dates for events in the ical
   *
   * @return array
   */
  public function getEventsPeriod()
  {
    $events = $this->vcalendar->getBaseComponents();
    $eventsPeriod = array();

    foreach ($events as $event) {
      $eventsPeriod[] = array($event->DTSTART->getDateTime(), $event->DTEND->getDateTime());
    }

    return $eventsPeriod;
  }
}
