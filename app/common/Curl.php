<?php


namespace App\common;


use Illuminate\Support\Facades\Session;

class Curl
{
    public static function send($url,$data)
    {
        $curl = curl_init();
        /*获取用户列表*/
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "X-Tableau-Auth: " . $data['token'],
                "Accept: application/json",
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return false;
        } else {
            if (!$response) {
                return false;
            }
            return json_decode($response);
        }
    }

    public static function getToken($data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => Session::get('tableau_domain') . "/api/3.2/auth/signin",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "{\"credentials\":{\"name\":\"" . $data['username'] . "\",\"password\":\"" . $data['password'] . "\",\"site\":{\"contentUrl\":\"".$data['contentUrl']."\"}}}",
            CURLOPT_HTTPHEADER => array(
                "User-Agent: TabCommunicate",
                "Content-Type: application/json",
                "Accept: application/json",
            ),
        ));
        $response = curl_exec($curl);
        if (!$response) {
            return view('admin.error.index');
        }
        $err = curl_error($curl);
        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return json_decode($response);
        }
    }

    public static function tj_post($remote_server, $post_string) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "123456");
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    public static function getWorkBooks($siteId = null)
    {
        /*拿到所有报表的数据*/
        $curlt = curl_init();
        if(empty($siteId)){
            $siteId = Session::get('credentials');
        }
        curl_setopt_array($curlt, array(
            CURLOPT_URL =>  Session::get('tableau_domain')."/api/3.2/sites/".$siteId."/workbooks/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_COOKIE =>"token=".Session::get('token'),
            CURLOPT_HTTPHEADER => array(
                "X-Tableau-Auth: ".Session::get('token'),
                "Accept: application/json",
            ),
        ));
        $response = curl_exec($curlt);
        if(!$response) {
            return false;
        }
        $err = curl_error($curlt);
        curl_close($curlt);
        if ($err) {
            return false;
        } else {
            // $response = simplexml_load_string($response);
            if(empty(json_decode($response)->workbooks) ||empty(json_decode($response)->workbooks->workbook))
                return [];
            return json_decode($response)->workbooks->workbook;
        }
    }

    public static function getWorkBooksViews($id,$siteId = null)
    {
        if(empty($siteId)){
            $siteId = Session::get('credentials');
        }
        $curlt = curl_init();
        curl_setopt_array($curlt, array(
            CURLOPT_URL => Session::get('tableau_domain')."/api/3.2/sites/".$siteId."/workbooks/".$id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "X-Tableau-Auth:".Session::get('token'),
                "Accept: application/json",
            ),
        ));
        $chilresponse = curl_exec($curlt);
        if(!$chilresponse) {
            return false;
        }
        $err = curl_error($curlt);
        curl_close($curlt);
        if ($err) {
            return false;
        } else {
            $viesdata = json_decode($chilresponse)->workbook->views->view;
            $wok = json_decode($chilresponse)->workbook;
            return [
                'viesdata' => $viesdata,
                'wok'=>$wok
            ];
        }
    }

    public static function getMiniView($id)
    {
        //获取缩略图
        $curlt = curl_init();
        /*获取用户的信息*/
        curl_setopt_array($curlt, array(
            //CURLOPT_URL =>  Session::get('tableau_domain')."/api/3.2/sites/".Session::get('credentials')."/workbooks/e51bfd80-8148-49fb-8a23-b177a73beb60/previewImage",
            CURLOPT_URL =>  Session::get('tableau_domain')."/api/3.2/sites/".Session::get('credentials')."/views/".$id."/image",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            // CURLOPT_COOKIE =>"token=".Session::get('token'),
            CURLOPT_HTTPHEADER => array(
                "X-Tableau-Auth: ".Session::get('token'),
                "Accept: application/json",
                //'Content-type: image/png'
            ),
        ));
        $response = curl_exec($curlt);
        if(!$response) {
            return false;
        }
        $err = curl_error($curlt);
        curl_close($curlt);
        if ($err) {
            return false;
        } else {
            return 'data:image/png;base64,' . base64_encode($response);
        }
    }
}
