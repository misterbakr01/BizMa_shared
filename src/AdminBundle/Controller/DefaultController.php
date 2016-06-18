<?php

namespace AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/admin/" , name="administration")
     */
    public function indexAction()
    {
        $user = $this->getUser();
        return $this->render('AdminBundle:Default:index.html.twig',
            ['user' => $user]
        );
    }

    public function unapprovedBusinessesAction(){
        $em = $this->entitymanager();
        $businesses = $em->getRepository('BusinessBundle:Business')->findBy(['approved' => false]);
        return $this->render('BusinessBundle:widgets:approve.html.twig',['businesses' => $businesses]);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/admin/approuve" , name="approuve")
     */
    public function unapproveBusinessAction(Request $request){
        $em = $this->entitymanager();
        $businessId = $request->request->get('business');
        $approve = $request->request->get('approve');
        $business = $em->getRepository('BusinessBundle:Business')->find($businessId);
        if($approve == "false"){
            $em = $this->entitymanager();
            $raison = $request->request->get('raison');

            $business->setComment($raison);
            $business->setDateofapproval(new \DateTime('now'));
            $business->setResponsable($this->getUser());

            $em->persist($business);
            $em->flush();

            $business->setApproved(0);
            $response = ['Status' => "unapproved",
                         'approve' => $approve,
                         'reponse' => true];
        }
        if($approve == "true"){
            $business->setApproved(1);
            $response = ['Status' => "approved",
                         'approve' => $approve];
        }
        $em->persist($business);
        $em->flush();
        return new JsonResponse($response);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/admin/users" , name="userslist")
     */
    public function usersAdministrationAction(Request $request){
        $em = $this->entitymanager();
        $users = $em->getRepository('AppBundle:User')->findAll();
        return $this->render('AdminBundle:Default:userslist.html.twig',['users'=> $users]);
    }

    /**
     * @Route("/admin/businesses/control" , name="controlBusinesses")
     */
    public function businessControlAction(){
        return $this->render('BusinessBundle:Default:Control.html.twig');
    }

    public function getUsersAction(){
        $em = $this->entitymanager();
        $users = $em->getRepository('AppBundle:User')->createQueryBuilder('b')
            ->Select('Count(b)')
            ->getQuery()
            ->getSingleScalarResult();
        return $this->render('BusinessBundle:widgets:users.html.twig',['users'=> $users]);
    }

    private function entitymanager()
    {
        $em = $this->getDoctrine()->getManager();
        return $em;
    }


}
