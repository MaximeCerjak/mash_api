<?php

namespace App\Controller;

use App\Entity\Download;
use App\Repository\PictureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;
use App\Entity\Picture;
use Doctrine\Persistence\ManagerRegistry;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use App\Entity\User;

class PictureController extends AbstractController
{

    public function __construct(
        private ManagerRegistry $doctrine,
        private SerializerInterface $serializer
    ) {
    }

    #[Route('/picture', name: 'app_picture')]
    public function index(PictureRepository $pictureRepository): JsonResponse
    {
        $pictures = $pictureRepository->findAll();
        $data = [];
        foreach ($pictures as $picture) {
            $data[] = [
                'id' => $picture->getId(),
                'title' => $picture->getPicTitle(),
                'url' => $picture->getPicUrl(),
                'legend' => $picture->getPicLegend(),
                'format' => $picture->getPicFormat(),
                'description' => $picture->getCreaDescription()
            ];
        }
        return $this->json($data);
    }

    /**
     * @Route("/picture/upload", methods={"POST"})
     * @OA\Response(
     *     response=201,
     *     description="Upload a picture",
     *     @OA\JsonContent(ref=@Model(type=User::class, groups={"picture:upload"}))
     * )
     * @OA\RequestBody(
     *     description="Picture data",
     *     required=true,
     *     @OA\JsonContent(
     *         @OA\Property(
     *             property="pic_title",
     *             type="string",
     *             description="The picture's title",
     *             example="My picture"
     *         ),
     *         @OA\Property(
     *             property="pic_legend",
     *             type="string",
     *             description="Tag the picture with a legend",
     *              example="#design #art #graphicdesign"
     *         ),
     *         @OA\Property(
     *             property="crea_description",
     *             type="string",
     *             description="Describe the picture",
     *             example="This is a picture of a cat"
     *         ),
     *     )
     * )
     * @OA\Tag(name="pictures")
     * @Security(name="Bearer")
     */
    public function upload(Request $request): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Vous devez être connecté pour effectuer cette action'], Response::HTTP_UNAUTHORIZED);
        }

        //if role = ["ROLE_USER"] user can't upload picture
        if (!in_array("ROLE_CREATOR", $user->getRoles())) {
            return new JsonResponse(['error' => 'Vous n\'avez pas les droits pour effectuer cette action'], Response::HTTP_UNAUTHORIZED);
        }

        // Récupérer le fichier envoyé
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('file');

        if (!$uploadedFile) {
            return new JsonResponse(['error' => 'Aucun fichier envoyé'], Response::HTTP_BAD_REQUEST);
        }

        // Vérifier l'extension et la taille du fichier
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $maxSize = 5 * 1024 * 1024; // Taille maximale de 5 Mo

        $extension = $uploadedFile->getClientOriginalExtension();
        $size = $uploadedFile->getSize();

        if (!in_array($extension, $allowedExtensions)) {
            return new JsonResponse(['error' => 'Extension de fichier non autorisée'], Response::HTTP_BAD_REQUEST);
        }

        if ($size > $maxSize) {
            return new JsonResponse(['error' => 'La taille du fichier dépasse la limite autorisée'], Response::HTTP_BAD_REQUEST);
        }

        // Déplacer le fichier dans un répertoire de stockage
        $targetDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads';
        $fileName = uniqid() . '.' . $extension;
        $uploadedFile->move($targetDirectory, $fileName);

        // Enregistrer les informations dans l'entité Picture
        $picture = new Picture();
        $picTitle = $request->request->get('pic_title');
        $picLegend = $request->request->get('pic_legend');
        $creaDescription = $request->request->get('crea_description');

        $picture->setPicTitle($picTitle);
        $picture->setPicLegend($picLegend);
        $picture->setCreaDescription($creaDescription);
        $picture->setPicUrl('/uploads/' . $fileName);
        $picture->setPicFormat($extension);
        $picture->setUser($user);


        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($picture);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Image uploaded', 'status' => Response::HTTP_CREATED, 'picture' => $picture], Response::HTTP_CREATED);
    }

    /**
     * @Route("/picture/download/{id}", methods={"GET"}, name="app_picture_download")
     * @OA\Response(
     *     response=200,
     *     description="Download a picture",
     *     @OA\JsonContent(ref=@Model(type=User::class, groups={"picture:download"}))
     * )
     * @OA\Tag(name="pictures")
     * @Security(name="Bearer")
     */
    public function download(Picture $picture): BinaryFileResponse|JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Vous devez être connecté pour effectuer cette action'], Response::HTTP_UNAUTHORIZED);
        }

        $picturePath = $this->getParameter('kernel.project_dir') . '/public' . $picture->getPicUrl();

        if (!file_exists($picturePath)) {
            return new JsonResponse(['error' => 'Fichier introuvable'], Response::HTTP_NOT_FOUND);
        }

        $download = new Download();
        $download->setUser($user);
        $download->setPicture($picture);
        $download->setDownloadDate(new \DateTime());

        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($download);
        $entityManager->flush();

        $response = new BinaryFileResponse($picturePath);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, basename($picture->getPicUrl()));

        return $response;
    }

    /**
     * @Route("/pictures/user/{userId}", methods={"GET"}, name="app_picture_list_by_user")
     * @OA\Response(
     *     response=200,
     *     description="Get pictures by user",
     *     @OA\JsonContent(ref=@Model(type=Picture::class, groups={"picture:list"}))
     * )
     * @OA\Tag(name="pictures")
     * @Security(name="Bearer")
     */
    public function showPicturesByUser(int $userId): JsonResponse
    {
        $user = $this->doctrine->getRepository(User::class)->find($userId);

        if (!$user) {
            return new JsonResponse(['error' => 'Utilisateur non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $entityManager = $this->doctrine->getManager();
        $pictures = $entityManager->getRepository(Picture::class)->findBy(['user' => $user]);

        $pictureArray = [];

        foreach ($pictures as $picture) {
            $pictureArray[] = [
                'id' => $picture->getId(),
                'pic_title' => $picture->getPicTitle(),
                'pic_legend' => $picture->getPicLegend(),
                'crea_description' => $picture->getCreaDescription(),
                'pic_url' => $picture->getPicUrl(),
                'pic_format' => $picture->getPicFormat(),
                'user' => [
                    'id' => $picture->getUser()->getId(),
                    'email' => $picture->getUser()->getEmail(),
                    'roles' => $picture->getUser()->getRoles(),
                ],
            ];
        }

        return new JsonResponse($pictureArray, Response::HTTP_OK);
    }
}
