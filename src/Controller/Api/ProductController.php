<?php

namespace App\Controller\Api;

use App\Repository\ProductRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_api_product')]
    public function index(ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {

        $productRepository->findAll();
        return new JsonResponse($serializer->serialize($productRepository->find(1), 'json'));
    }
}
