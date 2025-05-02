<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/products')]
class ProductController extends AbstractController
{
    /**
     * @param ProductRepository $productRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/', name: 'get_products', methods: [Request::METHOD_GET])]
    public function getProducts(ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        $products = $productRepository->findAll();
        $jsonProducts = $serializer->serialize($products, 'json');

        return new JsonResponse($jsonProducts, Response::HTTP_OK, [], true);
    }

    /**
     * @param int $id
     * @param ProductRepository $productRepository
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'get_product_item', methods: [Request::METHOD_GET])]
    public function getProductItem(int $id, ProductRepository $productRepository, SerializerInterface $serializer): JsonResponse
    {
        $product = $productRepository->find($id);

        if (!$product) {
            return new JsonResponse(['message' => 'Продукт не знайдено'], Response::HTTP_NOT_FOUND);
        }

        $jsonProduct = $serializer->serialize($product, 'json');

        return new JsonResponse($jsonProduct, Response::HTTP_OK, [], true);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    #[Route('/', name: 'post_products', methods: [Request::METHOD_POST])]
    public function postProducts(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $product = $serializer->deserialize($request->getContent(), Product::class, 'json');

        $errors = $validator->validate($product);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()][] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($product);
        $entityManager->flush();

        $jsonProduct = $serializer->serialize($product, 'json');

        return new JsonResponse($jsonProduct, Response::HTTP_CREATED, ['Location' => '/products/' . $product->getId()], true);
    }

    /**
     * @param int $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param ProductRepository $productRepository
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'patch_products', methods: [Request::METHOD_PATCH])]
    public function patchProducts(int $id, Request $request, EntityManagerInterface $entityManager, ProductRepository $productRepository, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $product = $productRepository->find($id);

        if (!$product) {
            return new JsonResponse(['message' => 'Продукт не знайдено'], Response::HTTP_NOT_FOUND);
        }

        $serializer->deserialize(
            $request->getContent(),
            Product::class,
            'json',
            ['object_to_populate' => $product]
        );

        $errors = $validator->validate($product);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()][] = $error->getMessage();
            }
            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @param int $id
     * @param EntityManagerInterface $entityManager
     * @param ProductRepository $productRepository
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'delete_products', methods: [Request::METHOD_DELETE])]
    public function deleteProducts(int $id, EntityManagerInterface $entityManager, ProductRepository $productRepository): JsonResponse
    {
        $product = $productRepository->find($id);

        if (!$product) {
            return new JsonResponse(['message' => 'Продукт не знайдено'], Response::HTTP_NOT_FOUND);
        }

        $entityManager->remove($product);
        $entityManager->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}