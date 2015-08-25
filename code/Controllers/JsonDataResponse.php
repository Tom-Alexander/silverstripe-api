<?php

class JsonDataResponse extends SS_HTTPResponse
{

    /**
     * HTTP Headers like "Content-Type: application/json"
     *
     * @see http://en.wikipedia.org/wiki/List_of_HTTP_headers
     * @var array
     */
    protected $headers = array(
        "Content-Type" => "application/json; charset=utf-8",
    );

}

class JsonDataResponse_Exception extends SS_HTTPResponse_Exception
{

    public function setResponse(SS_HTTPResponse $response) {

        $body = json_encode(array(
            'data' => array(
                'status_code' => $response->getStatusCode(),
                'message' => $response->getBody()
            )
        ));

        $this->response = new JsonDataResponse(
            $body,
            $response->getStatusCode(),
            $response->getStatusDescription()
        );
    }
}