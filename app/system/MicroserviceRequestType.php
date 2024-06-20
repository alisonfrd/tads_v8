<?php

class MicroserviceRequestType
{
    public function requestForm($service, $data)
    {
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($data),
            ],
        ];

        $context = stream_context_create($options);

        $response = @file_get_contents($service, false, $context);

        if ($response === false) {
            throw new Exception("Error communicating with microservice: $service");
        }

        return json_decode($response);
    }

    public function requestJson($service, $data)
    {
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-type: application/json',
                'content' => json_encode($data),
            ],
        ];

        $context = stream_context_create($options);

        $response = @file_get_contents($service, false, $context);

        if ($response === false) {
            throw new Exception("Error communicating with microservice: $service");
        }

        return json_decode($response);
    }

    public function requestFile($service, $data)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $service);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($ch);

        if ($response === false) {
            throw new Exception("Error communicating with microservice: $service");
        }

        curl_close($ch);

        return json_decode($response);
    }
}