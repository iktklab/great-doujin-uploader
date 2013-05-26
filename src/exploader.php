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

class Exploader extends Base_Uploader {

    // $uploader is uploader name.
    public $uploader = 'exploader';

    // initialized
    public function __construct($config = array()) {
        parent::__construct($config);

        $uploader = $this->uploader;
        $this->publish_date  = $config['service'][$uploader]['pub_date'];
        $this->max_file_size = $config['service'][$uploader]['max_file_size'];
    }

    // add configure
    public function addConfig($addconfig = array()) {

        // I complement here the missing settings
        if (isset($addconfig['delete_key'])) $this->delete_key = $addconfig['delete_key'];
        if (isset($addconfig['dl_key'])) $this->download_key = $addconfig['dl_key'];
        if (isset($addconfig['pub_date'])) $this->publish_date = $addconfig['pub_date'];
        if (isset($addconfig['expire'])) $this->expire = $addconfig['expire'];
        if (isset($addconfig['max_file_size'])) $this->max_file_size = $addconfig['max_file_size'];
        if (isset($addconfig['lock'])) $this->lock = $addconfig['lock'];
        if (isset($addconfig['cookie'])) $this->cookie = $addconfig['cookie'];
        return $this;
    }

    // execuse curl
    public function execurl() {
        header( "Content-Type: text/html; Charset=UTF8" );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->upload_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postfields);
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
        $pub_date = parent::convertDate();
        $file     = parent::convertFile();
        $this->postfields = array(
            "file"     => $file,
            "password" => $this->delete_key,
            "anon_password" => $this->download_key,
            "year"     => $pub_date[0],
            "month"    => $pub_date[1],
            "day"      => $pub_date[2],
            "hour"     => $pub_date[3],
            "minutes"  => $pub_date[4],
            "rp"       => $this->expire,
            "uri_lock" => ($this->lock) ? "1" : "0",
            "cookie_save"   => ($this->cookie) ? "1" : "0",
            "MAX_FILE_SIZE" => $this->max_file_size,
                            );
        //var_dump($this->postfields);
        $response = self::execurl();
        if( !$response ) {
            echo curl_error( $ch );
            exit("ERROR. curl response no data. (curlからのレスポンスデータがありません。)");
        }
        $this->response = $response;
        return $this;
    }

    public function getUrl(){
        preg_match('/(http\:\/\/www\.exploader\.net\/download\/[^"]+)/',$this->response, $matches);
        $session_url = $matches[1];
        return $session_url;
    }

}