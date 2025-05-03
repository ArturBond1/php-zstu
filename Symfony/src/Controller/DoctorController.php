<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Repository\DoctorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\QueryBuilder;

#[Route('/api/doctors')]
class DoctorController extends AbstractController
{
    private const DEFAULT_ITEMS_PER_PAGE = 10;

    private $entityManager;
    private $doctorRepository;
    private $serializer;
    private $validator;

    public function __construct(EntityManagerInterface $entityManager, DoctorRepository $doctorRepository, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->doctorRepository = $doctorRepository;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    #[Route('/', name: 'api_doctors_index', methods: [Request::METHOD_GET])]
    public function index(Request $request): JsonResponse
    {
        $queryBuilder = $this->doctorRepository->createQueryBuilder('d');

        $this->addFilter($queryBuilder, 'd.firstName', $request->query->get('firstName'));
        $this->addFilter($queryBuilder, 'd.lastName', $request->query->get('lastName'));
        $this->addFilter($queryBuilder, 'd.specialization', $request->query->get('specialization'));
        $this->addFilter($queryBuilder, 'd.phoneNumber', $request->query->get('phoneNumber'));
        $this->addFilter($queryBuilder, 'd.email', $request->query->get('email'));

        $page = $request->query->getInt('page', 1);
        $itemsPerPage = $request->query->getInt('itemsPerPage', self::DEFAULT_ITEMS_PER_PAGE);
        $totalItems = count($queryBuilder->getQuery()->getResult());
        $totalPages = ceil($totalItems / $itemsPerPage);

        $queryBuilder
            ->setFirstResult(($page - 1) * $itemsPerPage)
            ->setMaxResults($itemsPerPage);

        $doctors = $queryBuilder->getQuery()->getResult();
        $jsonDoctors = $this->serializer->serialize($doctors, 'json', ['groups' => 'doctor']);

        $response = new JsonResponse($jsonDoctors, Response::HTTP_OK, [], true);
        $response->headers->set('X-Total-Count', $totalItems);
        $response->headers->set('X-Current-Page', $page);
        $response->headers->set('X-Items-Per-Page', $itemsPerPage);
        $response->headers->set('X-Total-Pages', $totalPages);

        return $response;
    }

    private function addFilter(QueryBuilder $queryBuilder, string $field, $value): void
    {
        if ($value !== null && $value !== '') {
            $queryBuilder->andWhere($queryBuilder->expr()->like($field, ':'.$this->generateParameterName($field)))
                ->setParameter($this->generateParameterName($field), '%'.$value.'%');
        }
    }

    private function generateParameterName(string $field): string
    {
        return str_replace('.', '_', $field) . '_' . uniqid();
    }

    #[Route('/{id}', name: 'api_doctors_show', methods: [Request::METHOD_GET])]
    public function show(int $id): JsonResponse
    {
        $doctor = $this->doctorRepository->find($id);

        if (!$doctor) {
            return new JsonResponse(['message' => 'Лікаря не знайдено'], Response::HTTP_NOT_FOUND);
        }

        $jsonDoctor = $this->serializer->serialize($doctor, 'json', ['groups' => 'doctor']);

        return new JsonResponse($jsonDoctor, Response::HTTP_OK, [], true);
    }

    #[Route('/', name: 'api_doctors_create', methods: [Request::METHOD_POST])]
    public function create(Request $request, SerializerInterface $serializer): JsonResponse
    {
        $doctor = $serializer->deserialize($request->getContent(), Doctor::class, 'json');

        $errors = $this->validator->validate($doctor);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()][] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($doctor);
        $this->entityManager->flush();

        $jsonDoctor = $this->serializer->serialize($doctor, 'json', ['groups' => 'doctor']);

        return new JsonResponse($jsonDoctor, Response::HTTP_CREATED, ['Location' => '/api/doctors/' . $doctor->getId()], true);
    }

    #[Route('/{id}', name: 'api_doctors_update', methods: [Request::METHOD_PATCH])]
    public function update(int $id, Request $request, SerializerInterface $serializer): JsonResponse
    {
        $doctor = $this->doctorRepository->find($id);

        if (!$doctor) {
            return new JsonResponse(['message' => 'Лікаря не знайдено'], Response::HTTP_NOT_FOUND);
        }

        $serializer->deserialize(
            $request->getContent(),
            Doctor::class,
            'json',
            ['object_to_populate' => $doctor]
        );

        $errors = $this->validator->validate($doctor);

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

    #[Route('/{id}', name: 'api_doctors_delete', methods: [Request::METHOD_DELETE])]
    public function delete(int $id): JsonResponse
    {
        $doctor = $this->doctorRepository->find($id);

        if (!$doctor) {
            return new JsonResponse(['message' => 'Лікаря не знайдено'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($doctor);
        $this->entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}