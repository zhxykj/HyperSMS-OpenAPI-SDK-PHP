<?php

namespace HyperSMS;

class CallbackHelper
{
    public static function response_ok()
    {
        header('Content-Type:application/json; charset=utf-8');
        $arr = array('code' => 0, 'msg' => 'api.response.code.success', 'msgArgs' => null);
        exit(json_encode($arr));
    }

    private static function filerNull(&$arrData)
    {
        foreach ($arrData as $k => &$v) {
            if ($v === null) {
                unset($arrData[$k]);
            } else if (is_array($v)) {
                CallbackHelper::filerNull($v);
            }
        }
    }

    /**
     * calc sign
     * @param $param array params
     * @param  $app_key string app key
     */
    private static function calc_sign($param, $app_key)
    {
        $param = json_decode(json_encode($param, JSON_UNESCAPED_SLASHES), true);
        CallbackHelper::filerNull($param);
        unset($param['sign']);

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

        $signRaw .= $app_key;
        if (strpos($signRaw, '&') === 0) {
            $signRaw = substr($signRaw, 1);
        }

        return md5($signRaw);
    }

    /**
     * check post info valid
     * @param $data array
     * @param $app_key
     * @return bool
     */
    public static function info_check($data, $app_key)
    {
        $originSign = $data['sign'];
        $originTimestamp = $data['timestamp'];
        $checkSign = CallbackHelper::calc_sign($data, $app_key);
        return $checkSign == $originSign;
    }
}
