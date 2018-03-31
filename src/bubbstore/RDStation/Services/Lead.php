<?php

namespace bubbstore\RDStation\Services;

use bubbstore\RDStation\Contracts\LeadInterface;
use bubbstore\RDStation\Exceptions\RDException;
use bubbstore\RDStation\RD;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;

class Lead implements LeadInterface
{

    /**
     * Cliente HTTP
     *
     * @var \GuzzleHttp\ClientInterface
     */
    protected $http;

    /**
     * RD
     *
     * @var \bubbstore\RDStation\RD
     */
    protected $rd;

    /**
     * Parâmetros do lead
     *
     * @var array
     */
    protected $params;

    /**
     * Response da chamada da API
     *
     * @var array
     */
    protected $response;

    /**
     * Endpoint de conversão
     *
     * @var string
     */
    protected $endpoint = 'https://www.rdstation.com.br/api/1.3/conversions';

    public function __construct(ClientInterface $http, RD $rd)
    {
        $this->http = $http;
        $this->rd = $rd;
    }

    /**
     * create
     *
     * Cria um novo lead.
     *
     * @param array $params
     * @return array
     */
    public function create(array $params)
    {
        $this->setParams($params)->sendApiRequest();

        return $this->fetchResponse();
    }

    /**
     * fetchResponse
     *
     * Modifica o payload de retorno.
     *
     * @return array
     */
    protected function fetchResponse()
    {
        unset($this->params['token_rdstation']);
        return [
            'result' => $this->response['result'],
            'lead' => $this->params,
        ];
    }

    /**
     * sendApiRequest
     *
     * @return void
     */
    protected function sendApiRequest()
    {
        try {
            $request = $this->http->post($this->endpoint, [
                'json' => $this->params
            ]);
            
            $this->response = json_decode($request->getBody()->getContents(), true);
        } catch (RequestException $e) {
            throw new RDException($e->getMessage());
        }
    }

    /**
     * setParams
     *
     * @param array $value
     * @return self
     */
    protected function setParams($value)
    {

        if (!isset($value['email'])) {
            throw new RDException('O e-mail do lead é obrigatório.');
        }

        $this->params = $value + ['token_rdstation' => $this->rd->getToken()];
        return $this;
    }
}
