<?php

namespace App\Controller\Api;

use App\Entity\Product;
use OpenApi\Attributes as OA;
use App\Repository\ProductRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;


#[Route('/api/products')]
#[OA\Tag(name: 'Product')]

class ProductController extends AbstractController
{
    public function __construct(private CacheInterface $cache)
    {
    }

    #[Route(name: 'api_product_get_collection', methods: "GET")]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: Product::class, groups: ['groups' => 'get_products'])
    )]
    #[OA\Parameter(
        name: 'page',
        in: 'query',
        description: 'The field used to choose a page',
        schema: new OA\Schema(type: 'string')
    )]
    #[OA\Parameter(
        name: 'limit',
        in: 'query',
        description: 'The field used to choose how many product you want by page',
        schema: new OA\Schema(type: 'string')
    )]
    /**
     * @param ProductRepository $productRepository
     * @param SerializerInterface $serializer
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function getCollection(ProductRepository $productRepository, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 5);

        $jsonProducts = $this->cache->get("get-products-$page-$limit", function (ItemInterface $item) use ($productRepository, $serializer, $limit, $page) {
            $item->expiresAfter(3600);
            $context = SerializationContext::create()->setGroups(['get_products']);
            return $serializer->serialize($productRepository->findAllWithPagination($page, $limit), "json", $context);
        });
        return new JsonResponse($jsonProducts, JsonResponse::HTTP_OK, [], true);
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
        $jsonProduct = $this->cache->get("get-product-" . $product->getId(), function (ItemInterface $item) use ($product, $serializer) {
            $item->expiresAfter(3600);
            $context = SerializationContext::create()->setGroups(['get_products', 'get_product']);
            return $serializer->serialize($product, "json", $context);
        });
        return new JsonResponse($jsonProduct, JsonResponse::HTTP_OK, [], true);
    }
}
