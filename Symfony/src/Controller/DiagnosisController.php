<?php

namespace App\Controller;

use App\Entity\Diagnosis;
use App\Repository\DiagnosisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[Route('/api/diagnoses')]
class DiagnosisController extends AbstractController
{
    private $entityManager;
    private $diagnosisRepository;
    private $serializer;
    private $validator;

    public function __construct(EntityManagerInterface $entityManager, DiagnosisRepository $diagnosisRepository, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->diagnosisRepository = $diagnosisRepository;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    #[Route('/', name: 'api_diagnoses_index', methods: [Request::METHOD_GET])]
    public function index(): JsonResponse
    {
        $diagnoses = $this->diagnosisRepository->findAll();
        $jsonDiagnoses = $this->serializer->serialize($diagnoses, 'json', ['groups' => 'diagnosis']);

        return new JsonResponse($jsonDiagnoses, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'api_diagnoses_show', methods: [Request::METHOD_GET])]
    public function show(int $id): JsonResponse
    {
        $diagnosis = $this->diagnosisRepository->find($id);

        if (!$diagnosis) {
            return new JsonResponse(['message' => 'Діагноз не знайдено'], Response::HTTP_NOT_FOUND);
        }

        $jsonDiagnosis = $this->serializer->serialize($diagnosis, 'json', ['groups' => 'diagnosis']);

        return new JsonResponse($jsonDiagnosis, Response::HTTP_OK, [], true);
    }

    #[Route('/', name: 'api_diagnoses_create', methods: [Request::METHOD_POST])]
    public function create(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $diagnosis = $serializer->deserialize($request->getContent(), Diagnosis::class, 'json');

        $errors = $this->validator->validate($diagnosis);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()][] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($diagnosis);
        $this->entityManager->flush();

        $jsonDiagnosis = $this->serializer->serialize($diagnosis, 'json', ['groups' => 'diagnosis']);

        return new JsonResponse($jsonDiagnosis, Response::HTTP_CREATED, ['Location' => '/api/diagnoses/' . $diagnosis->getId()], true);
    }

    #[Route('/{id}', name: 'api_diagnoses_update', methods: [Request::METHOD_PATCH])]
    public function update(int $id, Request $request, SerializerInterface $serializer): JsonResponse
    {
        $diagnosis = $this->diagnosisRepository->find($id);

        if (!$diagnosis) {
            return new JsonResponse(['message' => 'Діагноз не знайдено'], Response::HTTP_NOT_FOUND);
        }

        $serializer->deserialize(
            $request->getContent(),
            Diagnosis::class,
            'json',
            ['object_to_populate' => $diagnosis]
        );

        $errors = $this->validator->validate($diagnosis);

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

    #[Route('/{id}', name: 'api_diagnoses_delete', methods: [Request::METHOD_DELETE])]
    public function delete(int $id): JsonResponse
    {
        $diagnosis = $this->diagnosisRepository->find($id);

        if (!$diagnosis) {
            return new JsonResponse(['message' => 'Діагноз не знайдено'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($diagnosis);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}