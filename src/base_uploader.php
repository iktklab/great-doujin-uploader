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

class Base_Uploader {

    public $upload_file;

    public $uplaod_path;

    // $main_url is based url.
    public $main_url;

    // $upload_url is upload form url.
    public $upload_url;

    // If you want to delete your uploaded files, you should use $delete_key.
    public $delete_key;

    //If we want to download files, you should use password. Its password is $download_key.
    public $download_key;

    public $expire;

    public $lock;

    public $cookie;

    public $publish_date;

    public $user_agent = '';

    public $sleep_time;

    public $provided_url;

    public $max_file_size;

    public $postfields;

    public $ticket;

    public $response;

    public function __construct($config){

        $this->user_agent   = $config['user_agent'];
        $this->upload_path  = $config['uploadfile_path'];
        $this->sleep_time   = $config['sleep_time'];

        $uploader           = $this->uploader;
        $this->main_url     = $config['service'][$uploader]['main'];
        $this->upload_url   = $config['service'][$uploader]['upload'];
        $this->delete_key   = $config['service'][$uploader]['delete_key'];
        $this->download_key = $config['service'][$uploader]['dl_key'];
        $this->expire       = $config['service'][$uploader]['expire'];
        $this->lock         = $config['service'][$uploader]['lock'];
        $this->cookie       = $config['service'][$uploader]['cookie'];
    }

    public function setFile($file) {
        $file = mb_convert_encoding($file, "UTF-8", "auto");
        $file = $this->upload_path."/".$file;
        if (file_exists($file)) {
            $this->upload_file = $file;
            return $this;
        } else {
            exit("Can't open $file . (ファイル ($file) をオープンできません)");
        }
    }

    public function convertFile() {
        if ($this->upload_file) {
            $mime = mime_content_type($this->upload_file);
            $file = "@".$this->upload_file.";type=".$mime;
            return $file;
        } else {
            exit("you should use setFile(). (setFile()を使用してください。)");
        }
    }

    public function convertDate() {
        $date = explode(' ',$this->publish_date);
        $ymd  = explode('-', $date[0]);
        $his  = explode(':', $date[1]);
        return array_merge($ymd,$his);
    }

    public function getResponse() {
        return $this->response;
    }
}
