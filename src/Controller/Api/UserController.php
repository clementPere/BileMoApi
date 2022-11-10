<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/api/user')]
class UserController extends AbstractController
{


    #[Route(name: 'api_user_get_collection', methods: ['GET'])]
    public function getCollection(UserRepository $user, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse(
            $serializer->serialize($user->findBy(["customer" => $this->getUser()]), "json", ['groups' => 'get_users']),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    #[Route("/{id}", name: 'api_user_get_item', methods: ['GET'])]
    public function getItem(User $user, SerializerInterface $serializer): JsonResponse
    {
        if ($user->getCustomer() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }
        return new JsonResponse(
            $serializer->serialize($user, "json", ['groups' => ['get_users', 'get_user']]),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    #[Route(name: 'api_user_post_item', methods: ['POST'])]
    public function postItem(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, UrlGeneratorInterface $urlGenerator): JsonResponse
    {
        $user = $serializer->deserialize($request->getContent(), User::class, "json", ['groups' => 'post_user']);
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

    #[Route("/{id}", name: 'api_user_delete_item', methods: ["DELETE"])]
    public function deleteItem(EntityManagerInterface $entityManager, User $user): JsonResponse
    {
        if ($user->getCustomer() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }
        $entityManager->remove($user);
        $entityManager->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT,);
    }
}
