<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use Symfony\Component\Serializer\SerializerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class UserController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $doctrine,
        private UserPasswordHasherInterface $passwordHasher,
        private JWTTokenManagerInterface $JWTManager,
        private ValidatorInterface $validator
    ) {
    }

    #[Route('/user', name: 'app_user')]
    public function index(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();
        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getName(),
                'pseudo' => $user->getPseudo(),
                'roles' => $user->getRoles(),
                'password' => $user->getPassword(),
                'picture' => $user->getPicture(),
            ];
        }
        return $this->json($data);
    }

    #[Route('/user/{id}/update', name: 'app_user_id_update')]
    public function update(UserRepository $userRepository, $id): JsonResponse
    {
        $user = $userRepository->find($id);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->json('User updated');
    }

    #[Route('/user/signup', name: 'app_user_create')]
    public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $data = json_decode($request->getContent(), true);

        $constraints = new Assert\Collection([
            'email' => [
                new Assert\NotBlank(),
                new Assert\Email(),
            ],
            'name' => [
                new Assert\NotBlank(),
                new Assert\Length(['max' => 255]),
            ],
            'pseudo' => [
                new Assert\NotBlank(),
                new Assert\Length(['max' => 255]),
            ],
            'password' => [
                new Assert\NotBlank(),
                new Assert\Length(['min' => 8]),
            ],
        ]);

        $violations = $validator->validate($data, $constraints);

        if ($violations->count() > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setName($data['name']);
        $user->setPseudo($data['pseudo']);
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $token = $this->JWTManager->create($user);

        $userData = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'pseudo' => $user->getPseudo(),
            'roles' => $user->getRoles(),
            'password' => $user->getPassword(),
            'picture' => $user->getPicture(),
            'token' => $token
        ];

        return $this->json($serializer->serialize($userData, 'json'), Response::HTTP_CREATED);
    }

    #[Route('/user/{id}', name: 'app_user_id')]
    public function show(UserRepository $userRepository, $id): JsonResponse
    {
        $user = $userRepository->find($id);
        $data = [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getName(),
            'pseudo' => $user->getPseudo(),
            'roles' => $user->getRoles(),
            'password' => $user->getPassword(),
            'picture' => $user->getPicture(),
        ];
        return $this->json($data);
    }

    #[Route('/user/{id}/delete', name: 'app_user_id_delete')]
    public function delete(UserRepository $userRepository, $id): JsonResponse
    {
        $user = $userRepository->find($id);
        $entityManager = $this->doctrine->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->json('User deleted');
    }


    // #[Route('/user/create', name: 'app_user_create')]
    // public function create(Request $request): JsonResponse
    // {
    //     $data = json_decode($request->getContent(), true);
    //     dd($data);
    //     die;
    //     $user = new User();
    //     $user->setEmail($data['email']);
    //     $user->setName($data['name']);
    //     $user->setPseudo($data['pseudo']);
    //     // $user->setRoles(['ROLE_USER']);
    //     $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));
    //     dump($user);
    //     $entityManager = $this->doctrine->getManager();
    //     $entityManager->persist($user);
    //     $entityManager->flush();
    //     dump($user);
    //     $userData = [
    //         'id' => $user->getId(),
    //         'email' => $user->getEmail(),
    //         'name' => $user->getName(),
    //         'pseudo' => $user->getPseudo()
    //         // 'roles' => $user->getRoles()
    //     ];



    //     return new JsonResponse($userData, Response::HTTP_CREATED);
    // }

}
