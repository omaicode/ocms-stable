<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute 를 허용해야 합니다.',
    'accepted_if' => ':other 가 :value 인 경우 :attribute 를 허용해야 합니다.',
    'active_url' => ':attribute 는 유효한 URL이 아닙니다.',
    'after' => ':attribute 는 :date 이후의 날짜여야 합니다.',
    'after_or_equal' => ':attribute 는 :date 이후의 날짜여야 합니다.',
    'alpha' => ':attribute 에는 문자만 포함되어야 합니다.',
    'alpha_dash' => ':attribute 에는 문자, 숫자, 대시 및 밑줄만 포함되어야 합니다.',
    'alpha_num' => ':attribute 은 문자와 숫자만 포함해야 합니다.',
    'array' => ':attribute 는 배열이어야 합니다.',
    'before' => ':attribute 는 :date 이전 날짜여야 합니다',
    'before_or_equal' => ':attribute 는 :date 이전 또는 같은 날짜여야 합니다.',
    'between' => [
        'numeric' => ':attribute 는 :min 과 :max 사이에 있어야 합니다.',
        'file' => ':attribute 는 :min 에서 :max KB 사이여야 합니다.',
        'string' => ':attribute 는 :min 과 :max 문자 사이에 있어야 합니다.',
        'array' => ':attribute 는 :min 과 :max 사이에 있어야 합니다.',
    ],
    'boolean' => ':attribute 필드는 true 또는 false 여야 합니다.',
    'confirmed' => ':attribute 가 일치하지 않습니다.',
    'current_password' => '비밀번호가 올바르지 않습니다.',
    'date' => ':attribute 은 유효한 날짜가 아닙니다.',
    'date_equals' => ':attribute 는 :date 와 동일한 날짜여야 합니다.',
    'date_format' => ':attribute 가 :format 와 형식이 일치하지 않습니다.',
    'declined' => ':attribute 는 거부되어야 합니다.',
    'declined_if' => ':other 가 :value 인 경우 :attribute 를 거부해야 합니다.',
    'different' => ':attribute 와 :other 는 서로 달라야 합니다.',
    'digits' => ':attribute 는 :digits 숫자여야 합니다.',
    'digits_between' => ':attribute 는 :min 과 :max 사이에 있어야 합니다.',
    'dimensions' => 'The :attribute 에 잘못된 이미지 사이즈가 있습니다',
    'distinct' => ':attribute 에 중복된 값이 있습니다.',
    'email' => ':attribute 는 유효한 이메일 주소여야 합니다.',
    'ends_with' => ':attribute 는 다음 중 하나로 끝나야 합니다: :values.',
    'enum' => '선택한 :attribute 이 잘못되었습니다.',
    'exists' => '선택한  :attribute 이 잘못되었습니다.',
    'file' => ':attribute 는 파일이어야 합니다.',
    'filled' => ':attribute 필드에는 값이 있어야 합니다.',
    'gt' => [
        'numeric' => ':attribute 는 :value 보다 커야 합니다.',
        'file' => ':attribute 는 :value KB 보다 커야 합니다.',
        'string' => ':attribute 는 :value 문자보다 커야 합니다.',
        'array' => ':attribute 는 :value 보다 많은 항목이 있어야 합니다.',
    ],
    'gte' => [
        'numeric' => ':attribute 는 :value 보다 크거나 같아야 합니다.',
        'file' => ':attribute 는 :value KB 보다 크거나 같아야 합니다.',
        'string' => ':attribute 는 :value 문자보다 크거나 같아야 합니다.',
        'array' => ':attribute 는 :value 항목 이상이 있어야 합니다.',
    ],
    'image' => ':attribute 는 이미지 여야 합니다.',
    'in' => '선택한 :attribute 이 잘못되었습니다.',
    'in_array' => ':attribute 필드는 :other 에 존재하지 않습니다.',
    'integer' => ':attribute 는 정수여야 합니다.',
    'ip' => ':attribute 는 유효한 IP 주소여야 합니다.',
    'ipv4' => ':attribute 는 유효한 IPv4 주소여야 합니다.',
    'ipv6' => ':attribute 는 유효한 IPv6 주소여야 합니다.',
    'json' => ':attribute 는 유효한 JSON 문자열이어야 합니다.',
    'lt' => [
        'numeric' => ':attribute 는 :value 보다 작아야 합니다.',
        'file' => ':attribute 는 :value KB 보다 작아야 합니다.',
        'string' => ':attribute 는 :value 문자보다 작아야 합니다.',
        'array' => ':attribute 는 :value 보다 적은 항목이 있어야 합니다.',
    ],
    'lte' => [
        'numeric' => ':attribute 는 :value 보다 작거나 같아야 합니다.',
        'file' => ':attribute 는 :value KB 보다 작거나 같아야 합니다.',
        'string' => ':attribute 는 :value 문자 보다 작거나 같아야 합니다.',
        'array' => ':attribute 는 :value 보다 많은 항목이 있어서는 안 됩니다.',
    ],
    'mac_address' => ':attribute 는 유효한 MAC 주소여야 합니다.',
    'max' => [
        'numeric' => ':attribute 는 :max 보다 클 수 없습니다.',
        'file' => ':attribute 는 :max KB 보다 클 수 없습니다.',
        'string' => ':attribute 는 :max 자를 초과할 수 없습니다.',
        'array' => ':attribute 는 :max 개 이상의 항목이 있어서는 안 됩니다.',
    ],
    'mimes' => ':attribute 는 :values 유형의 파일이어야 합니다.',
    'mimetypes' => ':attribute 는 :values 유형의 파일이어야 합니다.',
    'min' => [
        'numeric' => ':attribute 는 최소한 :min 이상이어야 합니다.',
        'file' => ':attribute 는 최소 :min KB 이상이어야 합니다.',
        'string' => ':attribute 는 :min 자 이상이어야 합니다.',
        'array' => ':attribute 는 최소한 :min 항목이 있어야 합니다.',
    ],
    'multiple_of' => ':attribute 는 :value 의 배수여야 합니다.',
    'not_in' => '선택한 :attribute 이 잘못되었습니다.',
    'not_regex' => ':attribute 형식이 잘못되었습니다.',
    'numeric' => ':attribute 은 숫자여야 합니다.',
    'password' => '비밀번호가 올바르지 않습니다..',
    'present' => ':attribute 필드가 있어야 합니다.',
    'prohibited' => ':attribute 필드는 사용할 수 없습니다.',
    'prohibited_if' => ':other 가 :value 인 경우 :attribute 필드는 사용할 수 없습니다.',
    'prohibited_unless' => ':other 가 :values 에 있지 않으면 :attribute 필드는 사용할 수 없습니다.',
    'prohibits' => ':attribute 필드는 :other 에 사용할 수 없습니다.',
    'regex' => ':attribute 형식이 잘못되었습니다.',
    'required' => ':attribute 필드는 필수입니다.',
    'required_array_keys' => ':attribute 필드에는 :values 에 대한 항목이 포함되어야 합니다.',
    'required_if' => ':other 가 :value 인 경우 :attribute 필드가 필요합니다.',
    'required_unless' => ':other 가 :values 에 있지 않는 한 :attribute 필드는 필수입니다.',
    'required_with' => ':values 가 있는 경우 :attribute 필드가 필요합니다.',
    'required_with_all' => ':values 가 있는 경우 :attribute 필드가 필요합니다.',
    'required_without' => ':values 가 없는 경우 :attribute 필드가 필요합니다.',
    'required_without_all' => ':value 가 하나도 없을 때 :attribute 필드가 필요합니다.',
    'same' => ':attribute 와 :other 가 일치해야 합니다.',
    'size' => [
        'numeric' => ':attribute 은 :size 사이즈 여야 합니다.',
        'file' => ':attribute 는 :size KB 여야 합니다.',
        'string' => ':attribute 는 :size 문자 여야 합니다..',
        'array' => ':attribute 에는 :size 항목이 포함되어야 합니다.',
    ],
    'starts_with' => ':attribute 는 다음 중 하나로 시작해야 합니다: :values.',
    'string' => ':attribute 는 문자열이어야 합니다.',
    'timezone' =>':attribute 는 유효한 시간대여야 합니다',
    'unique' => ':attribute 가 이미 사용되었습니다.',
    'uploaded' => ':attribute 을 업로드하지 못했습니다.',
    'url' => ':attribute 는 유효한 URL 이어야 합니다.',
    'uuid' => ':attribute 는 유효한 UUID 여야 합니다.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => '커스텀 메세지',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
