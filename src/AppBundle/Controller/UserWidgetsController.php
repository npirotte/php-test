<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserWidgetsController extends Controller
{
  public function currentUserWidgetAction ()
  {
    $user = $this->getUser();
    return $this->render('AppBundle:Widgets:currentUser.html.twig', array('user' => $user));
  }
}
