<?php

declare(strict_types=1);

namespace App\UI\API\Category;

use App\Domain\Category\CategoryId;
use App\UI\API\AbstractAction;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Validator\Constraints\Json;

final class CreateCategoryAction extends AbstractAction
{
    public function __construct(private readonly MessageBusInterface $commandBus){}

    public function __invoke(Request $request): Response
    {
        $command = CategoryCommandFactory::makeCreateCommand($request);

        /** @var CategoryId $id */
        $id = $this->commandBus->dispatch($command)->last(HandledStamp::class)->getResult();

        return new JsonResponse([
            'id' => $id->toString(),
        ], Response::HTTP_CREATED);
    }
}
