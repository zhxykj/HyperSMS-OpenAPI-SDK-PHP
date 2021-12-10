<?php

namespace HyperSMS;

class HSSDK
{

    /****  PRODUCT TYPE   *****/
    const PRODUCT_TYPE_HYPER_SMS = 1;
    const PRODUCT_TYPE_SMS = 2;

    /****  PRODUCT ITEM   *****/
    const PRODUCT_ITEM_SMS_AD_GROUP_1_EDU = 'SMS_AD_GROUP_1_EDU';
    const PRODUCT_ITEM_SMS_AD_GROUP_2_BDS = 'SMS_AD_GROUP_2_BDS';
    const PRODUCT_ITEM_SMS_AD_GROUP_3_COS = 'SMS_AD_GROUP_3_COS';
    const PRODUCT_ITEM_SMS_AD_GROUP_3_GTRI = 'SMS_AD_GROUP_3_GTRI';
    const PRODUCT_ITEM_SMS_AD_GROUP_3_FSION = 'SMS_AD_GROUP_3_FSION';
    const PRODUCT_ITEM_SMS_AD_GROUP_3_FOOD = 'SMS_AD_GROUP_3_FOOD';
    const PRODUCT_ITEM_SMS_AD_GROUP_3_MART = 'SMS_AD_GROUP_3_MART';
    const PRODUCT_ITEM_SMS_AD_GROUP_3_TRAN = 'SMS_AD_GROUP_3_TRAN';
    const PRODUCT_ITEM_SMS_AD_GROUP_3_FIN = 'SMS_AD_GROUP_3_FIN';
    const PRODUCT_ITEM_SMS_AD_GROUP_3_TMDT = 'SMS_AD_GROUP_3_TMDT';
    const PRODUCT_ITEM_SMS_SMS_AD_GROUP_3_DL = 'SMS_AD_GROUP_3_DL';
    const PRODUCT_ITEM_SMS_AD_GROUP_3_ELEC = 'SMS_AD_GROUP_3_ELEC';
    const PRODUCT_ITEM_SMS_AD_GROUP_4_OTHER = 'SMS_AD_GROUP_4_OTHER';
    const PRODUCT_ITEM_SMS_CSKH_GROUP_1_FIN = 'SMS_CSKH_GROUP_1_FIN';
    const PRODUCT_ITEM_SMS_CSKH_GROUP_2_2_HCHINH = 'SMS_CSKH_GROUP_2.2_HCHINH';
    const PRODUCT_ITEM_SMS_CSKH_GROUP_KCN_INDUS = 'SMS_CSKH_GROUP_KCN_INDUS';
    const PRODUCT_ITEM_SMS_CSKH_GROUP_3_3_OTTIN = 'SMS_CSKH_GROUP_3.3_OTTIN';
    const PRODUCT_ITEM_SMS_CSKH_GROUP_3_4_OTTL = 'SMS_CSKH_GROUP_3.4_OTTL';
    const PRODUCT_ITEM_SMS_CSKH_GROUP_4_EWP = 'SMS_CSKH_GROUP_4_EWP';
    const PRODUCT_ITEM_SMS_CSKH_GROUP_5_TMDT = 'SMS_CSKH_GROUP_5_TMDT';
    const PRODUCT_ITEM_SMS_CSKH_GROUP_6_INVOICE = 'SMS_CSKH_GROUP_6_INVOICE';
    const PRODUCT_ITEM_SMS_CSKH_GROUP_2_1_EDU = 'SMS_CSKH_GROUP_2.1_EDU';
    const PRODUCT_ITEM_SMS_CSKH_GROUP_1_BHIEM = 'SMS_CSKH_GROUP_1_BHIEM';
    const PRODUCT_ITEM_SMS_CSKH_GROUP_2_1_YTE = 'SMS_CSKH_GROUP_2.1_YTE';

    /*****  Description of data status code ******/
    const DATA_STATUS_CODE_INVALID = 0;
    const DATA_STATUS_CODE_VALID = 1;


    /*****  Approve status code description ******/
    const APPROVE_STATUS_CODE_REJECT = 0;
    const APPROVE_STATUS_CODE_PASS = 1;
    const APPROVE_STATUS_CODE_WAITING = 2;

    /**
     * @var HSRequest
     */
    public $request;

    /**
     * @var string server token, Generated after calling the login API
     */
    private $token;

    /**
     * @param $gatewayUrl string Unified access entrance; eg: https://openapi.viettel.hypersms.vn/v2/
     * @param $account HSAccount account entity
     * @param $token string server token, Generated after calling the login API
     */
    public function __construct($gatewayUrl, $account, $token = null)
    {
        $this->request = new HSRequest($gatewayUrl, $account);
        $this->token = $token;
    }

    /**
     * Login
     *
     *  The access party obtains the global token through the login API
     *
     * @param $appSecret string Access party password
     * @throws HSException
     */
    public function login($appId, $appSecret)
    {
        $result = $this->request->post('app/login', array(
            "appId" => $appId,
            "appSecret" => $appSecret
        ));

        //var_dump($result);
        if ($result['code'] === 0) {
            $this->token = $result['data']['token'];
            return $this->token;
        } else {
            throw new HSException(HSException::SDK_LOGIN_ERROR, 'login error');
        }
    }


    /**
     * check login status
     * @throws HSException
     */
    private function check_login()
    {
        if (empty($this->token)) {
            throw new HSException('SDK_ACCESS_ERROR', 'account not login, or login status has expired');
        }
    }

    /**
     *
     * Paging query product
     *
     * @param numeric|null $product_type Product Type
     * @param string|null $code Brand code
     * @param int $curr_page Current page number, default value is 1
     * @param int $page_size The number of items per page, the default value is 10
     * @return mixed
     * @throws HSException
     */
    public function query_product($product_type = null, $code = null, $curr_page = 1, $page_size = 10)
    {
        $this->check_login();
        return $this->request->post('app/product/item/query', array(
            "type" => $product_type,
            "code" => $code,
            "currPage" => $curr_page,
            "pageSize" => $page_size
        ), $this->token);
    }

    /**
     * New brand
     *
     * New brand information
     *
     * @param $brandname string Brand name
     * @param string $product_item_code Product Item Code, please refer to the query product interface response
     * @param numeric $product_type Product Type, 1 HyperSMS 2 SMS,default
     * @param $description string Description
     * @param $certified_url string Certificate URL
     * @return mixed
     * @throws HSException
     */
    public function add_brandname($brandname, $product_item_code, $product_type = 2, $description = null, $certified_url = null)
    {
        $this->check_login();
        return $this->request->post('app/brandname/add', array(
            "brandName" => $brandname,
            "type" => $product_type,
            "productItemCode" => $product_item_code,
            "description" => $description,
            "certified_url" => $certified_url
        ), $this->token);
    }


    /**
     *
     * Paging query brand
     *
     * @param string|null $brandname Brand name
     * @param string|null $code Brand code
     * @param numeric $product_type Product Type, 1 HyperSMS 2 SMS,default
     * @param int $curr_page Current page number, default value is 1
     * @param int $page_size The number of items per page, the default value is 10
     * @return mixed
     * @throws HSException
     */
    public function query_brandname($brandname = null, $code = null,  $product_type = 2, $curr_page = 1, $page_size = 10)
    {
        $this->check_login();
        return $this->request->post('app/brandname/query', array(
            "brandName" => $brandname,
            "code" => $code,
            "type" => $product_type,
            "currPage" => $curr_page,
            "pageSize" => $page_size
        ), $this->token);
    }

    /**
     * Query Brand Details
     * @param $brandname_code string Brand code
     * @return mixed
     * @throws HSException
     */
    public function info_brandname($brandname_code)
    {
        $this->check_login();
        return $this->request->post('app/brandname/info', array(
            "code" => $brandname_code
        ), $this->token);
    }


    /**
     *  Brand invalidation
     *
     *  Collocation of brand data offline is invalid
     *
     * @param $brandname_code string Brand code
     * @return mixed
     * @throws HSException
     */
    public function invalid_brandname($brandname_code)
    {
        $this->check_login();
        return $this->request->post('app/brandname/invalid', array(
            "code" => $brandname_code
        ), $this->token);
    }


    /**
     * New template
     *
     *  New template information
     *
     * @param $product_type int  Product type; see "Appendix B" for details
     * @param $brandname_code string Brand code
     * @param $product_item_code  string The belonging product item; see "Appendix C" for details
     * @param $title string Template title
     * @param $content string Template content (support up to 5 dynamic parameter {0} format)
     * @return mixed
     * @throws HSException
     */
    public function add_template($product_type, $brandname_code, $product_item_code, $title, $content)
    {
        $this->check_login();
        return $this->request->post('app/template/add', array(
            "type" => $product_type,
            "brandNameCode" => $brandname_code,
            "productItemCode" => $product_item_code,
            "title" => $title,
            "content" => $content,
        ), $this->token);
    }


    /**
     *  Edit template
     *
     *  Edit template information
     *
     * @param $template_code string Template code
     * @param $product_type numeric Product type; see "Appendix B" for details
     * @param $brandname_code string Brand code
     * @param $product_item_code string The belonging product item; see "Appendix C" for details
     * @param $title string Template title
     * @param $content string Template content (support up to 5 dynamic parameters {index} format)
     * @return mixed
     * @throws HSException
     */
    public function edit_template($template_code, $product_type, $brandname_code, $product_item_code, $title, $content)
    {
        $this->check_login();
        return $this->request->post('app/template/edit', array(
            "code" => $template_code,
            "type" => $product_type,
            "brandNameCode" => $brandname_code,
            "productItemCode" => $product_item_code,
            "title" => $title,
            "content" => $content,
        ), $this->token);
    }


    /**
     *  Paging query template
     *
     *  Query template data according to specified conditions, support paging
     *
     * @param string|null $template_code Template code
     * @param numeric|null $product_type Product type; see "Appendix B" for details
     * @param string|null $brandname_code Brand code
     * @param string|null $product_item_code The belonging product item; see "Appendix C" for details
     * @param string|null $title Template title (support fuzzy check)
     * @param int $curr_page Current page number, default value is 1
     * @param int $page_size The number of items per page, the default value is 10
     * @return mixed
     * @throws HSException
     */
    public function query_template($template_code = null,
                                   $product_type = null,
                                   $brandname_code = null,
                                   $product_item_code = null,
                                   $title = null,
                                   $curr_page = 1,
                                   $page_size = 10)
    {
        $this->check_login();
        return $this->request->post('app/template/query', array(
            "code" => $template_code,
            "type" => $product_type,
            "brandNameCode" => $brandname_code,
            "productItemCode" => $product_item_code,
            "title" => $title,
            "currPage" => $curr_page,
            "pageSize" => $page_size,
        ), $this->token);
    }

    /**
     * Template Detail
     *
     *  Query template details according to the template code, including review information
     *
     * @param $template_code string  Template code
     * @return mixed
     * @throws HSException
     */
    public function info_template($template_code)
    {
        $this->check_login();
        return $this->request->post('app/template/info', array(
            "code" => $template_code
        ), $this->token);
    }


    /**
     *  Template invalidation
     * @param $template_code string Template code
     * @return mixed
     * @throws HSException
     */
    public function invalid_template($template_code)
    {
        $this->check_login();
        return $this->request->post('app/template/invalid', array(
            "code" => $template_code
        ), $this->token);
    }


    /**
     * Create campaign
     *
     * Create campaign task information and sending rules
     *
     * @param $name string  Campaign name
     * @param $product_type numeric  Product type; see "Appendix B" for details
     * @param $sms_template_code string SMS template code
     * @param $property HSCampaignProperty Campaign property
     * @param $description string|null  Campaign description
     * @param $template_code string|null  HyperSMS template code [V2.0 version not currently supported]
     * @throws HSException
     */
    public function add_campaign($name, $product_type, $sms_template_code, $property, $description = null, $template_code = null)
    {
        $this->check_login();
        return $this->request->post('app/template/invalid', array(
            "type" => $product_type,
            "smsTemplateCode" => $sms_template_code,
            "name" => $name,
            "templateCode" => $template_code,
            "description" => $description,
            "property" => $property
        ), $this->token);
    }


    /**
     * Edit campaign
     *
     *  Edit campaign tasks and activity rules
     *
     * @param $campaign_code string  Campaign code
     * @param $name string  Campaign name
     * @param $product_type numeric    Product type; see "Appendix B" for details
     * @param $sms_template_code string    SMS template code
     * @param $property HSCampaignProperty Campaign description
     * @param $description string|null  Campaign description
     * @throws HSException
     */
    public function edit_campaign($campaign_code, $name, $product_type, $sms_template_code, $property, $description = null, $template_code = null)
    {
        $this->check_login();
        return $this->request->post('app/campaign/edit', array(
            "code" => $campaign_code,
            "type" => $product_type,
            "smsTemplateCode" => $sms_template_code,
            "name" => $name,
            "templateCode" => $template_code,
            "description" => $description,
            "property" => $property
        ), $this->token);
    }


    /**
     * Paging query campaign
     *
     *  Query activity data according to specified conditions, support paging
     *
     * @param null|string $name Campaign name (support fuzzy search)
     * @param null|string $campaign_code Campaign code
     * @param null|numeric $product_type Product type; see "Appendix B" for details
     * @param null|string $sms_template_code SMS template code
     * @param null|string $template_code HyperSMS template code [V2.0 version not currently supported]
     * @param int $curr_page Current page number, default value is 1
     * @param int $page_size The number of items per page, the default value is 10
     * @return mixed
     * @throws HSException
     */
    public function query_campaign($name = null,
                                   $campaign_code = null,
                                   $product_type = null,
                                   $sms_template_code = null,
                                   $template_code = null,
                                   $curr_page = 1,
                                   $page_size = 10)
    {
        $this->check_login();
        return $this->request->post('app/campaign/query', array(
            "type" => $product_type,
            "code" => $campaign_code,
            "smsTemplateCode" => $sms_template_code,
            "name" => $name,
            "templateCode" => $template_code,
            "currPage" => $curr_page,
            "pageSize" => $page_size,
        ), $this->token);
    }


    /**
     * Campaign Details
     *
     * Query event details according to the campaign code, including basic event information, rule information,
     * sending target and approved information
     *
     * @param $campaign_code string Campaign code
     * @return mixed
     * @throws HSException
     */
    public function info_campaign($campaign_code)
    {
        $this->check_login();
        return $this->request->post('app/campaign/info', array(
            "code" => $campaign_code
        ), $this->token);
    }


    /**
     * Campaign invalidation
     *
     *  Invalidated Campaign (Note: Activities in progress cannot be invalidated)
     *
     * @param $campaign_code string Campaign code
     * @return mixed
     * @throws HSException
     */
    public function invalid_campaign($campaign_code)
    {
        $this->check_login();
        return $this->request->post('app/campaign/invalid', array(
            "code" => $campaign_code
        ), $this->token);
    }


    /**
     *  Cancel Campaign task
     *
     *  Cancel the active task in execution
     *
     * @param $campaign_code  string  Campaign code
     * @return mixed
     * @throws HSException
     */
    public function cancel_campaign($campaign_code)
    {
        $this->check_login();
        return $this->request->post('app/campaign/cancel', array(
            "code" => $campaign_code
        ), $this->token);
    }

    /**
     * Pause campaign task
     *
     *  Pause the Campaign task that is being executed
     *
     * @param $campaign_code  string  Campaign code
     * @return mixed
     * @throws HSException
     */
    public function pause_campaign($campaign_code)
    {
        $this->check_login();
        return $this->request->post('app/campaign/pause', array(
            "code" => $campaign_code
        ), $this->token);
    }

    /**
     * Resume campaign task
     *
     *   Resume campaign tasks that are being suspended
     *
     * @param $campaign_code  string  Campaign code
     * @return mixed
     * @throws HSException
     */
    public function resume_campaign($campaign_code)
    {
        $this->check_login();
        return $this->request->post('app/campaign/resume', array(
            "code" => $campaign_code
        ), $this->token);
    }


    /**
     * Sending
     *
     *  Send a single number
     *
     * @param $product_type numeric  Product type; see "Appendix B" for details
     * @param $sms_template_code string SMS template code
     * @param $sub_id string    Send number
     * @param $param array<string>|null Set of dynamic parameter values; \
     *      Note: The order should correspond to the dynamic parameter (index) in the template
     * @param $template_code string|null HyperSMS template code [V2.0 version not currently supported]
     * @param $encode_type numeric Optional (0: plaintext, 1: operator encryption) The default is 0
     * @throws HSException
     */
    public function send($product_type, $sms_template_code, $sub_id, $param = null, $template_code = null, $encode_type = 0)
    {
        $this->check_login();
        return $this->request->post('app/send', array(
            "type" => $product_type,
            "smsTemplateCode" => $sms_template_code,
            "subId" => $sub_id,
            "param" => $param,
            "templateCode" => $template_code,
            "encodeType" => $encode_type
        ), $this->token);
    }


    /**
     * Batch sending
     *
     *  Send multiple numbers (only 500 numbers are currently supported)
     *
     * @param $product_type numeric  Product type; see "Appendix B" for details
     * @param $sms_template_code string SMS template code
     * @param $sub_list array<HSBatchSendSub> Send multiple numbers
     * @param $template_code string|null HyperSMS template code [V2.0 version not currently supported]
     * @throws HSException
     */
    public function batch_send($product_type, $sms_template_code, $sub_list, $template_code = null)
    {
        $this->check_login();
        return $this->request->post('app/send/batch', array(
            "type" => $product_type,
            "smsTemplateCode" => $sms_template_code,
            "templateCode" => $template_code,
            "subList" => $sub_list
        ), $this->token);
    }

    /**
     * New material
     *
     *  New material
     *
     * @param $file string file path
     * @return mixed
     * @throws HSException
     */
    public function add_material($file)
    {
        $this->check_login();
        return $this->request->postFile('app/material/add', $file, $this->token);
    }

    /**
     *  Paging query material
     *
     *  Query material data according to specified conditions, support paging
     *
     * @param numeric $product_type Product type; default is sms 2, see "Appendix B" for details
     * @param string|null $material_code material code, refer to add material interface result
     * @param int $curr_page Current page number, default value is 1
     * @param int $page_size The number of items per page, the default value is 10
     * @return mixed
     * @throws HSException
     */
    public function query_material($product_type = 2,
                                   $material_code = null,
                                   $curr_page = 1,
                                   $page_size = 10)
    {
        $this->check_login();
        return $this->request->post('app/material/query', array(
            "code" => $material_code,
            "type" => $product_type,
            "currPage" => $curr_page,
            "pageSize" => $page_size,
        ), $this->token);
    }

    /**
     * Material Details
     *
     * Query event details according to the material code
     *
     * @param string $material_code material code, refer to query material interface result
     * @return mixed
     * @throws HSException
     */
    public function info_material($material_code)
    {
        $this->check_login();
        return $this->request->post('app/material/info', array(
            "code" => $material_code
        ), $this->token);
    }

    /**
     * Material invalidation
     *
     *  Invalidated Material
     *
     * @param string $material_code material code, refer to query material interface result
     * @return mixed
     * @throws HSException
     */
    public function invalid_material($material_code)
    {
        $this->check_login();
        return $this->request->post('app/material/invalid', array(
            "code" => $material_code
        ), $this->token);
    }
}