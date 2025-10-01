<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'accepted'             => ':attribute を承認してください。',
    'active_url'           => ':attribute は有効なURLではありません。',
    'after'                => ':attribute には :date 以降の日付を指定してください。',
    'after_or_equal'       => ':attribute には :date 以降の日付を指定してください。',
    'alpha'                => ':attribute はアルファベットのみがご利用できます。',
    'alpha_dash'           => ':attribute は英数字とダッシュ(-)及び下線(_)がご利用できます。',
    'alpha_num'            => ':attribute は英数字のみご利用できます。',
    'array'                => ':attribute は配列でなくてはなりません。',
    'before'               => ':attribute には :date 以前の日付をご利用ください。',
    'before_or_equal'      => ':attribute には :date 以前の日付をご利用ください。',
    'between'              => [
        'numeric' => ':attribute は :min から :max までの数字で指定してください。',
        'file'    => ':attribute は :min KBから :max KBまでのサイズのファイルでなければなりません。',
        'string'  => ':attribute は :min 文字から :max 文字にしてください。',
        'array'   => ':attribute は :min 個から :max 個までの項目を含んでいなければなりません。',
    ],
    'boolean'              => ':attribute には true か false を指定してください。',
    'confirmed'            => ':attribute と確認フィールドとが一致していません。',
    'date'                 => ':attribute には有効な日付を指定してください。',
    'date_equals'          => ':attribute には :date と同じ日付を指定してください。',
    'date_format'          => ':attribute の形式が :format と一致していません。',
    'different'            => ':attribute と :other には異なった内容を指定してください。',
    'digits'               => ':attribute は :digits 桁でなければなりません。',
    'digits_between'       => ':attribute は :min 桁から :max 桁でなければなりません。',
    'email'                => ':attribute には有効なメールアドレスを指定してください。',
    'exists'               => '選択された :attribute は正しくありません。',
    'file'                 => ':attribute はファイルでなければなりません。',
    'filled'               => ':attribute は必須です。',
    'gt'                   => [
        'numeric' => ':attribute には :value より大きな値を指定してください。',
        'file'    => ':attribute には :value KBより大きなファイルを指定してください。',
        'string'  => ':attribute は :value 文字より多く入力してください。',
        'array'   => ':attribute は :value 個より多くの項目を含んでいなければなりません。',
    ],
    'gte'                  => [
        'numeric' => ':attribute には :value 以上の値を指定してください。',
        'file'    => ':attribute には :value KB以上のファイルを指定してください。',
        'string'  => ':attribute は :value 文字以上でなければなりません。',
        'array'   => ':attribute は :value 個以上の項目を含んでいなければなりません。',
    ],
    'image'                => ':attribute には画像ファイルを指定してください。',
    'in'                   => '選択された :attribute は正しくありません。',
    'integer'              => ':attribute には整数を指定してください。',
    'ip'                   => ':attribute には有効なIPアドレスを指定してください。',
    'ipv4'                 => ':attribute には有効なIPv4アドレスを指定してください。',
    'ipv6'                 => ':attribute には有効なIPv6アドレスを指定してください。',
    'json'                 => ':attribute には有効なJSON文字列を指定してください。',
    'lt'                   => [
        'numeric' => ':attribute には :value より小さな値を指定してください。',
        'file'    => ':attribute には :value KBより小さなファイルを指定してください。',
        'string'  => ':attribute は :value 文字より少なく入力してください。',
        'array'   => ':attribute は :value 個より少ない項目を含んでいなければなりません。',
    ],
    'lte'                  => [
        'numeric' => ':attribute には :value 以下の値を指定してください。',
        'file'    => ':attribute には :value KB以下のファイルを指定してください。',
        'string'  => ':attribute は :value 文字以下でなければなりません。',
        'array'   => ':attribute は :value 個以下の項目を含んでいなければなりません。',
    ],
    'max'                  => [
        'numeric' => ':attribute には :max 以下の値を指定してください。',
        'file'    => ':attribute には :max KB以下のファイルを指定してください。',
        'string'  => ':attribute は :max 文字以下で入力してください。',
        'array'   => ':attribute は :max 個以下でなければなりません。',
    ],
    'min'                  => [
        'numeric' => ':attribute には :min 以上の値を指定してください。',
        'file'    => ':attribute には :min KB以上のファイルを指定してください。',
        'string'  => ':attribute は :min 文字以上で入力してください。',
        'array'   => ':attribute は :min 個以上の項目を含んでいなければなりません。',
    ],
    'not_in'               => '選択された :attribute は正しくありません。',
    'numeric'              => ':attribute には数値を指定してください。',
    'present'              => ':attribute が存在していません。',
    'regex'                => ':attribute の形式が正しくありません。',
    'required'             => ':attribute は必須です。',
    'required_if'          => ':other が :value の場合、:attribute は必須です。',
    'required_unless'      => ':other が :values でない限り、:attribute は必須です。',
    'required_with'        => ':values が指定されている場合、:attribute は必須です。',
    'required_with_all'    => ':values が全て指定されている場合、:attribute は必須です。',
    'required_without'     => ':values が指定されていない場合、:attribute は必須です。',
    'required_without_all' => ':values が全て指定されていない場合、:attribute は必須です。',
    'same'                 => ':attribute と :other が一致しません。',
    'size'                 => [
        'numeric' => ':attribute は :size でなければなりません。',
        'file'    => ':attribute は :size KBでなければなりません。',
        'string'  => ':attribute は :size 文字でなければなりません。',
        'array'   => ':attribute は :size 個含まれていなければなりません。',
    ],
    'string'               => ':attribute は文字列でなければなりません。',
    'timezone'             => ':attribute には正しいタイムゾーンを指定してください。',
    'unique'               => ':attribute は既に存在しています。',
    'url'                  => ':attribute の形式が正しくありません。',
    'uuid'                 => ':attribute は有効なUUIDでなければなりません。',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    */

    'attributes' => [],

];
