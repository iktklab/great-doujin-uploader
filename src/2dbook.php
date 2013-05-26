<?php
/*
 * Copyright 2013 Iktklab Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License atan *
 *     http://www.apache.org/licenses/LICENSE-2.0 *
 * Unless required by applicable law or agreed to in writing, software * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and * limitations under the License.
 */
require_once 'base_uploader.php';

class Up2dbook extends Base_Uploader {

    // $uploader is uploader name.
    public $uploader = '2dbook';

    // initialized
    public function __construct($config = array()) {
        parent::__construct($config);

        $uploader = $this->uploader;
        $this->file_name     = $config['service'][$uploader]['file_name'];
        $this->provided_url  = $config['service'][$uploader]['provided_url'];
        $this->tag           = $config['service'][$uploader]['tag'];
        $this->ticket        = self::getTicket();
    }

    public function addConfig($addconfig = array()) {

        // I complement here the missing settings
        if (isset($addconfig['file_name'])) $this->file_name = $addconfig['file_name'];
        if (isset($addconfig['delete_key'])) $this->delete_key = $addconfig['delete_key'];
        if (isset($addconfig['dl_key'])) $this->download_key = $addconfig['dl_key'];
        if (isset($addconfig['provided_url'])) $this->provided_url = $addconfig['provided_url'];
        if (isset($addconfig['tag'])) $this->tag = $addconfig['tag'];
        if (isset($addconfig['expire'])) $this->expire = $addconfig['expire'];
        if (isset($addconfig['lock'])) $this->lock = $addconfig['lock'];
        if (isset($addconfig['cookie'])) $this->cookie = $addconfig['cookie'];
        return $this;
    }


    /*
     * If you get 2dbook uploaded books, Ticket is nessesary.
     * Ticket is related Cookie. The first, you get Cookie.
     */
    public function getTicket() {

        $this->cdata=tempnam(sys_get_temp_dir(),'cookie_');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->upload_url);
        // UserAgentの設定
        curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
        // 結果を文字列として返す
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // cookieに書き込む
        curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cdata);
        $response = curl_exec($ch);
        curl_close($ch);
        if ($response) {
            preg_match('/name\=\"data\[ticket\]\" value\=\"([0-9a-zA-Z]+)/',$response,$matches);
            // チケットを取得
            return $matches[1];
            // curlを連続で叩くのは良くないので、数秒間間隔をあけてcurlを実行する
            sleep($this->sleep_time);
        } else {
            exit("ERROR. upload_url gets error response code. (Curlでupload_urlをgetする際にエラーが発生しました。)");
        }
    }


    public function convertExpire() {
        if (($this->expire < '1') || ($this->expire > '30')) {
            return 'infi';
        } elseif($this->expire === '1') {
            return '1day';
        } elseif($this->expire === '2') {
            return '2day';
        } elseif($this->expire === '3') {
            return '3day';
        } elseif($this->expire < '8') {
            return '1week';
        } elseif($this->expire < '15') {
            return '2week';
        } elseif($this->expire < '22') {
            return '3week';
        } else {
            return '1month';
        }
    }

    public function convertTag() {
        $tag_str = implode("",$this->tag);
        return $tag_str;
    }
    public function execurl() {
        header( "Content-Type: text/html; Charset=UTF8" );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->upload_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postfields);
        // Ticketを取得したときのクッキーを使用する
        curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cdata);
        // UserAgentの設定
        curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);
        //ヘッダ先にリダイレクトする
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        //結果を文字列として返す
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        return $response;
    }

    public function postContent() {
        $file     = parent::convertFile();
        $this->postfields = array(
            "_method" => 'POST',
            "data[Book][upfile]"       => $file,
            "data[Book][upfile_name]"  => $this->file_name,
            "data[Book][delete_pass]"  => $this->delete_key,
            "data[Book][download_key]" => $this->download_key,
            "data[Book][url]"          => $this->provided_url,
            "data[Book][tag]"          => self::convertTag($this->tag),
            "data[Book][strage]"       => self::convertExpire($this->expire),
            "data[Book][lock_key]"     => ($this->lock) ? "1" : "0",
            "data[Book][remember]"     => ($this->cookie) ? "1" : "0",
            "data[ticket]"             => $this->ticket,
                            );
        // なにかおかしいとおもったら下記のコメントアウトを外してpostfieldsを確認するといい
        //var_dump($this->postfields);
        //exit(0);
        $response = self::execurl();
        if( !$response ) {
            echo curl_error( $ch );
            exit("ERROR. curl response no data. (curlからのレスポンスデータがありません。)");
        }
        $this->response = $response;
        return $this;
    }

    // ダウンロード用のURLコードを取得
    public function getUrl(){
        preg_match('/(http\:\/\/2dbook\.com\/books\/[0-9a-zA-Z\/]+)/',$this->response, $matches);
        $session_url = $matches[1];
        return $session_url;
    }

}