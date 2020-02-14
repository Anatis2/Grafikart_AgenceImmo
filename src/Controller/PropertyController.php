<?php

namespace App\Controller;

use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Repository\PropertyRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    /**
     * @Route("/biens", name="property.index")
     */
    public function index(PropertyRepository $repo, PaginatorInterface $paginator, Request $request)
    {
    	$search = new PropertySearch();

    	$form = $this->createForm(PropertySearchType::class, $search);
    	$form->handleRequest($request);

		$properties = $paginator->paginate(
			$repo->findAllVisibleQuery($search),
			$request->query->getInt('page', 1),
			12
		);

        return $this->render('property/index.html.twig', [
        	'current_menu' => 'properties',
			'properties' => $properties,
			'form' => $form->createView()
		]);
    }

	/**
	 * @Route("/biens/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
	 * @return Response
	 */
    public function show(Property $property, string $slug): Response
	{
		if($property->getSlug() !== $slug) {
			return $this->redirectToRoute('property.show', [
				'id' => $property->getId(),
				'slug' => $property->getSlug()
			], 301);
		}

		return $this->render('property/show.html.twig', [
			'property' => $property,
			'current_menu' => 'properties'
		]);
	}
}
