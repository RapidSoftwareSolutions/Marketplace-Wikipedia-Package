<?php

$app->post('/api/Wikipedia/getRevisionByPageTitle', function ($request, $response) {

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['titles']);

    if(!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback']=='error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $post_data = $validateRes;
    }

    $requiredParams = ['titles'=>'titles'];
    $optionalParams = ['rvTag'=>'rvtag','revisionProperty'=>'rvprop','rvuser'=>'rvuser','rvsection'=>'rvsection','rvExcludeUser'=>'rvexcludeuser','rvlimit'=>'rvlimit','rvEnd'=>'rvend','rvStart'=>'rvstart','customParams'=>'customParams'];
    $bodyParams = [
       'query' => ['target','pageids','rvprop','rvtag','rvuser','rvsection','rvexcludeuser','rvlimit','rvend','rvstart']
    ];

    $data = \Models\Params::createParams($requiredParams, $optionalParams, $post_data['args']);

    
    if(!empty($data['titles']))
    {
        $data['titles'] = \Models\Params::toString($data['titles'], '|');

    }
    if(!empty($data['rvstart']))
    {
        $data['rvstart'] = \Models\Params::toFormat($data['rvstart'], 'Y-m-d\TH:i:s\Z');

    }
    if(!empty($data['rvend']))
    {
        $data['rvend'] = \Models\Params::toFormat($data['rvend'], 'Y-m-d\TH:i:s\Z');

    }
    if(!empty($data['rvprop']))
    {
        $data['rvprop'] = \Models\Params::toString($data['rvprop'], '|');

    }

    $client = $this->httpClient;
    $query_str = "https://en.wikipedia.org/w/api.php?action=query&prop=revision&";



    $requestParams = \Models\Params::createRequestBody($data, $bodyParams);
    $requestParams['headers'] = [];
    $requestParams['query']['action'] = 'query';
    $requestParams['query']['prop'] = 'revisions';
    $requestParams['query']['format'] = 'json';

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