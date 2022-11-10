<?php

namespace App\Controller\Api;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/product')]
class ProductController extends AbstractController
{
    #[Route(name: 'api_product_get_collection')]
    public function getCollection(ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($productRepository->findAll(), "json", ['groups' => 'get_products']),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    #[Route("/{id}", name: 'api_product_get_item')]
    public function getItem(Product $product, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($product, "json", ['groups' => ['get_products', 'get_product']]),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }
}
