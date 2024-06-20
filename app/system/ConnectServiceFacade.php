<?php

class ConnectServiceFacade
{

    static public function connect ($service, $data, $requestDataType = 'FORM')
    {
        switch ($requestDataType)
        {
            case 'JSON':
                return self::requestJSON($service, $data);
                break;
            case 'FORM':
                return self::requestForm($service, $data);
                break;
            case 'FILE':
                return self::requestFile($service, $data);
                break;
            default:
                return self::requestForm($service, $data);
        }
    }

    public static function requestForm ($service, $data)
    {
        $options = [ 
            'http' => [ 
                'method'  => 'POST', 
                'header'  => 'Content-type: application/x-www-form-urlencoded', 
                'content' => http_build_query($data), 
            ], 
        ]; 
          
        $context  = stream_context_create($options); 

        $response = json_decode(file_get_contents($service, false, $context)); 

        return $response;
    }

    public static function requestJSON($service, $data) {
     
        $options = [ 
            'http' => [ 
                'method'  => 'POST', 
                'header'  => 'Content-type: application/json',
                'content' =>  json_encode($data), 
            ], 
        ]; 
        
        $context  = stream_context_create($options); 

        $response = json_decode(file_get_contents($service, false, $context)); 
       
        return $response;
    }

    public static function requestFile($service, $data) {
       
         // Iniciar uma nova sessão cURL
         $ch = curl_init();

         // Configurar as opções do cURL
         curl_setopt($ch, CURLOPT_URL, 'http://localhost:5000/predict'); // URL do microserviço
         curl_setopt($ch, CURLOPT_POST, 1);

         // Configurar os dados do POST
         curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

         // Configurar a opção para retornar a resposta como uma string
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


         // Fechar a sessão cURL
         curl_close($ch);

        $response = json_decode(curl_exec($ch)); 

        return $response;
    }
}