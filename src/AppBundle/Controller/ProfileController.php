<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends Controller
{
    /**
     * @Route("/profile/", name="profilepage")
     */
    public function showprofileAction(Request $request)
    {
        $user = $this->getUser();
        return $this->render('@App/Default/businessPorfile.html.twig', [
           /* 'user' => $user,*/
        ]);
    }
    /**
     * @Route("/prodfgfile/edit/", name="profilepage")
     */
    public function editprofileAction(Request $request)
    {
        $user = $this->getUser();
        return $this->render('@App/Default/editPorfile.html.twig', [
            'user' => $user,
        ]);
    }
}
