<?php

namespace App\Controller;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class CustomerController
 * @package App\Controller
 */
class CustomerController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * CustomerController constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function auth(Request $request): JsonResponse
    {
        try {
            $customerRepository = $this->em->getRepository(Customer::class);

            $data = $this->extractCustomerData($request);

            /** @var Customer|null $customer */
            $customer = $customerRepository->findOneBy(
                [
                    'email'    => $data['email'],
                    'password' => $data['password'],
                ]
            );

            if (!$customer) {
                return new JsonResponse(['customer not found'], Response::HTTP_UNAUTHORIZED);
            }

            return new JsonResponse($this->preparedDataToAnswer($customer));
        } catch (Throwable $exception) {
            return new JsonResponse(
                ['error' => $exception->getMessage(), 'code' => $exception->getCode()],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function singUp(Request $request): JsonResponse
    {
        try {
            $data = $this->extractCustomerData($request);

            if (empty($data['email']) || empty($data['password'])) {
                throw new Exception('Email, password should`t be empty');
            }

            $customerRepository = $this->em->getRepository(Customer::class);

            $customer = $customerRepository->findOneBy(['email' => $data['email']]);

            if ($customer) {
                throw new Exception('User allready exist');
            }

            $customer = (new Customer())
                ->setFullname($data['fullname'])
                ->setPassword($data['password'])
                ->setEmail($data['email'])
                ->setPhone($data['phone']);

            $this->em->persist($customer);
            $this->em->flush();

            return new JsonResponse($this->preparedDataToAnswer($customer));
        } catch (Throwable $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    private function extractCustomerData(Request $request): array
    {
        return [
            'phone'    => $request->get('phone'),
            'email'    => $request->get('email'),
            'fullname' => $request->get('fullname'),
            'password' => $request->get('password'),
        ];
    }

    /**
     * @param Customer|null $customer
     *
     * @return array
     */
    private function preparedDataToAnswer(?Customer $customer): array
    {
        if (null === $customer) {
            return [];
        }

        return [
            'id'       => $customer->getId(),
            'email'    => $customer->getEmail(),
            'phone'    => $customer->getPhone(),
            'fullname' => $customer->getFullname(),
            'password' => $customer->getPassword(),
        ];
    }
}