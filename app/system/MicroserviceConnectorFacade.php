<?php

class MicroserviceConnectorFacade
{
    const FORM = 'FORM';
    const JSON = 'JSON';
    const FILE = 'FILE';

    static public function connect($service, $data, $requestDataType = 'FORM')
    {
        $connector = new MicroserviceRequestType();

        switch ($requestDataType) {
            case self::JSON:
                $response = $connector->requestJson($service, $data);
                break;
            case self::FILE:
                $response = $connector->requestFile($service, $data);
                break;
            default:
                $response = $connector->requestForm($service, $data);
        }

        return $response;
    }
}