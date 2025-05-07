<?php

  namespace App\Controller;

  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  use Symfony\Component\HttpFoundation\JsonResponse;
  use Symfony\Component\Routing\Annotation\Route;
  use Symfony\Component\Security\Http\Attribute\IsGranted;

  class ApiController extends AbstractController
  {
      #[Route('/api/profile', name: 'api_profile', methods: ['GET'])]
      #[IsGranted('IS_AUTHENTICATED_FULLY')]
      public function getProfile(): JsonResponse
      {
           $user = $this->getUser();
            if (!$user) {
                return new JsonResponse(['error' => 'User not found'], 404);
            }

            $userData = [
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
            ];
          return new JsonResponse($userData);
      }
  }