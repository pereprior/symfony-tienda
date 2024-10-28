<?php

namespace App\Controller;

use App\Entity\Team;
use App\Service\ProductService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    private array $teams;

    public function __construct(ManagerRegistry $doctrine)
    {
        $repository = $doctrine->getRepository(Team::class);
        $this->teams = $repository->findAll();
    }

    #[Route('/', name: 'index')]
    public function index(ProductService $productsService): Response
    {
        $products = $productsService->getProducts();
        return $this->render('page/index.html.twig', [
            'teams' => $this->teams,
            'products' => $products
        ]);
    }

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('page/about.html.twig', ['teams' => $this->teams]);
    }

    #[Route('/service', name: 'service')]
    public function service(): Response
    {
        return $this->render('page/service.html.twig', []);
    }

    #[Route('/price', name: 'price')]
    public function price(): Response
    {
        return $this->render('page/price.html.twig', []);
    }

    #[Route('/team', name: 'team')]
    public function team(): Response
    {
        return $this->render('page/team.html.twig', []);
    }

    #[Route('/testimonial', name: 'testimonial')]
    public function testimonial(): Response
    {
        return $this->render('page/testimonial.html.twig', []);
    }

    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->render('page/contact.html.twig', []);
    }

}
