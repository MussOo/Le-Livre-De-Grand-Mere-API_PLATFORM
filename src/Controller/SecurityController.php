<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/api/login", name="api_login", methods={"POST"})
     */
    public function login()
    {
        throw new \LogicException('This method should not be reached. Symfony intercepts this route.');
    }

    /**
     * @Route("/api/me", name="api_me", methods={"GET"})
     */
    public function me(){
        return $this->json($this->getUser());
    }
}
