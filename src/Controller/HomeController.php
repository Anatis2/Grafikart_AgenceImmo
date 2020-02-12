<?php

namespace App\Controller;

use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PropertyRepository $repo)
    {
		$properties = $repo->findLatest();
        return $this->render('pages/home.html.twig', [
        	'properties' => $properties
		]);
    }
}
