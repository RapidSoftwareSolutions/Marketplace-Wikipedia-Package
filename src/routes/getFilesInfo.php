<?php

$app->post('/api/Wikipedia/getFilesInfo', function ($request, $response) {

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['fileNames']);

    if(!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback']=='error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $post_data = $validateRes;
    }

    $requiredParams = [];
    $optionalParams = ['fileNames'=>'titles','informationToGet'=>'iiprop','informationLimit'=>'iilimit','startListingFrom'=>'iistart','stopListingAt'=>'iiend','urlWidth'=>'iiurlwidth','urlHeight'=>'iiurlheight','metadataLanguage'=>'iiextmetadatalanguage','badFileContextTitle'=>'iibadfilecontexttitle','customParams'=>'customParams'];
    $bodyParams = [
       'query' => ['iibadfilecontexttitle','badFileContextTitle','iiextmetadatalanguage','iiurlheight','iiurlwidth','iistart','iiend','iiprop','titles']
    ];

    $data = \Models\Params::createParams($requiredParams, $optionalParams, $post_data['args']);

    if(!empty($data['titles']))
    {
        $data['titles'] = \Models\Params::toString($data['titles'], '|');

    }
    if(!empty($data['iiprop']))
    {
        $data['iiprop'] = \Models\Params::toString($data['iiprop'], '|');

    }
    if(!empty($data['iistart']))
    {
        $data['iistart'] = \Models\Params::toFormat($data['iistart'], 'Y-m-d\TH:i:s\Z');

    }
    if(!empty($data['iiend']))
    {
        $data['iiend'] = \Models\Params::toFormat($data['iiend'], 'Y-m-d\TH:i:s\Z');
    }

    $client = $this->httpClient;
    $query_str = "https://en.wikipedia.org/w/api.php";

    

    $requestParams = \Models\Params::createRequestBody($data, $bodyParams);
    $requestParams['headers'] = [];
    $requestParams['query']['action'] = 'query';
    $requestParams['query']['format'] = 'json';
    $requestParams['query']['prop'] = 'imageinfo';
    //$requestParams['query']['prop'] = 'fileusage';

    if(!empty($data['customParams']))
    {
        foreach($data['customParams'] as $key => $value)
        {
            $requestParams['query'][$value['key']] = $value['value'];
        }
    }



    try {
        $resp = $client->get($query_str, $requestParams);
        $responseBody = $resp->getBody()->getContents();

        if(in_array($resp->getStatusCode(), ['200', '201', '202', '203', '204'])) {
            $result['callback'] = 'success';
            $result['contextWrites']['to'] = is_array($responseBody) ? $responseBody : json_decode($responseBody);
            if(empty($result['contextWrites']['to'])) {
                $result['contextWrites']['to']['status_msg'] = "Api return no results";
            }
        } else {
            $result['callback'] = 'error';
            $result['contextWrites']['to']['status_code'] = 'API_ERROR';
            $result['contextWrites']['to']['status_msg'] = json_decode($responseBody);
        }

    } catch (\GuzzleHttp\Exception\ClientException $exception) {

        $responseBody = $exception->getResponse()->getBody()->getContents();
        if(empty(json_decode($responseBody))) {
            $out = $responseBody;
        } else {
            $out = json_decode($responseBody);
        }
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'API_ERROR';
        $result['contextWrites']['to']['status_msg'] = $out;

    } catch (GuzzleHttp\Exception\ServerException $exception) {

        $responseBody = $exception->getResponse()->getBody()->getContents();
        if(empty(json_decode($responseBody))) {
            $out = $responseBody;
        } else {
            $out = json_decode($responseBody);
        }
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'API_ERROR';
        $result['contextWrites']['to']['status_msg'] = $out;

    } catch (GuzzleHttp\Exception\ConnectException $exception) {
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'INTERNAL_PACKAGE_ERROR';
        $result['contextWrites']['to']['status_msg'] = 'Something went wrong inside the package.';

    }

    return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);

});