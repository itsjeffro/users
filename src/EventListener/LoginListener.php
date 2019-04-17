<?php

namespace App\EventListener;

use App\Entity\Employee;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class LoginListener
{
    /**
     * @var RouterInterface
     */
    public $router;

    /**
     * @var EntityManagerInterface
     */
    public $entityManager;

    /**
     * @var TokenStorageInterface
     */
    public $tokenStorage;

    /**
     * @var EventDispatcherInterface
     */
    public $dispatcher;

    /**
     * LoginListener constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param RouterInterface $router
     * @param EntityManagerInterface $entityManager
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        RouterInterface $router,
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $dispatcher
    ) {
        $this->router = $router;
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Set the last login for the user after a successful login, then
     * redirect the user to the correct route based on their role.
     *
     * @param InteractiveLoginEvent $event
     * @throws \Exception
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        $user->setLastLogin(new \DateTime);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->dispatcher->addListener(KernelEvents::RESPONSE, [$this, 'onKernelResponse']);
    }

    /**
     * Handle redirect for user.
     *
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $url = $this->router->generate('contractor');

        if (in_array(Employee::ROLE_EMPLOYEE, $user->getRoles())) {
            $url = $this->router->generate('employee');
        }

        $response = new RedirectResponse($url);

        return $event->setResponse($response);
    }
}
