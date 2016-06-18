<?php

namespace BusinessBundle\Controller;

use BusinessBundle\Entity\Business;
use BusinessBundle\Entity\OpeningHours;
use BusinessBundle\Form\BusinessType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends Controller
{
    /**
     * @Route("dashboard/", name="_dashboard")
     */

    public function showAction()
    {
        $em = $this->entitymanager();
        $user = $this->getUser();
        $query = $em->createQuery(
            'SELECT COUNT(v) as Visits,b.name
    FROM BusinessBundle:Visits v,BusinessBundle:Business b
    WHERE b.owner = :owner AND v.business = b.id
    GROUP BY v.business'
        )->setParameter('owner', $user);
        $businesses = $query->getResult();
        $user = $this->getUser();
        return $this->render('@App/Default/dashboard.html.twig', [
            'user' => $user,
        ]);
    }

    public function userBusinessesAction()
    {
        $businesses = $this->entitymanager()->getRepository('BusinessBundle:Business')->findBy(['owner' => $this->getUser()]);
        return $this->render('@Business/widgets/userBusinesses.html.twig',['businesses' => $businesses]);
    }

    /**
     * @Route("/chart")
     */
    public function businessesChartAction()
    {
        $em = $this->entitymanager();
        $user = $this->getUser();
        $query = $em->createQuery(
            'SELECT COUNT(v) as Visits,b.name
    FROM BusinessBundle:Visits v,BusinessBundle:Business b
    WHERE b.owner = :owner AND v.business = b.id
    GROUP BY v.business'
        )->setParameter('owner', $user);
        $businesses = $query->getResult();
        return $this->render('@Business/widgets/businessesChart.html.twig',['businesses' => $businesses]);
    }

    public function totalBusinessesAction()
    {
        $em = $this->entitymanager();
        $user = $this->getUser();
        $query = $em->createQuery(
            'SELECT COUNT(v) as Visits
    FROM BusinessBundle:Visits v,BusinessBundle:Business b
    WHERE b.owner = :owner AND v.business = b.id'
        )->setParameter('owner', $user);
        $businesses = $query->getResult();
        return $this->render('@Business/widgets/totalbusinesses.html.twig',['nbr' => $businesses]);
    }

    public function approvedBusinessesAction(){
        $em = $this->entitymanager();
        $user = $this->getUser();
        $query = $em->createQuery(
            'SELECT COUNT(b) as businesses
    FROM BusinessBundle:Business b
    WHERE b.owner = :owner AND b.approved = 1'
        )->setParameter('owner', $user);
        $businesses = $query->getResult();
        return $this->render('@Business/widgets/approvedBusinesses.html.twig',['nbr' => $businesses]);
    }

    public function businessesStatusAction(){
        $em = $this->entitymanager();
        $user = $this->getUser();
        $query = $em->createQuery(
            'SELECT b
            FROM BusinessBundle:Business b
            WHERE b.owner = :owner'
        )->setParameter('owner', $user);
        $businesses = $query->getResult();
        return $this->render('@Business/widgets/businessesStatus.html.twig',['businesses' => $businesses]);
    }

    /**
     * @Route("dashboard/business/{id}/edit", name="_editmybusiness")
     */
    public function editAction(Request $request,$id)
    {
        $em = $this->entitymanager();
        $business = $em->getRepository('BusinessBundle:Business')->find($id);
        if($business->getOwner() != $this->getUser()){
            throw $this->createNotFoundException("Vous ne pouvez pas modifier un point d'intérêt qui ne vous appartient pas!");
        }
        $originalOpenings = new ArrayCollection();

        // Create an ArrayCollection of the current Tag objects in the database
        foreach ($business->getOpenings() as $opening) {
            $originalOpenings->add($opening);
        }

        $form = $this->createForm(BusinessType::class,$business);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->entitymanager();
            foreach ($form->get('openings')->getData() as $ac) {
                $ac->setBusiness($business);
                $em->persist($ac);
            }
            foreach ($originalOpenings as $opening) {
                if (false === $business->getOpenings()->contains($opening)) {
                    // remove the Task from the Tag
                    //$opening->getBusiness()->removeElement($business);

                    // if it was a many-to-one relationship, remove the relationship like this
                    // $tag->setTask(null);

                    $em->persist($opening);

                    // if you wanted to delete the Tag entirely, you can also do that
                    $em->remove($opening);
                }
            }
            $business->upload();
            $business->setLastEdit(new \DateTime('now'));
            $em->persist($business);
            $em->flush();
            $this->addFlash('success','Business Editted successfully');
            return $this->redirectToRoute("_showbusiness",['id'=>$id]);
        }
        return $this->render('@Business/editmybusiness.html.twig',array(
            'form' => $form->createView(),
            'user' => $this->getUser()
        ));
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    public function entitymanager()
    {
        $em = $this->getDoctrine()->getManager();
        return $em;
    }
}
