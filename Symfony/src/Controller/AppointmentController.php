<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Repository\AppointmentRepository;
use App\Repository\PatientRepository;
use App\Repository\DoctorRepository;
use App\Repository\DiagnosisRepository;
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

#[Route('/api/appointments')]
class AppointmentController extends AbstractController
{
    private const DEFAULT_ITEMS_PER_PAGE = 10;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private AppointmentRepository $appointmentRepository,
        private PatientRepository $patientRepository,
        private DoctorRepository $doctorRepository,
        private DiagnosisRepository $diagnosisRepository,
        private TreatmentRepository $treatmentRepository,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator
    ) {}

    #[Route('/', name: 'appointment_index', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        $queryBuilder = $this->appointmentRepository->createQueryBuilder('a');
        $this->addFilter($queryBuilder, 'a.appointmentDate', $request->query->get('appointmentDate'));
        $this->addFilter($queryBuilder, 'a.status', $request->query->get('status'));
        $this->addFilter($queryBuilder, 'patient.id', $request->query->get('patient'));
        $this->addFilter($queryBuilder, 'doctor.id', $request->query->get('doctor'));
        $this->addFilter($queryBuilder, 'diagnosis.id', $request->query->get('diagnosis'));

        $page = $request->query->getInt('page', 1);
        $itemsPerPage = $request->query->getInt('itemsPerPage', self::DEFAULT_ITEMS_PER_PAGE);
        $totalItems = count($queryBuilder->getQuery()->getResult());
        $totalPages = ceil($totalItems / $itemsPerPage);

        $queryBuilder
            ->setFirstResult(($page - 1) * $itemsPerPage)
            ->setMaxResults($itemsPerPage);

        $appointments = $queryBuilder
            ->leftJoin('a.patient', 'patient')
            ->leftJoin('a.doctor', 'doctor')
            ->leftJoin('a.diagnosis', 'diagnosis')
            ->getQuery()
            ->getResult();

        $data = $this->serializer->serialize($appointments, 'json', [
            'groups' => ['appointment', 'patient', 'doctor', 'diagnosis', 'treatment']
        ]);

        $response = new JsonResponse($data, Response::HTTP_OK, [], true);
        $response->headers->set('X-Total-Count', $totalItems);
        $response->headers->set('X-Current-Page', $page);
        $response->headers->set('X-Items-Per-Page', $itemsPerPage);
        $response->headers->set('X-Total-Pages', $totalPages);

        return $response;
    }

    private function addFilter(QueryBuilder $queryBuilder, string $field, $value): void
    {
        if ($value !== null && $value !== '') {
            $queryBuilder->andWhere($queryBuilder->expr()->eq($field, ':'.$this->generateParameterName($field)))
                ->setParameter($this->generateParameterName($field), $value);
        }
    }

    private function generateParameterName(string $field): string
    {
        return str_replace('.', '_', $field) . '_' . uniqid();
    }

    #[Route('/{id}', name: 'appointment_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $appointment = $this->appointmentRepository->find($id);

        if (!$appointment) {
            return $this->json(['message' => 'Appointment not found'], Response::HTTP_NOT_FOUND);
        }

        $data = $this->serializer->serialize($appointment, 'json', [
            'groups' => ['appointment', 'patient', 'doctor', 'diagnosis', 'treatment']
        ]);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
    }

    #[Route('/', name: 'appointment_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $appointment = new Appointment();
        $appointment->setAppointmentDate(new \DateTime($data['appointmentDate']));
        $appointment->setStatus($data['status'] ?? 'scheduled');

        if (isset($data['patient'])) {
            $patient = $this->patientRepository->find($data['patient']);
            if (!$patient) {
                return $this->json(['message' => 'Patient not found'], Response::HTTP_BAD_REQUEST);
            }
            $appointment->setPatient($patient);
        }

        if (isset($data['doctor'])) {
            $doctor = $this->doctorRepository->find($data['doctor']);
            if (!$doctor) {
                return $this->json(['message' => 'Doctor not found'], Response::HTTP_BAD_REQUEST);
            }
            $appointment->setDoctor($doctor);
        }

        if (isset($data['diagnosis'])) {
            $diagnosis = $this->diagnosisRepository->find($data['diagnosis']);
            if (!$diagnosis) {
                return $this->json(['message' => 'Diagnosis not found'], Response::HTTP_BAD_REQUEST);
            }
            $appointment->setDiagnosis($diagnosis);
        }

        if (isset($data['treatment'])) {
            $treatment = $this->treatmentRepository->find($data['treatment']);
            if (!$treatment) {
                return $this->json(['message' => 'Treatment not found'], Response::HTTP_BAD_REQUEST);
            }
            $appointment->setTreatment($treatment);
        }

        $errors = $this->validator->validate($appointment);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()][] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->persist($appointment);
        $this->entityManager->flush();

        $responseData = $this->serializer->serialize($appointment, 'json', [
            'groups' => ['appointment', 'patient', 'doctor', 'diagnosis', 'treatment']
        ]);

        return new JsonResponse($responseData, Response::HTTP_CREATED, [], true);
    }

    #[Route('/{id}', name: 'appointment_update', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        $appointment = $this->appointmentRepository->find($id);

        if (!$appointment) {
            return $this->json(['message' => 'Appointment not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['appointmentDate'])) {
            $appointment->setAppointmentDate(new \DateTime($data['appointmentDate']));
        }

        if (isset($data['status'])) {
            $appointment->setStatus($data['status']);
        }

        if (isset($data['patient'])) {
            $patient = $this->patientRepository->find($data['patient']);
            if (!$patient) {
                return $this->json(['message' => 'Patient not found'], Response::HTTP_BAD_REQUEST);
            }
            $appointment->setPatient($patient);
        }

        if (isset($data['doctor'])) {
            $doctor = $this->doctorRepository->find($data['doctor']);
            if (!$doctor) {
                return $this->json(['message' => 'Doctor not found'], Response::HTTP_BAD_REQUEST);
            }
            $appointment->setDoctor($doctor);
        }

        if (isset($data['diagnosis'])) {
            $diagnosis = $this->diagnosisRepository->find($data['diagnosis']);
            if (!$diagnosis) {
                return $this->json(['message' => 'Diagnosis not found'], Response::HTTP_BAD_REQUEST);
            }
            $appointment->setDiagnosis($diagnosis);
        }

        if (isset($data['treatment'])) {
            $treatment = $this->treatmentRepository->find($data['treatment']);
            if (!$treatment) {
                return $this->json(['message' => 'Treatment not found'], Response::HTTP_BAD_REQUEST);
            }
            $appointment->setTreatment($treatment);
        }

        // Validate
        $errors = $this->validator->validate($appointment);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()][] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $this->entityManager->flush();

        $responseData = $this->serializer->serialize($appointment, 'json', [
            'groups' => ['appointment', 'patient', 'doctor', 'diagnosis', 'treatment']
        ]);

        return new JsonResponse($responseData, Response::HTTP_OK, [], true);
    }

    #[Route('/{id}', name: 'appointment_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $appointment = $this->appointmentRepository->find($id);

        if (!$appointment) {
            return $this->json(['message' => 'Appointment not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($appointment);
        $this->entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}