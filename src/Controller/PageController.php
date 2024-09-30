<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Entity\Category;
use Doctrine\ORM\EntityManager;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PageController extends AbstractController
{
    #[Route('/', name: 'app_page')]
    public function index(): Response
    {
        return $this->render('page/index.html.twig');
    }

    #[Route('/p/{slug}', name: 'app_post_show')]
    public function show(PostRepository $pr, string $slug): Response
    {
        return $this->render('page/show.html.twig', [
            'post' => $pr->findOneBy(['slug' => $slug]),
        ]);
    }

    #[Route('/new/', name: 'app_post_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        // Objets
        $post = new Post();
        $category = new Category();
        
        
        
        // Form
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        
        // Traitement
        if($form->isSubmitted() && $form->isValid())
        {
            // Assignation de l'auteur, de la catégorie
            $post
                ->setAuthor($this->getUser())
                ->setCategory($category)
            ;
            $em->persist($category);
            $em->persist($post);
            $em->flush();
        }

        return $this->render('page/new.html.twig', [
            'postForm' => $form,
        ]);
    }
}
