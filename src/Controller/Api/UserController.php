<?php

namespace App\Controller\Api;

use App\Entity\User;
use OpenApi\Attributes as OA;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api/user')]
#[OA\Tag(name: 'User')]
class UserController extends AbstractController
{


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
    public function getCollection(UserRepository $user, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($user->findBy(["customer" => $this->getUser()]), "json", ['groups' => 'get_users']),
            JsonResponse::HTTP_OK,
            [],
            true
        );
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
            return new JsonResponse("Your rights are insufficient to access this resource", 403);
        }
        return new JsonResponse(
            $serializer->serialize($user, "json", ['groups' => ['get_users', 'get_user']]),
            JsonResponse::HTTP_OK,
            [],
            true
        );
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

        $user = $serializer->deserialize($request->getContent(), User::class, "json", ['groups' => 'post_user']);
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new JsonResponse($errorsString);
        }

        $user->setCustomer($this->getUser());
        $entityManager->persist($user);
        $entityManager->flush();
        return new JsonResponse(
            $serializer->serialize($user, "json", ['groups' => 'get_user']),
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
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT,);
    }
}
