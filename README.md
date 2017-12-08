# AuthShibboleth

AuthShibbolethプラグインは、shibbolethログインをすることができる、NetCommons3の拡張プラグインです。

[![Build Status](https://travis-ci.org/NetCommons3/AuthShibboleth.svg?branch=master)](https://travis-ci.org/NetCommons3/AuthShibboleth)
[![Coverage Status](https://img.shields.io/coveralls/NetCommons3/AuthShibboleth.svg)](https://coveralls.io/github/NetCommons3/AuthShibboleth)

| dependencies | status |
| ------------ | ------ |
| composer.json | [![Dependency Status](https://www.versioneye.com/user/projects/59ffab362de28c000fa16315/badge.svg?style=flat)](https://www.versioneye.com/user/projects/59ffab362de28c000fa16315) |

### 前提

* NetCommon3.1.7以降に対応
* 別途、Shibooleth SPの導入が必要です。

### 画面

![Shiboolethログイン](https://github.com/NetCommons3/AuthShibboleth//wiki/images/shibboleth.png)
![ユーザ紐づけ](https://github.com/NetCommons3/AuthShibboleth//wiki/images/shibboleth2.png)
![Shiboolethログイン設定](https://github.com/NetCommons3/AuthShibboleth//wiki/images/shibboleth3.png)

### インストール

* プラグインを配置
* copyToDocumentRoot をドキュメントルートにコピー
* マイグレーションrun all実行

### アンインストール

* マイグレーションdownを数回実行
* copyToDocumentRoot のファイルを削除。htaccessを元に戻す
* プラグインを削除
