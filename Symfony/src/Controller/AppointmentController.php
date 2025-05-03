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

#[Route('/api/appointments')]
class AppointmentController extends AbstractController
{
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
    public function index(): JsonResponse
    {
        $appointments = $this->appointmentRepository->findAll();
        $data = $this->serializer->serialize($appointments, 'json', [
            'groups' => ['appointment', 'patient', 'doctor', 'diagnosis', 'treatment']
        ]);

        return new JsonResponse($data, Response::HTTP_OK, [], true);
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