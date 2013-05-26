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

global $upload_config;
$upload_config = array(
    // fileの場所(example:'/usr/local/src')
    "uploadfile_path" => '',
    // UserAgentの設定
    "user_agent" => 'Mozilla/5.0 (Windows NT 5.1; rv:21.0) Gecko/20130401 Firefox/21.0',
    // url遷移の間隔をsleep_time秒数分空ける
    "sleep_time" => 5,
    "service" => array(
        "exploader" => array(
            // mainはトップページアドレス
            "main"   => 'http://www.exploader.net',
            // uploadはuploadページアドレス
            "upload" => 'http://www.exploader.net/upload/post',
            "delete_key"   => '',
            "dl_key" => '',
            "pub_date" => date('Y-m-d H:i'),
            "expire" => '30',
            "max_file_size" => '31457280',
            "lock"   => true,
            "cookie" => false,
                             ),
        "2dbook"    => array(
            // mainはトップページアドレス
            "main"   => 'http://2dbook.com/',
            // uploadはuploadページアドレス
            "upload" => 'http://2dbook.com/uploads',
            "file_name"    => '',
            "delete_key"   => '',
            "dl_key" => '',
            "provided_url" => '',
            "tag"    => array(),
            "expire" => '0',
            "lock"   => true,
            "cookie" => false,
                             ),
        ),
    );
