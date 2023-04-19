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
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController
{
    public function __construct(
        private ManagerRegistry $doctrine,
        private UserPasswordHasherInterface $passwordHasher,
        private JWTTokenManagerInterface $JWTManager,
        private ValidatorInterface $validator
    ) {
    }

    /**
     * @Route("/user", name="app_user", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns a list of users",
     *     @OA\JsonContent(
     *         type="array",
     *         @OA\Items(ref=@Model(type=User::class, groups={"full"}))
     *     )
     * )
     * @OA\Parameter(
     *     name="order",
     *     in="query",
     *     description="The field used to order users",
     *     @OA\Schema(type="string")
     * )
     * @OA\Tag(name="users")
     * @Security(name="Bearer")
     */
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

    /**
     * @Route("/user/create", methods={"POST"})
     * @OA\Response(
     *     response=201,
     *     description="Creates a new user",
     *     @OA\JsonContent(ref=@Model(type=User::class, groups={"user:create"}))
     * )
     * @OA\RequestBody(
     *     description="User data",
     *     required=true,
     *     @OA\JsonContent(
     *         @OA\Property(
     *             property="email",
     *             type="string",
     *             description="The user's email",
     *             example="user@example.com"
     *         ),
     *         @OA\Property(
     *             property="name",
     *             type="string",
     *             description="The user's name",
     *              example="John Doe"
     *         ),
     *         @OA\Property(
     *             property="pseudo",
     *             type="string",
     *             description="The user's pseudo",
     *             example="John"
     *         ),
     *         @OA\Property(
     *             property="password",
     *             type="string",
     *             description="The user's password",
     *             example="secret"
     *         )
     *     )
     * )
     * @OA\Tag(name="users")
     * @Security(name="Bearer")
     */
    public function create(Request $request, JWTTokenManagerInterface $jwtManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = new User();
        $user->setEmail($data['email']);
        $user->setName($data['name']);
        $user->setPseudo($data['pseudo']);

        if ($data['typeUser'] === 'creator') {
            $user->setRoles(['ROLE_CREATOR']);
        } else {
            $user->setRoles(['ROLE_USER']);
        }

        $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $token = $jwtManager->create($user);
        //New JsonResponse return message, status code and user 
        return new JsonResponse(['message' => 'User created', 'status' => Response::HTTP_CREATED, 'user' => $user, 'token' => $token], Response::HTTP_CREATED);
    }

    /**
     * @Route("/user/role", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns the details of the authenticated user",
     *     @OA\JsonContent(ref=@Model(type=User::class, groups={"user:details"}))
     * )
     * @OA\Tag(name="users")
     * @Security(name="Bearer")
     */
    public function userRole(UserInterface $user): JsonResponse
    {
        $data = [
            'roles' => $user->getRoles(),
            'id' => $user->getUserIdentifier(),
        ];

        return $this->json($data);
    }

    /**
     * @Route("/user/{id}", methods={"GET"})
     * @OA\Response(
     *     response=200,
     *     description="Returns the details of a specific user",
     *     @OA\JsonContent(ref=@Model(type=User::class, groups={"user:details"}))
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The user ID",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     * @OA\Tag(name="users")
     * @Security(name="Bearer")
     */
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

    /**
     * @Route("/user/{id}/delete", methods={"DELETE"})
     * @OA\Response(
     *     response=200,
     *     description="Deletes a user",
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="message", type="string", example="User deleted")
     *     )
     * )
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The user ID",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     * @OA\Tag(name="users")
     * @Security(name="Bearer")
     */
    public function delete(UserRepository $userRepository, $id): JsonResponse
    {
        $user = $userRepository->find($id);
        $entityManager = $this->doctrine->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->json('User deleted');
    }

    /**
     * @Route("/user/{id}/update", methods={"PUT"})
     * @OA\Response(
     *     response=200,
     *     description="Updates a user",
     *     @OA\JsonContent(ref=@Model(type=User::class, groups={"user:update"}))
     * )
     * 
     * @OA\RequestBody(
     *     description="User data",
     *     required=true,
     *     @OA\JsonContent(ref=@Model(type=User::class, groups={"user:update"}))
     * )
     * 
     * @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="The user ID",
     *     required=true,
     *     @OA\Schema(type="integer")
     * )
     * 
     * @OA\Tag(name="users")
     * @Security(name="Bearer")
     */
    public function update(UserRepository $userRepository, $id): JsonResponse
    {
        $user = $userRepository->find($id);
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->json('User updated');
    }
}
