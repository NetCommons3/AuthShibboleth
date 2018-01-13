# AuthShibboleth

AuthShibbolethプラグインは、shibbolethログインをすることができる、NetCommons3の拡張プラグインです。

[![Build Status](https://travis-ci.org/NetCommons3/AuthShibboleth.svg?branch=master)](https://travis-ci.org/NetCommons3/AuthShibboleth)
[![Coverage Status](https://img.shields.io/coveralls/NetCommons3/AuthShibboleth.svg)](https://coveralls.io/github/NetCommons3/AuthShibboleth)

| dependencies | status |
| ------------ | ------ |
| composer.json | [![Dependency Status](https://www.versioneye.com/user/projects/59ffab362de28c000fa16315/badge.svg?style=flat)](https://www.versioneye.com/user/projects/59ffab362de28c000fa16315) |

* [前提](#前提)
* [画面](#画面)
* [拡張プラグインのインストール・アンインストール(外部リンク)](https://github.com/NetCommons3/NetCommons3/wiki/%E6%8B%A1%E5%BC%B5%E3%83%97%E3%83%A9%E3%82%B0%E3%82%A4%E3%83%B3%E3%81%AE%E3%82%A4%E3%83%B3%E3%82%B9%E3%83%88%E3%83%BC%E3%83%AB%E3%83%BB%E3%82%A2%E3%83%B3%E3%82%A4%E3%83%B3%E3%82%B9%E3%83%88%E3%83%BC%E3%83%AB)

### 前提

* NetCommon3.1.7以降に対応。
* 別途、Shibooleth SPの導入が必要です。
* [copyToDocumentRootの中身](https://github.com/NetCommons3/AuthShibboleth/tree/master/copyToDocumentRoot) をNetCommon3のインストールディレクトリ（例：/var/www/html/nc3/）に上書きコピーします。

### 画面

![Shiboolethログイン](https://github.com/NetCommons3/AuthShibboleth//wiki/images/shibboleth.jpg)
![Embedded DS](https://github.com/NetCommons3/AuthShibboleth//wiki/images/shibboleth1.jpg)
![ID関連付け](https://github.com/NetCommons3/AuthShibboleth//wiki/images/shibboleth2.jpg)
![Shiboolethログイン設定](https://github.com/NetCommons3/AuthShibboleth//wiki/images/shibboleth3.jpg)
