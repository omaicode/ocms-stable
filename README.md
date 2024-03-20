<p align="center"><a href="https://omaicode.com" target="_blank"><img src="https://omaicode.com/themes/pkurg-nubis-child/assets/images/og_image.jpg" width="400"></a></p>

## About OCMS

OCMS is a CMS based on Laravel Framework that allows you to build websites for any purpose. It has powerful tools for developers to build any kind of website.

## Requirements

| Name | Version | Description |
| ---- | ------- | ----------- |
| Web server | Nginx or Apache | - |
| PHP | >= 7.3 | Extensions (Sodium, BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML) |
| MySql | >= 5.7 | - |
| Composer | v2 | https://getcomposer.org/ |

## How to install

Install by enter command:

```
composer create omaicode/ocms-stable ./project_path
```

After composer created project success, set your Nginx or Apache document root to **./project_path/public** folder and then access to the browser and enter your URL.

Last step, enter your database connection, admin account information and click **INSTALL**


## LICENSE
MIT
