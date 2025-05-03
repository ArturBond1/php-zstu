<?php

namespace App\Controller;

use App\Entity\Treatment;
use App\Repository\TreatmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[Route('/api/treatments')]
class TreatmentController extends AbstractController
{
    private $entityManager;
    private $treatmentRepository;
    private $serializer;
    private $validator;

    public function __construct(EntityManagerInterface $entityManager, TreatmentRepository $treatmentRepository, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->treatmentRepository = $treatmentRepository;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    #[Route('/', name: 'api_treatments_index', methods: [Request::METHOD_GET])]
    public function index(): JsonResponse
    {
        $treatments = $this->treatmentRepository->findAll();
        $jsonTreatments = $this->serializer->serialize($treatments, 'json', ['groups' => 'treatment']);

        return new JsonResponse($jsonTreatments, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'api_treatments_show', methods: [Request::METHOD_GET])]
    public function show(int $id): JsonResponse
    {
        $treatment = $this->treatmentRepository->find($id);

        if (!$treatment) {
            return new JsonResponse(['message' => 'Лікування не знайдено'], Response::HTTP_NOT_FOUND);
        }

        $jsonTreatment = $this->serializer->serialize($treatment, 'json', ['groups' => 'treatment']);

        return new JsonResponse($jsonTreatment, Response::HTTP_OK, [], true);
    }

    #[Route('/', name: 'api_treatments_create', methods: [Request::METHOD_POST])]
    public function create(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $treatment = $serializer->deserialize($request->getContent(), Treatment::class, 'json');
        $errors = $this->validator->validate($treatment);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()][] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($treatment);
        $this->entityManager->flush();

        $jsonTreatment = $this->serializer->serialize($treatment, 'json', ['groups' => 'treatment']);

        return new JsonResponse($jsonTreatment, Response::HTTP_CREATED, ['Location' => '/api/treatments/' . $treatment->getId()], true);
    }

    #[Route('/{id}', name: 'api_treatments_update', methods: [Request::METHOD_PATCH])]
    public function update(int $id, Request $request, SerializerInterface $serializer): JsonResponse
    {
        $treatment = $this->treatmentRepository->find($id);

        if (!$treatment) {
            return new JsonResponse(['message' => 'Лікування не знайдено'], Response::HTTP_NOT_FOUND);
        }

        $serializer->deserialize(
            $request->getContent(),
            Treatment::class,
            'json',
            ['object_to_populate' => $treatment]
        );

        $errors = $this->validator->validate($treatment);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()][] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/{id}', name: 'api_treatments_delete', methods: [Request::METHOD_DELETE])]
    public function delete(int $id): JsonResponse
    {
        $treatment = $this->treatmentRepository->find($id);

        if (!$treatment) {
            return new JsonResponse(['message' => 'Лікування не знайдено'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($treatment);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}