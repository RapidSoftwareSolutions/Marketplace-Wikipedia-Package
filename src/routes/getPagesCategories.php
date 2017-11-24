<?php

$app->post('/api/Wikipedia/getPagesCategories', function ($request, $response) {

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['pageIds']);

    if(!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback']=='error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $post_data = $validateRes;
    }

    $requiredParams = ['pageIds'=>'pageids'];
    $optionalParams = ['additionalLists'=>'list','export'=>'export','indexPageIds'=>'indexpageids','iwUrl'=>'iwurl','rawContinue'=>'rawcontinue','categoriesLimit'=>'cllimit','clShow'=>'clshow','categoryProp'=>'clprop','customParams'=>'customParams'];
    $bodyParams = [
       'query' => ['rawcontinue','iwurl','indexpageids','export','list','pageids','iwurl','rawcontinue','cllimit','clshow','clprop']
    ];

    $data = \Models\Params::createParams($requiredParams, $optionalParams, $post_data['args']);

    if(!empty($data['pageids']))
    {
        $data['pageids'] = \Models\Params::toString($data['pageids'], '|');

    }
    if(!empty($data['list']))
    {
        $data['list'] = \Models\Params::toString($data['list'], '|');

    }
    if(!empty($data['clshow']))
    {
        $data['clshow'] = \Models\Params::toString($data['clshow'], '|');

    }
    if(!empty($data['clprop']))
    {
        $data['clprop'] = \Models\Params::toString($data['clprop'], '|');

    }

    $client = $this->httpClient;
    $query_str = "https://en.wikipedia.org/w/api.php";


    $requestParams = \Models\Params::createRequestBody($data, $bodyParams);
    $requestParams['headers'] = [];
    $requestParams['query']['action'] = 'query';
    $requestParams['query']['prop'] = 'categories';
    $requestParams['query']['format'] = 'json';
     

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