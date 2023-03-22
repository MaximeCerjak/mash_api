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

class UserController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine, private UserPasswordHasherInterface $passwordHasher)
    {
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

    #[Route('/user/create', name: 'app_user_create')]
    public function create(Request $request, SerializerInterface $serializer)
    {
        $data = json_decode($request->getContent(), true);
        $user = new User();
        $user->setEmail($data['email']);
        $user->setName($data['name']);
        $user->setPseudo($data['pseudo']);
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $userData = [
                    'id' => $user->getId(),
                    'email' => $user->getEmail(),
                    'name' => $user->getName(),
                    'pseudo' => $user->getPseudo(),
                    'roles' => $user->getRoles()
                ];
        
        
            
        return new JsonResponse($userData, Response::HTTP_CREATED);
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
