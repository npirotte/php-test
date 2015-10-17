<?php

namespace InvoicingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api/company")
 */
class CompanyApiController extends Controller
{
  /**
   * @Route("/search", name="searchCompany")
   */
  public function createInvoiceAction(Request $request)
  {
    $q = $request->get('q');

    $em = $this->getDoctrine()->getManager();
    /* $query = $em->createQuery(
      'SELECT  c
      FROM InvoicingBundle:Company c
      WHERE c.email LIKE :q'
    )->setParameter('q', $q); */
    $repository = $this->getDoctrine()->getRepository('InvoicingBundle:Company');
    $query = $repository->createQueryBuilder('c')
      ->where('c.email LIKE :q')
      ->setParameter('q', $q.'%')
      ->getQuery();

    $companies = $query->getResult();

    $results = array();

    foreach ($companies as $company) {
      $results[] = array('email' => $company->getEmail());
    }

    return new JsonResponse($results);
  }
}
