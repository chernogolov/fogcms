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
        'rating' => ['type' => 'rating', 'title' => __('Rating'), 'icon' => 'mdi mdi-star-box', 'filter_template' => 'fogcms::records/attrs/filter/rating', 'params' => ['attr' => 'rating']],
    ],

//    'connections' => [
//        'mysql' => [
//            'strict' => false,
//        ],
//    ],

    'erc_email'                 => env('ERC_EMAIL', ''),
    'erc_password'              => env('ERC_PASSWORD', ''),
    'accounts_reg_id'           => env('ACCOUNTS_REG_ID', ''),
    'devices_reg_id'            => env('DEVICES_REG_ID', ''),
    'add_values_reg_id'         => env('ADD_VALUES_REG_ID', ''),
    'tickets_reg_id'            => env('TICKETS_REG_ID', ''),
    'qa_reg_id'                 => env('QA_REG_ID', ''),
    'news_reg_id'               => env('NEWS_REG_ID', ''),
    'accepted_values_reg_id'    => env('ACCEPTED_VALUES_REG_ID', ''),
    'contacts_reg_id'           => env('CONTACTS_REG_ID', ''),
    'organisation_reg_id'       => env('ORGANISATION_REG_ID', ''),
    'addresses_reg_id'          => env('ADDRESSES_REG_ID', ''),
    'ads_user'                  => env('ADS_USER', ''),

    'documents_reg_id'          => env('DOCUMENTS_REG_ID', ''),
    'prot_oss'                  => env('PROT_OSS_REG_ID', ''),
    'prot_sov'                  => env('PROT_SOV_REG_ID', ''),
    'fin_otch'                  => env('FIN_OTCH_REG_ID', ''),

    'frisbi_link'               => env('FRISBI_LINK', ''),

];
