<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

if (!function_exists('sendResponse')) {
    /**
     * Success response method.
     *
     * @return JsonResponse
     * @package esa\helper
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
     * @package esa\helper
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

if (!function_exists('stringJoin')) {
    /**
     * remove whitespace and join array by separator
     *
     * @param array $arr
     * @param string $separator default ' '
     * @return string
     * @package esa\helper
     */
    function stringJoin(array $arr, string $separator = ' '): string
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
     * @package esa\helper
     */
    function xmlToArray($xml): array
    {
        $encodedXml = mb_convert_encoding($xml, 'UTF-8', 'ISO-8859-1');
        $json = json_encode(simplexml_load_string($encodedXml));
        return json_decode($json, true);
    }
}

if (!function_exists('objectToArray')) {
    /**
     * Convert Object to Array
     * @param mixed $object
     * @return array
     * @package esa\helper
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
     * @package esa\helper
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
     * @package esa\helper
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

if (!function_exists('isAzureStorageEnabled')) {
    /**
     * Check if azure storage enabled or not
     *
     * @return bool
     * @package esa\helper
     */
    function isAzureStorageEnabled()
    {
        return Config::get('filesystems.disks.azure.key') != '' ? true : false;
    }
}

if (!function_exists('storeFile')) {
    /**
     * Store file to Remote storage if configured otherwise store to local storage.
     *
     * @param string $filePath
     * @param mixed $contents,
     * @return bool
     * @package esa\helper
     */
    function storeFile(string $filePath, $contents, string $disk = 'azure'): bool
    {
        if (isAzureStorageEnabled()) {
            return Storage::disk($disk)->put($filePath, $contents);
        }
        return Storage::put($filePath, $contents);
    }
}

if (!function_exists('downloadStorageFile')) {
    /**
     * Download storage file
     * @param string $path
     * @param string $fileName
     * @return mixed
     * @package esa\helper
     */
    function downloadStorageFile(string $path, string $fileName): BinaryFileResponse
    {
        if (isAzureStorageEnabled()) {
            return downloadRemoteFile($path, $fileName);
        }
        return Response::download($path, $fileName);
    }
}

if (!function_exists('downloadRemoteFile')) {
    /**
     * download remote file
     *
     * @param string $path
     * @param string $fileName
     * @param string $disk
     * @return BinaryFileResponse
     * @package esa\helper
     */
    function downloadRemoteFile(string $path, string $fileName = '', string $disk = 'azure')
    {
        $url = Storage::disk($disk)->url($path);

        $fileName ??= basename($path);
        $tempImage = tempnam(sys_get_temp_dir(), $fileName);
        copy($url, $tempImage);

        return Response::download($tempImage, $fileName);
    }
}
