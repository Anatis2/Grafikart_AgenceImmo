<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPropertyController extends AbstractController
{
    /**
     * @Route("/admin", name="admin.property.index")
     */
    public function index(PropertyRepository $repo)
    {
    	$properties = $repo->findAll();

        return $this->render('admin_property/index.html.twig', [
            'properties' => $properties
        ]);
    }

	/**
	 * @Route("/admin/property/create", name="admin.property.new")
	 */
    public function new(Request $request, EntityManagerInterface $manager)
	{
		$property = new Property();

		$form = $this->createForm(PropertyType::class, $property);

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()) {
			$manager->persist($property);
			$manager->flush();
			$this->addFlash('success', 'Le bien a été créé avec succès !');
			return $this->redirectToRoute('admin.property.index');
		}

		return $this->render('admin_property/new.html.twig', [
			'properties' => $property,
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/admin/property/{id}", name="admin.property.edit", methods="GET|POST")
	 */
    public function edit(Property $property, Request $request, EntityManagerInterface $manager)
	{
		$form = $this->createForm(PropertyType::class, $property);

		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid()) {
			$manager->flush();
			$this->addFlash('success', 'Le bien a été modifié avec succès !');
			return $this->redirectToRoute('admin.property.index');
		}

		return $this->render('admin_property/edit.html.twig', [
			'properties' => $property,
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/admin/property/{id}", name="admin.property.delete", methods="DELETE")
	 */
	public function delete(Property $property, EntityManagerInterface $manager, Request $request)
	{
		if($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token'))) {
			$manager->remove($property);
			$manager->flush();
			$this->addFlash('success', 'Le bien a été supprimé avec succès !');
		}

		return $this->redirectToRoute('admin.property.index');
	}
}


