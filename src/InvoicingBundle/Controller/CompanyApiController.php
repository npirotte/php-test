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
  public function searchCompnayAction(Request $request)
  {
    $q = $request->get('q');

    $repository = $this->getDoctrine()->getRepository('InvoicingBundle:Company');
    $query = $repository->createQueryBuilder('c')
      ->where('c.email LIKE :q')
      ->setParameter('q', $q.'%')
      ->setMaxResults(10)
      ->getQuery();

    $companies = $query->getResult();

    // build view model for the api result
    $results = array();

    foreach ($companies as $company) {
      $results[] = array('email' => $company->getEmail());
    }

    return new JsonResponse($results);
  }
}
