<?php

namespace BusinessBundle\Controller;

use BusinessBundle\Entity\Business;
use BusinessBundle\Entity\OpeningHours;
use BusinessBundle\Entity\Visits;
use BusinessBundle\Form\BusinessType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\IsNull;

class DefaultController extends Controller
{
    /**
     * @Route("/business")
     */
    public function indexAction()
    {
        return $this->render('BusinessBundle:Default:index.html.twig');
    }
    /**
     * @Route("new/business", name="_newbusiness")
     */
    public function addAction(Request $request)
    {
        $business = new Business();
        $form = $this->createForm(BusinessType::class,$business);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->entitymanager();
            $business->upload();
            $owner = $this->getUser();
            $business->setOwner($owner);
            foreach ($form->get('openings')->getData() as $ac) {
                $ac->setBusiness($business);
                $em->persist($ac);
            }
            $business->setAddingDate(new \DateTime('now'));
            $em->persist($business);
            $em->flush();
            $this->addFlash('success','Business added successfully');
            return $this->redirectToRoute("homepage");
        }
        return $this->render('@Business/newbusiness.html.twig',array(
            'form' => $form->createView(),
            'user' => $this->getUser()
        ));
    }

    /**
     * @Route("business/{id}/edit", name="_editbusiness")
     */
    public function editAction(Request $request,$id)
    {
        $em = $this->entitymanager();
        $business = $em->getRepository('BusinessBundle:Business')->find($id);
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
        return $this->render('@Business/editbusiness.html.twig',array(
            'form' => $form->createView(),
            'user' => $this->getUser()
        ));
    }

    /**
     * @Route("business/{id}/delete", name="_deletebusiness")
     */
    public function deletetAction(Request $request,$id)
    {
        $em = $this->entitymanager();
        $business = $em->getRepository('BusinessBundle:Business')->find($id);
    }

    /**
     * @Route("/business/{id}/show", name="_showbusiness" , options={"expose"=true})
     */
    public function showAction($id)
    {
        $em = $this->entitymanager();
        $business = $em->getRepository('BusinessBundle:Business')->find($id);
        $user = $this->getUser();
        $isAdmin = $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN');
        // business introuvable
        if(!$business){
            throw $this->createNotFoundException("Le point d'intérêt que vous chercher n'éxiste pas!");
        }
        if($business->getApproved() == false && $user !== $business->getOwner() && $isAdmin == false){
            throw $this->createNotFoundException("Le point d'intérêt que vous chercher n'éxiste pas!");
        }
        if($business->getApproved() == null && $user !== $business->getOwner() && $isAdmin == false){
            throw $this->createNotFoundException("Le point d'intérêt que vous chercher n'éxiste pas!");
        }
        if($business->getApproved() == null && $user == $business->getOwner()){
            $this->addFlash('info',"Ce point d'intérêt n'est pas encore approuvé par un administrateur!");
            dump($business->getApproved());
        }
        elseif($business->getApproved() == false && $user == $business->getOwner()){
            $this->addFlash('alert',"Ce point d'intérêt n'a pas été approuvé par l'administrateur! Veuillez le contacter pour régler votre situatiion.");
            dump($business->getApproved());
        }
        if (!is_object($user) || !$user instanceof UserInterface) {
            //$user = dump($user);
        }
        dump($business->getApproved());
        $visit = new Visits($business,$user,new \DateTime('now'));
        $em->persist($visit);
        $em->flush();
        return $this->render('@Business/Default/showbusiness.html.twig',array(
            'business' => $business,
        ));
    }
    /**
     * @Route("/businessesapi", name="_businessesapi" , options={"expose"=true})
     */
    public function searchAction(Request $request)
    {
        $category = $request->query->get('category');
        $city = $request->query->get('city');
        $em = $this->entitymanager();
        if(!empty($city) && !empty($category)){
            $results = $em->createQuery("SELECT b,c FROM BusinessBundle:Business b JOIN b.category c WHERE b.city = :city AND b.category = :category")
                ->setParameter('city', $city)
                ->setParameter('category', $category)
                ->getArrayResult();
        }elseif(empty($city) && empty($category)){
            $results = $em->createQuery("SELECT b,c FROM BusinessBundle:Business b JOIN b.category c")->getArrayResult();
        }elseif(empty($city) || empty($category)){
            $results = $em->createQuery("SELECT b,c FROM BusinessBundle:Business b JOIN b.category c WHERE b.city = :city OR b.category = :category")
                ->setParameter('city', $city)
                ->setParameter('city', $city)
                ->setParameter('category', $category)
                ->getArrayResult();
        }

        $response = new JsonResponse($results);
        return $response;
    }

    public function newBusinessesAction(){
        $em = $this->entitymanager();
        $new_bs = $em->getRepository('BusinessBundle:Business')->createQueryBuilder('b')
            ->Select('Count(b)')
            ->Where('b.approved = 0')
            ->getQuery()
            ->getSingleScalarResult();
        return $this->render('BusinessBundle:widgets:newbusinesses.html.twig',['nbr'=> $new_bs]);
    }

    public function getBusinessesAction(){
        $em = $this->entitymanager();
        $businesses = $em->getRepository('BusinessBundle:Business')->findAll();
        return $this->render('BusinessBundle:widgets:businesses.html.twig',['businesses'=> $businesses]);
    }

    public function filtersAction(){
        $em = $this->entitymanager();
        $categories = $em->getRepository('BusinessBundle:Category')->findBy([],['name' => 'ASC']);
        $cities = $em->getRepository('BusinessBundle:City')->findBy([],['name' => 'ASC']);
        return $this->render('BusinessBundle:Default:index.html.twig',[
            'cities' => $cities,
            'categories' => $categories
        ]);
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
