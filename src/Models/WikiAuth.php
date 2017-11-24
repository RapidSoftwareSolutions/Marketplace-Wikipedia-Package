<?php
namespace WikiAuth;


class WikiAuth
{
    public static function auth($login,$password)
    {
        $client = new \GuzzleHttp\Client(['cookies' => true]);

        $query_str = "https://en.wikipedia.org/w/api.php";
        $data = [
            'action' => 'query',
            'meta' => 'tokens',
            'type' => 'login',
            'format' => 'json'
        ];
        $firstStep['query'] = $data;



        $first = $client->post($query_str, $firstStep);
        $responseBody = $first->getBody()->getContents();
        $responseBody = json_decode($responseBody,true);

        if(empty($responseBody) || empty($responseBody['query']['tokens']['logintoken']))
        {
            return false;
        }


        $loginToken = $responseBody['query']['tokens']['logintoken'];


        //Get login token and auth cookies.
        $data = [
            'action' => 'login',
            'lgname' => $login,
            'lgpassword' => $password,
            'lgtoken' => $loginToken,
            'format' => 'json',
            'lgdomain' => null
        ];

        $secondStep['form_params'] = $data;
        $second = $client->post($query_str, $secondStep);
        $cookie = $client->getConfig('cookies');
        $cookie = $cookie->toArray();
        $responseBody = $second->getBody()->getContents();

        $responseBody = json_decode($responseBody,true);

        if(empty($responseBody) || empty($responseBody['login']['result']))
        {
            return false;
        }

        if(!empty($responseBody['login']['result']))
        {
            if($responseBody['login']['result'] !== 'Success')
            {
                return false;
            }
        }

        //Get csrf token with login token and auth cookie.
        $data = [
            'action' => 'query',
            'meta' => 'tokens',
            'format' => 'json'
        ];

        foreach($cookie as $key => $value)
        {
            $arr[$value['Name']] = $value['Value'];
        }

        $cookie = $arr;




        $cookieJar = \GuzzleHttp\Cookie\CookieJar::fromArray(
            $cookie
        , 'en.wikipedia.org');
        $data['cookies'] = $cookieJar;


        $thirdStep['form_params'] = $data;

        $third = $client->post($query_str, $thirdStep);
        $responseBody = $third->getBody()->getContents();
        $responseBody = json_decode($responseBody,true);

        if(empty($responseBody) || empty($responseBody['query']['tokens']['csrftoken']))
        {
            return false;
        }


        $cookie = $arr;
        $token = $responseBody['query']['tokens']['csrftoken'];
        $authData['token'] = $token;
        $authData['cookie'] = $cookie;



        return $authData;
    }
}
