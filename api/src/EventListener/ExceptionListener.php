<?php

namespace App\EventListener;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ValidationFailedException) {
            $response = new JsonResponse([
                'message' => 'Validation Failed',
                'errors' => $this->formatValidationErrors($exception->getViolations()),
            ], JsonResponse::HTTP_BAD_REQUEST);

            $event->setResponse($response);
            return;
        }

        if ($exception instanceof UniqueConstraintViolationException) {
            $response = new JsonResponse([
                'message' => 'Este valor já está em uso',
                'field' => $this->extractUniqueField($exception),
            ], JsonResponse::HTTP_CONFLICT);

            $event->setResponse($response);
            return;
        }

        if ($exception instanceof HttpExceptionInterface) {
            $response = new JsonResponse([
                'message' => $exception->getMessage(),
            ], $exception->getStatusCode());

            $event->setResponse($response);
            return;
        }

        $response = new JsonResponse([
            'message' => 'Internal Server Error',
            'error' => $exception->getMessage(),
        ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);

        $event->setResponse($response);
    }

    private function formatValidationErrors(ConstraintViolationListInterface $violations): array
    {
        $errors = [];
        foreach ($violations as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }
        return $errors;
    }

    private function extractUniqueField(UniqueConstraintViolationException $exception): ?string
    {
        $message = $exception->getMessage();

        if (preg_match('/Duplicate entry .* for key \'([^\']+)\'/', $message, $matches)) {
            $constraintName = explode(".", $matches[1])[1];

            if (preg_match('/(?:unique|idx|UNIQ_IDENTIFIER)_(.+)/i', $constraintName, $fieldMatches)) {
                return strtolower(str_replace('_', ' ', $fieldMatches[1]));
            }

            return $constraintName;
        }

        return null;
    }
}
