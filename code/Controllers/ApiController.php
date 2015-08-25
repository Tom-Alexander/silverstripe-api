<?php

class ApiController extends Controller
{

    protected $dataRecord;

    /**
     * Delegate the request to the correct method and
     * wrap the response (including exceptions) in a
     * JsonDataResponse
     * @param SS_HTTPRequest $request
     * @param DataModel $model
     * @return string|JsonDataResponse|SS_HTTPResponse|null
     * @throws JsonDataResponse
     * @throws SS_HTTPResponse_Exception
     */
    public function handleRequest(SS_HTTPRequest $request, DataModel $model)
    {
        $body = null;
        $verb = strtolower($request->httpMethod());
        $availableVerbs =  $this->config()->get('allowed_verbs');
        $id = $request->param('ID');

        $resourceType = 'List';
        if($id && is_numeric($id)) $resourceType = 'Data';
        $methodName = $verb . $resourceType;

        try {
            if(in_array(strtoupper($verb), $availableVerbs)
                && $this->hasMethod($methodName)) {
                $body = $this->{$methodName}($request);
            }
        } catch(SS_HTTPResponse_Exception $exception) {
            $response = $exception->getResponse();
            throw new JsonDataResponse_Exception(
                $response->getBody(),
                $response->getStatusCode(),
                $response->getStatusDescription()
            );
        }

        if($body instanceof JsonDataResponse) {
            return $body;
        }

        if($body instanceof SS_HTTPResponse) {
            return new JsonDataResponse(
                $body->getBody(),
                $body->getStatusCode(),
                $body->getStatusDescription()
            );
        }

        if(is_string($body)) {
            return new JsonDataResponse($body);
        }

        throw new JsonDataResponse_Exception(
            "The URI requested has not been implement",
            404
        );

    }

    /**
     * The default implementation of the controller
     * is to call the serializeData method on its model.
     * JsonDataResponse, SS_HTTPResponse or a string
     * @param SS_HTTPRequest $request
     * @return string|JsonDataResponse|SS_HTTPResponse
     */
    public function getData(SS_HTTPRequest $request)
    {
        $id = (int) $request->param('ID');
        $record = $this->getDataRecord();
        if($record->hasMethod('getSerializedData')) {
            return $record->getSerializedData($id, $request->getVars())
                ->toJson();
        }
    }

    /**
     * The default implementation of the controller
     * is to call the serializeList method on its model.
     * @param SS_HTTPRequest $request
     * @return string|JsonDataResponse|SS_HTTPResponse
     */
    public function getList(SS_HTTPRequest $request)
    {
        $record = $this->getDataRecord();
        if($record->hasMethod('getSerializedList')) {
            return $record->getSerializedList($request->getVars())
                ->toJson();
        }
    }

    public function setDataRecord($dataRecord)
    {
        $this->dataRecord = $dataRecord;
    }

    public function getDataRecord()
    {
        return $this->dataRecord;
    }

}