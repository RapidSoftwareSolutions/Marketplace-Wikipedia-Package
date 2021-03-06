<?php

$app->post('/api/Wikipedia/getWatchList', function ($request, $response) {

    $settings = $this->settings;
    $checkRequest = $this->validation;
    $validateRes = $checkRequest->validate($request, ['username','password']);

    if(!empty($validateRes) && isset($validateRes['callback']) && $validateRes['callback']=='error') {
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($validateRes);
    } else {
        $post_data = $validateRes;
    }

    $requiredParams = ['username'=>'username','password'=>'password'];
    $optionalParams = ['end'=>'wlend','wlToken'=>'wltoken','wlOwner'=>'wlowner','start'=>'wlstart','includeMultipleRevisions'=>'wlallrev','wlProp'=>'wlprop','wlTypes'=>'wltype','wlLimit'=>'wllimit','customParams'=>'customParams','wlNamespaces'=>'wlnamespace'];
    $bodyParams = [
       'query' => ['wlnamespace','wlowner','wltoken','wllimit','wltype','wlprop','wlallrev','wlstart','wlend']
    ];

    $data = \Models\Params::createParams($requiredParams, $optionalParams, $post_data['args']);

    if(!empty($data['wlend']))
    {
        $data['wlend'] = \Models\Params::toFormat($data['wlend'], 'Y-m-d\TH:i:s\Z');

    }
    if(!empty($data['wltype']))
    {
        $data['wltype'] = \Models\Params::toString($data['wltype'], '|');

    }
    if(!empty($data['wlprop']))
    {
        $data['wlprop'] = \Models\Params::toString($data['wlprop'], '|');

    }
    if(!empty($data['wlstart']))
    {
        $data['wlstart'] = \Models\Params::toFormat($data['wlstart'], 'Y-m-d\TH:i:s\Z');

    }

    $client = new \GuzzleHttp\Client(['cookies' => true]);
    $query_str = "https://en.wikipedia.org/w/api.php";



    $requestParams = \Models\Params::createRequestBody($data, $bodyParams);
    $requestParams['headers'] = [];
    $requestParams['query']['action'] = 'query';
    $requestParams['query']['format'] = 'json';
    $requestParams['query']['list'] = 'watchlist';

    if(!empty($data['customParams']))
    {
        foreach($data['customParams'] as $key => $value)
        {
            $requestParams['query'][$value['key']] = $value['value'];
        }
    }


    $authData = \WikiAuth\WikiAuth::auth($data['username'],$data['password']);
    if(!$authData)
    {
        $result['callback'] = 'error';
        $result['contextWrites']['to']['status_code'] = 'API_ERROR';
        $result['contextWrites']['to']['status_msg'] = 'Wrong auth credentials or you have used the limit of queries.Try again after 5 min.';
        return $response->withHeader('Content-type', 'application/json')->withStatus(200)->withJson($result);

    }
    //$requestParams['query']['token'] = $authData['token'];



    $cookieJar = \GuzzleHttp\Cookie\CookieJar::fromArray(
        $authData['cookie']
        , 'en.wikipedia.org');
    $requestParams['cookies'] = $cookieJar;

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