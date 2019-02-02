<?php

return [

    /*Данная опция включает возможность отображения некоторых
    функций личного кабинета для незарегистрированного пользователя*/

    'lk_for_noreg' => env('LK_FOR_NOREG', true),

    'default_fields' => [
        'id' => ['type' => 'chars', 'title' => '#', 'filter_template' => 'fogcms::records/attrs/filter/id', 'params' => ['attr' => 'id']],
        'created_at' => ['type' => 'chars', 'title' => __('Created at'), 'icon' => 'mdi mdi-plus-box', 'filter_template' => 'fogcms::records/attrs/filter/created_at', 'params' => ['attr' => 'created_at']],
        'updated_at' => ['type' => 'chars', 'title' => __('Updated at'), 'icon' => 'mdi mdi-pencil-box', 'filter_template' => 'fogcms::records/attrs/filter/updated_at', 'params' => ['attr' => 'updated_at']],
        'status' => ['type' => 'status', 'title' => __('Status'), 'icon' => 'mdi mdi-checkbox-marked', 'filter_template' => 'fogcms::records/attrs/filter/status', 'data_template' => 'fogcms::records/attrs/data/status', 'params' => ['attr' => 'status']],
        'user_id' => ['type' => 'chars', 'title' => __('User'), 'icon' => 'mdi mdi-account-box', 'filter_template' => 'fogcms::records/attrs/filter/user_id', 'params' => ['attr' => 'user_id']],
        'rating' => ['type' => 'chars', 'title' => __('Rating'), 'icon' => 'mdi mdi-star-box', 'filter_template' => 'fogcms::records/attrs/filter/rating', 'params' => ['attr' => 'rating']],
    ],

//    'connections' => [
//        'mysql' => [
//            'strict' => false,
//        ],
//    ],
];
