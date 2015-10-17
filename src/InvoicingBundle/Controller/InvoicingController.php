<?php

namespace InvoicingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

use InvoicingBundle\Entity\Invoice;
use InvoicingBundle\Form\Type\InvoiceType;

/**
 * @Route("/invoice")
 */
class InvoicingController extends Controller
{
  /**
   * @Route("/new", name="createInvoice")
   */
  public function createInvoiceAction(Request $request)
  {
    // set up an invoice instance
    $invoice = new Invoice();

    // form creation
    $form = $this->createForm(new InvoiceType(), $invoice, array('attr' => array('autocomplete' => 'off' )));
    $form->handleRequest($request);

    if ($form->isValid())
    {
        $doctrine = $this->getDoctrine();

        // check if companies exits
        $existingCompanies = $doctrine
          ->getRepository('InvoicingBundle:Company')
          ->findByEmail(array(
            $invoice->getSeller()->getEmail(),
            $invoice->getDebtor()->getEmail()
          ));

        // fill form data with finded companies
        foreach ($existingCompanies as $company) {
          if ($invoice->getSeller()->getEmail() === $company->getEmail())
          {
            $invoice->setSeller($company);
          }
          elseif ($invoice->getDebtor()->getEmail() === $company->getEmail())
          {
            $invoice->getDebtor($company);
          }
        }

        // persist

        $em = $doctrine->getManager();
        $em->persist($invoice);
        $em->flush();

        // set flash message
        $this->get('session')->getFlashBag()->add('notice', "You succefully introduce an invoice ! An confirmation email will be send after an administrator approved the invoice.");

        // handle different redirect when user is an admin
        $user = $this->getUser();
        if ($user)
        {
          if ($user->hasRole('ROLE_SUPER_ADMIN'))
          {
            return $this->redirectToRoute('invoiceList');
          }
        }

        return $this->redirectToRoute('homepage');
    }

    // render
    return $this->render(
      'InvoicingBundle:Invoicing:form.html.twig',
      array(
        'form' => $form->createView()
      )
    );
  }
}
