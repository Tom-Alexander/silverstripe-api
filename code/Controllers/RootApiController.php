<?php

class RootApiController extends Controller
{

    /**
     * Delegate model requests to the appropriate ApiController
     * in a similar way as ContentController
     *
     * @param SS_HTTPRequest $request
     * @param DataModel $model
     * @return mixed
     * @throws JsonDataResponse_Exception
     */
    public function handleRequest(SS_HTTPRequest $request, DataModel $model)
    {

        if($modelName = $request->param('URLSegment')) {
            $controllerName = $controller = $modelName . '_Controller';
            if(class_exists($modelName) && class_exists($controllerName)) {
                $controller = singleton($controllerName);
                if(is_subclass_of($controller, 'ApiController')) {

                    $dataRecord = singleton($modelName);

                    if($dataRecord && $modelName != 'Security' && !$dataRecord->canView()) {
                        throw new JsonDataResponse_Exception(
                            'The request is understood, but access is not allowed',
                            403
                        );
                    }

                    $instance = Injector::inst()->create($controllerName);
                    $instance->setDataRecord($dataRecord);
                    return $instance->handleRequest($request, $model);
                }
            }
        }

        throw new JsonDataResponse_Exception(
           'The URI requested is invalid or the resource requested does not exists',
            404
        );
   }
}