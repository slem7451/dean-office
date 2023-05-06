<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');

defined('DB_INSERT') or define('DB_INSERT', 1);
defined('DB_UPDATE') or define('DB_UPDATE', 2);
defined('DB_DELETE') or define('DB_DELETE', 3);
