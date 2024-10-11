<?php

use SimpleXMLElement;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

if (!function_exists('sendResponse')) {
    /**
     * Success response method.
     *
     * @return JsonResponse
     */
    function sendResponse($result, $message = '', $success = true): JsonResponse
    {
        $response = [
            'success' => $success,
            'data' => $result,
            'message' => $message,
        ];

        return Response::json($response, 200);
    }
}

if (!function_exists('sendError')) {
    /**
     * Return error response.
     *
     * @return JsonResponse
     */
    function sendError($errors, $errorMessages = [], $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $errors,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return Response::json($response, $code);
    }
}

if (!function_exists('string_join')) {
    /**
     * remove whitespace and join array by separator
     *
     * @param array $arr
     * @param string $separator default ' '
     * @return string
     */
    function string_join(array $arr, string $separator = ' '): string
    {
        $arr = array_filter(array_map('trim', $arr));
        return join($separator, $arr);
    }
}

if (!function_exists('xmlToArray')) {
    /**
     * convert XML to Array
     * @param mixed $xml
     * @return array
     */
    function xmlToArray($xml): array
    {
        $json = json_encode(simplexml_load_string($xml));
        return json_decode($json, true);
    }
}

if (!function_exists('objectToArray')) {
    /**
     * Convert Object to Array
     * @param mixed $object
     * @return array
     */
    function objectToArray($object): array
    {
        return json_decode(json_encode($object), TRUE);
    }
}

if (!function_exists('getIP')) {
    /**
     * Return IP Address
     * @return mixed
     */
    function getIP(): mixed
    {
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = null;

        return $ipaddress;
    }
}

if (!function_exists('arrayToXml')) {
    /**
     * Convert Array into XML
     * @param array $arr
     * @param SimpleXMLElement $xml
     * @return bool|string
     */
    function arrayToXml(array $arr, SimpleXMLElement $xml)
    {
        foreach ($arr as $k => $v) {

            $attrArr = array();
            $kArray = explode(' ', $k);
            $tag = array_shift($kArray);

            if (count($kArray) > 0) {
                foreach ($kArray as $attrValue) {
                    $attrArr[] = explode('=', $attrValue);
                }
            }

            if (is_array($v)) {
                if (is_numeric($k)) {
                    arrayToXml($v, $xml);
                } else {
                    $child = $xml->addChild($tag);
                    if (!empty($attrArr)) {
                        foreach ($attrArr as $attrArrV) {
                            $child->addAttribute($attrArrV[0], $attrArrV[1]);
                        }
                    }
                    arrayToXml($v, $child);
                }
            } else {
                $child = $xml->addChild($tag, $v);
                if (!empty($attrArr)) {
                    foreach ($attrArr as $attrArrV) {
                        $child->addAttribute($attrArrV[0], $attrArrV[1]);
                    }
                }
            }
        }

        return $xml->asXML();
    }
}
