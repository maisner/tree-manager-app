<?php

declare(strict_types = 1);

namespace App\Api\Presenters;

use App\Api\Utils\RequestBody;
use Nette;
use Nette\Http\IResponse;

abstract class BasePresenter extends Nette\Application\UI\Presenter
{

    public function assertPOST(): void
    {
        if ($this->getHttpRequest()->isMethod('POST')) {
            return;
        }

        $this->sendNotAllowedMethodResponse();
    }

    public function assertPUT(): void
    {
        if ($this->getHttpRequest()->isMethod('PUT')) {
            return;
        }

        $this->sendNotAllowedMethodResponse();
    }

    public function assertGET(): void
    {
        if ($this->getHttpRequest()->isMethod('GET')) {
            return;
        }

        $this->sendNotAllowedMethodResponse();
    }

    public function assertDELETE(): void
    {
        if ($this->getHttpRequest()->isMethod('DELETE')) {
            return;
        }

        $this->sendNotAllowedMethodResponse();
    }

    protected function beforeRender(): void
    {
        parent::beforeRender();

        $this->getHttpResponse()->setHeader('Content-Type', 'application/json');
        $this->terminate();
    }

    /** @param array<mixed>|null $data */
    protected function sendSuccessResponse(?array $data = null): void
    {
        $this->getHttpResponse()->setCode(IResponse::S200_OK);
        $this->sendJson($data ?? ['status' => 'ok']);
    }

    protected function getRequestBody(): RequestBody
    {
        return RequestBody::fromHttpRequest($this->getHttpRequest());
    }

    private function sendNotAllowedMethodResponse(): void
    {
        $this->getHttpResponse()->setCode(IResponse::S405_MethodNotAllowed);
        $this->sendJson([
            'message' => 'Method not allowed',
            'status' => 'error',
        ]);
    }

}