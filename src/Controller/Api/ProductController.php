<?php

namespace App\Controller\Api;

use App\Entity\Product;
use OpenApi\Attributes as OA;
use App\Repository\ProductRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/product')]
#[OA\Tag(name: 'Product')]

class ProductController extends AbstractController
{


    /**
     * Afficher la liste des produits
     * @param ProductRepository $productRepository
     * @param SerializerInterface $serializer
     * 
     * @return JsonResponse
     */
    #[Route(name: 'api_product_get_collection', methods: "GET")]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: Product::class, groups: ['groups' => 'get_products'])
    )]
    public function getCollection(ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($productRepository->findAll(), "json", ['groups' => 'get_products']),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }


    /**
     * Afficher les details d'un produit
     * @param Product $product
     * @param SerializerInterface $serializer
     * 
     * @return JsonResponse
     */
    #[Route("/{id}", name: 'api_product_get_item', methods: "GET")]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: Product::class, groups: ['groups' => 'get_products', 'get_product'])
    )]
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
