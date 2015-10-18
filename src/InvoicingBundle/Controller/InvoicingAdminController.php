<?php

namespace InvoicingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use InvoicingBundle\Services\EmailScheduler;
use InvoicingBundle\Services\HolidaysCalendar;

/**
 * @Route("/admin/invoice")
 */
class InvoicingAdminController extends Controller
{
  /**
   * @Route("/", name="invoiceList")
   */
  public function invoiceListAction()
  {
    // get all invoices
    $invoices = $this->getDoctrine()
      ->getRepository('InvoicingBundle:Invoice')
      ->findAll();

    // render
    return $this->render(
      'InvoicingBundle:Invoicing:list.html.twig',
      array (
        'invoices' => $invoices
      )
    );
  }

  /**
   * @Route("/{id}", name="invoiceDetails")
   */
  public function invoiceDetailsAction($id)
  {
    // get invoice
    $invoice = $this->getDoctrine()
      ->getRepository('InvoicingBundle:Invoice')
      ->find($id);

    // handle not found
    if (!$invoice)
    {
      throw $this->createNotFoundException('No invoice found for id '.$id);
    }

    // render
    return $this->render(
      'InvoicingBundle:Invoicing:details.html.twig',
      array('invoice' => $invoice)
    );
  }

  /**
   * @Route("/{id}/approve", name="approveInvoice")
   */
   public function approveAction($id)
   {
     $invoice = $this->getDoctrine()
       ->getRepository('InvoicingBundle:Invoice')
       ->find($id);

     // handle not found
     if (!$invoice)
     {
       throw $this->createNotFoundException('No invoice found for id '.$id);
     }

     // get user data
     $user = $this->getUser();

     // get approving date
     $date = new \DateTime();

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

     // create a holliday callendar object, file path could be loaded dynamicly from database.
     $holidaysCalendar = new HolidaysCalendar('../data/Holidays.ics');

     // get email sending DateTime
     $interval = new \DateInterval('PT4H'); // '04:00:00';
     $scheduler = new EmailScheduler($timeTable, $timeExclusions, $holidaysCalendar);
     $sendEmailOn = $scheduler->getSendDate(clone $date, $interval);

     // update
     $invoice->setApprovedBy($user);
     $invoice->setApprovedOn($date);
     $invoice->setSendEmailOn($sendEmailOn);

     // persist
     $em = $this->getDoctrine()->getManager();
     $em->persist($invoice);
     $em->flush();

     // feedback message
     $this->get('session')->getFlashBag()->add('notice', 'Invoice approved ! An email will be sent to '.$invoice->getDebtor()->getEmail().' on '.$sendEmailOn->format('m/d/Y H:i').'.' );

     return $this->redirectToRoute('invoiceDetails', array('id' => $id));
   }
}
