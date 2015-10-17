<?php

namespace InvoicingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sabre\VObject;

use InvoicingBundle\Entity\Invoice;
use InvoicingBundle\Form\Type\InvoiceType;

use InvoicingBundle\Services\EmailScheduler;

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
  public function invoiceDetailsActi0n($id)
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
     $date = new \DateTime();

     // get email sending DateTime
     $interval = new \DateInterval('PT4H'); // '04:00:00';
     $scheduler = new EmailScheduler();
     $sendEmailOn = $scheduler->getSendDate(clone $date, $interval);

     // update
     $invoice->setApprovedBy($user);
     $invoice->setApprovedOn($date);
     $invoice->setSendEmailOn($sendEmailOn);

     $em = $this->getDoctrine()->getManager();
     $em->persist($invoice);
     $em->flush();

     $this->get('session')->getFlashBag()->add('notice', 'Invoice approved ! An email will be send to '.$invoice->getDebtor()->getEmail().' on '.$sendEmailOn->format('m/d/Y H:i').'.' );

     return $this->redirectToRoute('invoiceDetails', array('id' => $id));
   }
}
