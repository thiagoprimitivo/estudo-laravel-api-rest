<?php

namespace App\Exceptions\Traits;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait ApiException {

    /**
     * Trata as exceções da API
     *
     * @param $request
     * @param $e
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getJsonException($request, $e)
    {
        if($e instanceof ModelNotFoundException) {
            return $this->notFoundException();
        }

        if($e instanceof HttpException) {
            return $this->httpException($e);
        }

        if($e instanceof ValidationException) {
            return $this->validationException($e);
        }

        //Implementar depois a exceçãp para limite de acesso à api

        return $this->genericException();
    }

    /**
     * Retorna o erro 404
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function notFoundException()
    {
        return $this->getResponse(
            "Recurso não encontrado",
            "01",
            Response::HTTP_NOT_FOUND
        );
    }

    /**
     * Retorna o erro de HTTP
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function httpException($e)
    {
        $messages = [
            405 => [
                "code" => "03",
                "message" => "Verbo HTTP não permitido"
            ]
        ];

        return $this->getResponse(
            $messages[$e->getStatusCode()]["message"],
            $messages[$e->getStatusCode()]["code"],
            $e->getStatusCode()
        );
    }

    /**
     * Retorna o erro de validação
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function validationException($e)
    {
        return response()->json($e->errors(), $e->status);
    }

    /**
     * Retorna o erro 500
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function genericException()
    {
        return $this->getResponse(
            "Erro interno no servidor",
            "02",
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    /**
     * Monta a resposta em JSON
     *
     * @param $message
     * @param $code
     * @param $status
     * @return \Illuminate\Http\JsonResponse
     */
    protected function getResponse($message, $code, $status)
    {
        return response()->json([
            "errors" => [
                [
                    "status" => $status,
                    "code" => $code,
                    "message" => $message
                ]
            ]
        ], $status);
    }
}