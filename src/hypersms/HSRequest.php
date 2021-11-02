<?php

namespace HyperSMS;


class HSRequest
{
    /**
     * @var string 根Url
     */
    private $gateUrl;

    /**
     *
     * @var HSAccount 帐号
     */
    private $account;

    private $timeout = 30;


    public function __construct($gateUrl, $account)
    {
        $this->gateUrl = $gateUrl;
        $this->account = $account;
    }

    private function timestamp()
    {
        list($s1, $s2) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
    }


    private function filerNull (&$arrData){
        foreach ($arrData as $k => &$v){
            if($v === null){
                unset($arrData[$k]);
            }else if(is_array($v)){
                $this->filerNull($v);
            }
        }
    }

    /**
     * 预处理，加入 token
     * @param $param array 参数
     * @param  $with_token string|null 加入token
     */
    private function prepare($param, $with_token)
    {
        $param = json_decode(json_encode($param, JSON_UNESCAPED_SLASHES), true);
        $this->filerNull($param);

        if (!empty($with_token)) {
            ksort($param);
            $signRaw = '';
            foreach ($param as $key => $value) {
                if ($value !== null) {
                    if (is_array($value) || is_object($value)) {
                        $signRaw .= ('&' . $key . '=' . json_encode($value, JSON_UNESCAPED_SLASHES));
                    } else {
                        $signRaw .= ('&' . $key . '=' . $value);
                    }
                }
            }

            $signRaw .= $this->account->appKey;
            if (strpos($signRaw, '&') === 0) {
                $signRaw = substr($signRaw, 1);
            }

            printf("sign raw is %s \n", $signRaw);
            $param['sign'] = md5($signRaw);
        }
        return str_replace('\\\\/', '\/', json_encode($param, JSON_UNESCAPED_SLASHES));
    }

    /**
     *
     * @param  $url string 请求的地址
     * @param  $param array 参数
     * @param  $with_token string|null token
     * @throws HSException 请求构造出错的时候会返回error
     */
    public function post($url, $param, $with_token = null)
    {

        $now = $this->timestamp();
        $param['timestamp'] = $now;
        $data = $this->prepare($param, $with_token);

        $headers = [
            'appId: ' . $this->account->appId,
            'timestamp: ' . $now,
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ];

        if (!empty($with_token)) {
            array_push($headers, 'token: ' . $with_token);
        }

        printf("header data : %s, body data: %s \n", json_encode($headers), $data);

        $ch = null;
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->gateUrl . $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
            curl_setopt($ch, CURLOPT_USERAGENT, 'HyperSMS OpenAPI PHP Client/1.0');

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $result = curl_exec($ch);
            if ($result == false) throw new HSException(HSException::HTTP_REQUEST_ERROR, curl_error($ch));
        } catch (\Exception $e) {
            if ($ch) {
                curl_close($ch);
            }
            throw $e;
        }

        return json_decode($result, true);
    }
}