<?php

namespace HomeBundle\Controller;

use HomeBundle\Entity\Post;
use HomeBundle\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="_Home")
     */
    public function indexAction(Request $request)
    {
        /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
        $session = $request->getSession();
        $authErrorKey = Security::AUTHENTICATION_ERROR;
        $lastUsernameKey = Security::LAST_USERNAME;

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);
        if ($this->has('security.csrf.token_manager')) {
            $csrfToken = $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue();
        } else {
            // BC for SF < 2.4
            $csrfToken = $this->has('form.csrf_provider')
                ? $this->get('form.csrf_provider')->generateCsrfToken('authenticate')
                : null;
        }
        $em = $this->entitymanager();
        $posts = $em->getRepository('HomeBundle:Post')->findBy(array(), array('creationDate' => 'desc'));
        $businesses = $em->getRepository('BusinessBundle:Business')->findAll();
        return $this->render('@Business/Default/businesses.html.twig',array(
            'posts' => $posts,
            'businesses' => $businesses,
            'last_username' => $lastUsername,
            'error' => $error,
            'csrf_token' => $csrfToken,
        ));
    }
    /**
     * @Route("/post/{id}", name="_showpost" , options={"expose"=true})
     */
    public function showAction($id)
    {
        $em = $this->entitymanager();
        $post = $em->getRepository('HomeBundle:Post')->find($id);
        return $this->render('HomeBundle:Default:showpost.html.twig',array(
            'post' => $post,
        ));
    }
    /**
     * @Route("/search", name="_searchpost")
     */
    public function searchAction(Request $request)
    {
        $keyword = $request->query->get('term');
        $keyword = '%'.$keyword.'%';
        $em = $this->entitymanager();
        $results = $em->getRepository("HomeBundle:Post")->createQueryBuilder('s')
            ->where('s.title LIKE :title')
            ->orWhere('s.content LIKE :content')
            ->setParameter('title', $keyword)
            ->setParameter('content', $keyword)
            ->getQuery()
            ->getArrayResult();
        $response = new JsonResponse($results);
        return $response;
    }
    /**
     * @Route("/post/edit/{id}", name="_editpost")
     */
    public function editAction(Request $request,$id)
    {
        $em = $this->entitymanager();
        $post = $em->getRepository('HomeBundle:Post')->find($id);
        $form = $this->createForm(PostType::class,$post);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->entitymanager();
            $post->upload();
            $post->setLastEdit();
            $em->flush();
            $this->addFlash('success','Post edited successfully');
            return $this->redirectToRoute("_showpost",['id' => $post->getId()]);
        }
        return $this->render('HomeBundle:Default:editpost.html.twig',array(
            'form' => $form->createView(),
        ));
    }
    /**
     * @Route("admin/post/new" , name="_newpost")
     */
    public function addAction(Request $request)
    {
        $post = new Post("","",new \DateTime('now'));
        $form = $this->createForm(PostType::class,$post);
        $form->handleRequest($request);
        $message = "";
        if($form->isSubmitted() && $form->isValid()){
            $em = $this->entitymanager();
            $post->upload();
            $user = $this->getUser();
            $post->setAuthor($user);
            $post->setCreationDate(new \DateTime('now'));
            $em->persist($post);
            $em->flush();
            $this->addFlash('success','Post added successfully');
            $message = "Form submitted successfully";
            return $this->redirectToRoute("_showpost",['id' => $post->getId()]);
        }
        return $this->render('HomeBundle:Default:newposts.html.twig',array(
            'form' => $form->createView(),
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
