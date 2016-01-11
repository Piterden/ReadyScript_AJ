<?php
namespace DaData\Model;

class Api{
    const
        API_IP_ADRESS_URL = 'https://dadata.ru/api/v2/detectAddressByIp';
    
    /**
    * Делает запрос к dadata
    * 
    * @param string $ip - IP адрес для запроса
    * @param string $token - ключ API DaData 
    */
    function requestCityByIp($ip, $token)
    {
        $data = http_build_query(array(
            'ip' => $ip
        ));
        // Create a stream
        $opts = array(
          'http'=>array(
            'method'=>"GET",
            'header'=>"Accept: application/json\r\n" .
                      "Authorization: Token {$token}\r\n"
          )
        );

        $context = stream_context_create($opts);

        // Open the file using the HTTP headers set above
        $result = file_get_contents(self::API_IP_ADRESS_URL."?".$data, false, $context); 
        if ($result){
            return json_decode($result, true);
        }
        return false;
    }
} 

