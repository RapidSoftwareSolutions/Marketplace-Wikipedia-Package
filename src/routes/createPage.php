<?php

$app->post('/api/Wikipedia/createPage', function ($request, $response) {

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['username','password','title','pageText']);

    if(!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback']=='error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $post_data = $validateRes;
    }

    $requiredParams = ['username'=>'username','password'=>'password','title'=>'title','pageText'=>'text'];
    $optionalParams = ['section'=>'section','sectionTitle'=>'sectiontitle','summary'=>'summary','tags'=>'tags','baseTimestamp'=>'basetimestamp'];
    $bodyParams = [
       'form_params' => ['tags','summary','text','sectiontitle','section','title','basetimestamp']
    ];

    $data = \Models\Params::createParams($requiredParams, $optionalParams, $post_data['args']);

    if(!empty($data['basetimestamp']))
    {
        $data['basetimestamp'] = \Models\Params::toFormat($data['basetimestamp'], 'Y-m-d\TH:i:s\Z');
    }

    $client = new \GuzzleHttp\Client(['cookies' => true]);
    $query_str = "https://en.wikipedia.org/w/api.php";

    

    $requestParams = \Models\Params::createRequestBody($data, $bodyParams);
    $requestParams['headers'] = [];
    $requestParams['form_params']['action'] = 'edit';
    $requestParams['form_params']['format'] = 'json';

    $authData = \WikiAuth\WikiAuth::auth($data['username'],$data['password']);

    if(!$authData)
    {
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'API_ERROR';
        $result['contextWrites']['to']['status_msg'] = 'Wrong auth credentials or you have used the limit of queries.Try again after 5 min.';
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);

    }

    $requestParams['form_params']['token'] = $authData['token'];



    $cookieJar = \GuzzleHttp\Cookie\CookieJar::fromArray(
        $authData['cookie']
    , 'en.wikipedia.org');
    $requestParams['cookies'] = $cookieJar;

    try {
        $resp = $client->post($query_str, $requestParams);
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