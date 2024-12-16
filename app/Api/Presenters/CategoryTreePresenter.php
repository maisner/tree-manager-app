<?php

declare(strict_types=1);

namespace App\Api\Presenters;

use App\Model\Category\Facades\CategoryTreeFacade;

final class CategoryTreePresenter extends BasePresenter
{
    public function __construct(private readonly CategoryTreeFacade $categoryTreeFacade)
    {
        parent::__construct();
    }

    public function actionFindAll(): void
    {
        $this->assertGET();

        $this->sendSuccessResponse($this->categoryTreeFacade->getTrees());
    }

    public function actionAddRootNode(): void
    {
        $this->assertPOST();

        $body = $this->getRequestBody();

        $response = $this->categoryTreeFacade->addRootNode($body->getStringValue('name'));

        $this->sendSuccessResponse($response);
    }

    public function actionAddNode(): void
    {
        $this->assertPOST();

        $body = $this->getRequestBody();

        $response = $this->categoryTreeFacade->addNode(
            $body->getStringValue('name'),
            $body->getIntValue('parent_id')
        );

        $this->sendSuccessResponse($response);
    }

    public function actionUpdateNode(int $id): void
    {
        $this->assertPUT();

        $body = $this->getRequestBody();

        $this->categoryTreeFacade->updateNode($id, $body->getStringValue('name'));

        $this->sendSuccessResponse();
    }

    public function actionDeleteNode(int $id): void
    {
        $this->assertDELETE();

        $this->categoryTreeFacade->deleteNode($id);

        $this->sendSuccessResponse();
    }

}