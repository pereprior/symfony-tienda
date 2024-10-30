<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;use App\Service\CartService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path:'/cart')]
class CartController extends AbstractController
{
    private ProductRepository $repository;
    private CartService $cart;

    public function __construct(ManagerRegistry $doctrine, CartService $cart)
    {
        $this->repository = $doctrine->getRepository(Product::class);
        $this->cart = $cart;
    }

    #[Route('/', name: 'app_cart')]
    public function index(): Response
    {
        $products = $this->repository->getFromCart($this->cart);
        $items = [];
        $totalCart = 0;

        foreach($products as $product){
            $item = [
                "id"=> $product->getId(),
                "name" => $product->getName(),
                "price" => $product->getPrice(),
                "photo" => $product->getPhoto(),
                "quantity" => $this->cart->getCart()[$product->getId()]
            ];
            $totalCart += $item["quantity"] * $item["price"];
            $items[] = $item;
        }

        return $this->render('cart/index.html.twig', ['items' => $items, 'totalCart' => $totalCart]);
    }

    #[Route('/add/{id}', name: 'cart_add', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function add(int $id): Response
    {
        $product = $this->repository->find($id);
        if (!$product)
            return new JsonResponse("[]", Response::HTTP_NOT_FOUND);

        $this->cart->add($id);

        $data = [
            "id" => $product->getId(),
            "name" => $product->getName(),
            "price" => $product->getPrice(),
            "photo" => $product->getPhoto(),
            "quantity" => $this->cart->getCart()[$product->getId()]
        ];
        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/update/{id}/{quantity}', name: 'cart_update', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function update(int $id, int $quantity = 1): Response
    {
        $product = $this->repository->find($id);
        if (!$product)
            return new JsonResponse("[]", Response::HTTP_NOT_FOUND);

        $this->cart->update($id, $quantity);

        $data = [
            "id" => $product->getId(),
            "name" => $product->getName(),
            "price" => $product->getPrice(),
            "photo" => $product->getPhoto(),
            "quantity" => $this->cart->getCart()[$product->getId()]
        ];
        return new JsonResponse($data, Response::HTTP_OK);
    }

    #[Route('/remove/{id}', name: 'cart_remove', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function remove($id): Response
    {
        $product = $this->repository->find($id);
        if (!$product)
            return new JsonResponse("[]", Response::HTTP_NOT_FOUND);

        $this->cart->remove($id);

        return new Response("El producto ha sido eliminado del carrito", Response::HTTP_OK);
    }

}