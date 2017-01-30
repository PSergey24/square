<?php

/*
 * This file is part of the Dektrium project
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\bootstrap\Nav;

?>

<?= Nav::widget([
    'options' => [
        'class' => 'menu-admin',
        'style' => 'margin-bottom: 15px',
    ],
    'items' => [
        [
            'label'   => Yii::t('user', 'Users'),
            'url'     => ['/admin'],
        ],
        [
            'label'   => Yii::t('user', 'Площадки'),
            'url'     => ['/admin/courts'],
        ],
        [
            'label'   => Yii::t('user', 'Популярность площадок'),
            'url'     => ['/admin/popularcourts'],
        ],
        [
            'label'   => Yii::t('user', 'Активность районов'),
            'url'     => ['/admin/activity'],
        ],
        [
            'label'   => Yii::t('user', 'Модерация картинок'),
            'url'     => ['/admin/photo'],
        ],
        [
            'label'   => Yii::t('user', 'Жалобы на сообщения'),
            'url'     => ['/admin/stat'],
        ],
        [
            'label'   => Yii::t('user', 'Жалобы на площадки'),
            'url'     => ['/admin/report_courts'],
        ],
        [
            'label'   => Yii::t('user', 'Модерация площадок'),
            'url'     => ['/admin/courtmod'],
        ],
        [
            'label'   => Yii::t('user', 'Модерация фотографий пользователей'),
            'url'     => ['/admin/photouser'],
        ],
        [
            'label'   => Yii::t('user', 'Активность пользователей'),
            'url'     => ['/admin/activityuser'],
        ],
        // [
        //     'label'   => Yii::t('user', 'Roles'),
        //     'url'     => ['/rbac/role/index'],
        //     'visible' => isset(Yii::$app->extensions['dektrium/yii2-rbac']),
        // ],
        // [
        //     'label' => Yii::t('user', 'Permissions'),
        //     'url'   => ['/rbac/permission/index'],
        //     'visible' => isset(Yii::$app->extensions['dektrium/yii2-rbac']),
        // ],
        // [
        //     'label' => Yii::t('user', 'Create'),
        //     'items' => [
        //         [
        //             'label'   => Yii::t('user', 'New user'),
        //             'url'     => ['/user/admin/create'],
        //         ],
        //         [
        //             'label' => Yii::t('user', 'New role'),
        //             'url'   => ['/rbac/role/create'],
        //             'visible' => isset(Yii::$app->extensions['dektrium/yii2-rbac']),
        //         ],
        //         [
        //             'label' => Yii::t('user', 'New permission'),
        //             'url'   => ['/rbac/permission/create'],
        //             'visible' => isset(Yii::$app->extensions['dektrium/yii2-rbac']),
        //         ],
        //     ],
        // ],
    ],
]) ?>
