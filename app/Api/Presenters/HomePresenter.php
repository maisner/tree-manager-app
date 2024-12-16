<?php

declare(strict_types = 1);

namespace App\Api\Presenters;

final class HomePresenter extends BasePresenter
{

    public function actionDefault(): void
    {
        $this->assertGET();

        $this->sendSuccessResponse(['api' => 'v1']);
    }

}