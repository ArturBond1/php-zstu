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
use Doctrine\ORM\QueryBuilder;

#[Route('/api/treatments')]
class TreatmentController extends AbstractController
{
    private const DEFAULT_ITEMS_PER_PAGE = 10;

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
    public function index(Request $request): JsonResponse
    {
        $queryBuilder = $this->treatmentRepository->createQueryBuilder('t');

        $this->addFilter($queryBuilder, 't.description', $request->query->get('description'));
        $this->addFilter($queryBuilder, 't.medication', $request->query->get('medication'));
        $this->addFilter($queryBuilder, 't.dosage', $request->query->get('dosage'));
        $this->addFilter($queryBuilder, 'appointment.id', $request->query->get('appointment'));

        $page = $request->query->getInt('page', 1);
        $itemsPerPage = $request->query->getInt('itemsPerPage', self::DEFAULT_ITEMS_PER_PAGE);
        $totalItems = count($queryBuilder->getQuery()->getResult());
        $totalPages = ceil($totalItems / $itemsPerPage);

        $queryBuilder
            ->setFirstResult(($page - 1) * $itemsPerPage)
            ->setMaxResults($itemsPerPage);

        $treatments = $queryBuilder
            ->leftJoin('t.appointment', 'appointment')
            ->getQuery()
            ->getResult();
        $jsonTreatments = $this->serializer->serialize($treatments, 'json', ['groups' => 'treatment']);

        $response = new JsonResponse($jsonTreatments, Response::HTTP_OK, [], true);
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