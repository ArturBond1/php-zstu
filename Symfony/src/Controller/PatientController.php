<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[Route('/api/patients')]
class PatientController extends AbstractController
{
    private $entityManager;
    private $patientRepository;
    private $serializer;
    private $validator;

    public function __construct(
        EntityManagerInterface $entityManager,
        PatientRepository $patientRepository,
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->entityManager = $entityManager;
        $this->patientRepository = $patientRepository;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    #[Route('/', name: 'api_patients_index', methods: [Request::METHOD_GET])]
    public function index(): JsonResponse
    {
        $patients = $this->patientRepository->findAll();
        $jsonPatients = $this->serializer->serialize($patients, 'json', ['groups' => 'patient']);

        return new JsonResponse($jsonPatients, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'api_patients_show', methods: [Request::METHOD_GET])]
    public function show(int $id): JsonResponse
    {
        $patient = $this->patientRepository->find($id);

        if (!$patient) {
            return new JsonResponse(['message' => 'Пацієнта не знайдено'], Response::HTTP_NOT_FOUND);
        }

        $jsonPatient = $this->serializer->serialize($patient, 'json', ['groups' => 'patient']);

        return new JsonResponse($jsonPatient, Response::HTTP_OK, [], true);
    }

    #[Route('/', name: 'api_patients_create', methods: [Request::METHOD_POST])]
    public function create(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $patient = $serializer->deserialize($request->getContent(), Patient::class, 'json');

        $errors = $this->validator->validate($patient);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()][] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($patient);
        $this->entityManager->flush();

        $jsonPatient = $serializer->serialize($patient, 'json', ['groups' => 'patient']);

        return new JsonResponse($jsonPatient, Response::HTTP_CREATED, ['Location' => '/api/patients/' . $patient->getId()], true);
    }

    #[Route('/{id}', name: 'api_patients_update', methods: [Request::METHOD_PATCH])]
    public function update(int $id, Request $request, SerializerInterface $serializer): JsonResponse
    {
        $patient = $this->patientRepository->find($id);

        if (!$patient) {
            return new JsonResponse(['message' => 'Пацієнта не знайдено'], Response::HTTP_NOT_FOUND);
        }

        $serializer->deserialize(
            $request->getContent(),
            Patient::class,
            'json',
            ['object_to_populate' => $patient]
        );

        $errors = $this->validator->validate($patient);

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

    #[Route('/{id}', name: 'api_patients_delete', methods: [Request::METHOD_DELETE])]
    public function delete(int $id): JsonResponse
    {
        $patient = $this->patientRepository->find($id);

        if (!$patient) {
            return new JsonResponse(['message' => 'Пацієнта не знайдено'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($patient);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}