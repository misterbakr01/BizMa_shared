<?php
namespace AdminBundle\EventListener;

use Avanzu\AdminThemeBundle\Event\ShowUserEvent;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;

class ShowUserListener extends Controller{

    // ...

    public function onShowUser(ShowUserEvent $event) {

        $user = $this->getUser();
        $event->setUser($user);

    }
}