<?php

namespace frontend\assets;

use yii\web\AssetBundle;

class SelfAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/self.css',
    ];
    public $js = [
    ];
    public $depends = [];
}