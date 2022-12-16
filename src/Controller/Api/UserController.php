<?php

namespace App\Controller\Api;

use App\Entity\User;
use OpenApi\Attributes as OA;
use App\Repository\UserRepository;
use JMS\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use Nelmio\ApiDocBundle\Annotation\Model;
use JMS\Serializer\DeserializationContext;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/users')]
#[OA\Tag(name: 'User')]
class UserController extends AbstractController
{
    public function __construct(private TagAwareCacheInterface $cache)
    {
    }

    /**
     * Affiche la liste de vos clients
     * @param UserRepository $user
     * @param SerializerInterface $serializer
     * 
     * @return JsonResponse
     */
    #[Route(name: 'api_user_get_collection', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: User::class, groups: ['groups' => 'get_users'])
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
        description: 'The field used to choose how many user you want by page',
        schema: new OA\Schema(type: 'string')
    )]
    public function getCollection(UserRepository $user, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 5);

        $jsonUsers = $this->cache->get("get-users-$page-$limit", function (ItemInterface $item) use ($user, $serializer, $page, $limit) {
            $item->expiresAfter(3600);
            $item->tag("usersCache");
            $context = SerializationContext::create()->setGroups(['get_users']);
            return $serializer->serialize($user->findAllWithPagination($page, $limit, $this->getUser()), "json", $context);
        });
        return new JsonResponse($jsonUsers, JsonResponse::HTTP_OK, [], true);
    }


    /**
     * Affiche les details d'un de vos clients
     * @param User $user
     * @param SerializerInterface $serializer
     * 
     * @return JsonResponse
     */
    #[Route("/{id}", name: 'api_user_get_item', methods: ['GET'])]
    #[OA\Response(
        response: 200,
        description: 'Successful response',
        content: new Model(type: User::class, groups: ['groups' => 'get_users', 'get_user'])
    )]
    public function getItem(User $user, SerializerInterface $serializer): JsonResponse
    {
        if ($user->getCustomer() !== $this->getUser()) {
            return new JsonResponse("Error: Your rights are insufficient to access this resource", 403);
        }

        $jsonUser = $this->cache->get("get-user" . $user->getId(), function (ItemInterface $item) use ($user, $serializer) {
            $item->expiresAfter(3600);
            $item->tag("userCache");
            $context = SerializationContext::create()->setGroups(['get_users', 'get_user']);
            return $serializer->serialize($user, "json", $context);
        });
        return new JsonResponse($jsonUser, JsonResponse::HTTP_OK, [], true);
    }


    /**
     * Ajouter un nouveau client
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @param UrlGeneratorInterface $urlGenerator
     * 
     * @return JsonResponse
     */
    #[Route(name: 'api_user_post_item', methods: ['POST'])]
    #[OA\Post(requestBody: new OA\RequestBody(content: new OA\JsonContent(ref: new Model(type: User::class, groups: ['groups' => 'post_user']))))]
    #[OA\Response(
        response: 201,
        description: 'Successful response',
        content: new Model(type: User::class, groups: ['post_user'])
    )]
    public function postItem(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, UrlGeneratorInterface $urlGenerator, ValidatorInterface $validator): JsonResponse
    {

        $context = DeserializationContext::create()->setGroups(['post_user']);
        $user = $serializer->deserialize($request->getContent(), User::class, "json", $context);
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse($errorsString);
        }

        $user->setCustomer($this->getUser());
        $entityManager->persist($user);
        $entityManager->flush();
        $this->cache->invalidateTags(["usersCache"]);
        $context = SerializationContext::create()->setGroups(['post_user']);
        return new JsonResponse(
            $serializer->serialize($user, "json", $context),
            JsonResponse::HTTP_CREATED,
            ["Location" => $urlGenerator->generate("api_user_post_item", ["id" => $user->getId()])],
            true
        );
    }


    /**
     * Supprimer un de vos clients
     * @param EntityManagerInterface $entityManager
     * @param User $user
     * 
     * @return JsonResponse
     */
    #[Route("/{id}", name: 'api_user_delete_item', methods: ["DELETE"])]
    #[OA\Response(
        response: 204,
        description: 'Successful response',
    )]
    public function deleteItem(EntityManagerInterface $entityManager, User $user): JsonResponse
    {
        if ($user->getCustomer() !== $this->getUser()) {
            return new JsonResponse("Your rights are insufficient to delete this resource", 403);
        }
        $entityManager->remove($user);
        $entityManager->flush();
        $this->cache->invalidateTags(["usersCache", "userCache"]);
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT,);
    }
}
