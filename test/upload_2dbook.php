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

// デフォルトの設定はconfig.php内で設定する
require_once '../src/config.php';
require_once '../src/2dbook.php';

// file_pathを指定(必須)
// ZIP/RAR/LZH 100MB以下、画像点数200まで
$file_path = '';

$test = new Up2dbook($upload_config);

// configの上書き、追記をここで行う
// fileの追加もここで行う
/*
 * addConfig: config設定の上書き
 * setFile: ファイル名の設定
 * $config = array(
                    // 実際のファイル名と異なるファイル名で投稿できます
                    'file_name' => '',

                    // 半角英数字で10字以内(必須)
                    'delete_key' => '12345',

                    // 半角英数字で10字以内
                    'dl_key' => '1234',

                    // lock / delete_keyを使用する場合は必須
                    'provided_url' => 'http://example.com/',

                    // 配列形式で複数登録できます(3つまで)
                    'tag' => array(),

                    // 0の場合は無制限、1,2,3の場合は1日、2日、3日
                    // 7以上だと1週間、14以上だと2週間、21日以上だと３週間でそれ以上だと一ヶ月
                    'expire' => '20',

                    // ロックをかける場合はtrueに(ただしprovided_url,dl_keyは必須になる)
                    'lock' => false
                    );
 */
$config = array('file_name' => '','delete_key' => '1234', 'dl_key' => '12345', 'expire' => '0', 'provided_url' => 'http://example.com/');
$test->addConfig($config)->setFile($file_path);

// upload実行
$test->postContent();

// URLの出力
echo $test->getUrl()."\n";

// レスポンスの取得
//echo $test->getResponse()."\n";
