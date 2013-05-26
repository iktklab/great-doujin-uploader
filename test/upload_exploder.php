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
require_once '../src/exploader.php';

// アップロードしたいfile名を指定(必須)
// 日本語も英数字でも可
// 対応圧縮形式：ZIP/RAR/LZH
// ファイルサイズ上限：30MB以下
// 解凍にパスワードが必要な圧縮ファイルはアップロードできません
// 対応画像形式：jpeg/gif/png
// ファイル総数：画像200枚以下
// フォルダ数：1個以下
// 1ファイル辺りのファイルサイズ上限：5MB以下
// 解凍後のファイルサイズ総計:100MB以下
$file_name = '';

$test = new Exploader($upload_config);

// configの上書き、追記をここで行う
// fileの追加もここで行う
/*
 * addConfig: config設定の上書き
 * setFile: ファイル名の設定
 * $config = array(
                   // 削除Key(英数字)(必須)
                   'delete_key' => '12345',
                   // DL Key(英数字)(必須)
                   'dl_key' => '1234',
                   // 保存期間(defaultが30日)
                   'expire' => '30',
                   // 公開日時(Y-m-d H:i)形式で
                   'pub_date' => '2013-05-26 01:23',
                   // ロック(true or false)
                   'lock' => false,
                   // クッキーに保存(true or false)
                   'cookie' => false,
                    );
 */
$config = array('delete_key' => '12345', 'dl_key' => '1234', 'expire' => '20');
$test->addConfig($config)->setFile($file_name);

// upload実行
$test->postContent();

// URLの出力
echo $test->getUrl()."\n";

// htmlレスポンスの取得
// echo $test->getResponse();
