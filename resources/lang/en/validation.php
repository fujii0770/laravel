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

    'not_special_char'           => ':attribute must exclude the character " \'`! $% ^ @ #',
    'accepted'             => 'Please approve the :attribute.',
    'active_url'           => 'Specify a valid URL for :attribute.',
    'after'                => 'For :attribute, specify a date after :date.',
    'after_or_equal'       => ':attribute must be a value after or equal to the :date',
    'alpha'                => 'For :attribute, specify a character string consisting only of alphabetic characters.',
    'alpha_dash'           => 'For :attribute, specify a character string consisting only of alphanumeric characters, hyphens, and underscores.',
    'alpha_num'            => 'For :attribute, specify a character string consisting only of alphanumeric characters.',
    'array'                => 'Specify an array for :attribute.',
    'before'               => 'For :attribute, specify a date preceding :date.',
    'before_or_equal'      => ':attribute must be a value preceding or equal to the :date',
    'between'              => [
        'numeric' => 'Specify a number from :min to :max for :attribute.',
        'file'    => 'For :attribute, specify a file of :min ~ :max KB.',
        'string'  => ':attribute must have a size between the given :min and :max',
        'array'   => 'Number items of :attribute must be more than :min and less than :max.',
    ],
    'boolean'              => ':attribute field must be true or false.',
    'confirmed'            => ':attribute confirmation does not match.',
    'date'                 => ':attribute is not a valid date.',
    'date_format'          => ':attribute does not match the format :format.',
    'different'            => ':attribute and :other must be different.',
    'digits'               => ':attribute must be :digits digits.',
    'digits_between'       => ':attribute must be between :min and :max digits.',
    'dimensions'           => ':attribute has invalid image dimensions.',
    'distinct'             => ':attribute field has a duplicate value.',
    'email'                => ':attribute must be a valid email address.',
    'exists'               => 'The selected :attribute is invalid.',
    'file'                 => ':attribute must be a file.',
    'filled'               => ':attribute field is required.',
    'gt'                   => [
        'numeric' => 'For :attribute, specify a value larger than :value.',
        'file'    => 'For :attribute, specify a file larger than :value kB.',
        'string'  => 'Specify :attribute longer than :value character.',
        'array'   => 'For :attribute, specify more than :value items.',
    ],
    'gte'                  => [
        'numeric' => 'For :attribute, specify a value greater than or equal to :value.',
        'file'    => 'For :attribute, specify a file of :value kB or more.',
        'string'  => 'Specify :attribute with :value character or more.',
        'array'   => 'For :attribute, specify :value or more items.'
    ],
    'image'                => ':attribute must be an image.',
    'in'                   => 'The selected :attribute is invalid.',
    'in_array'             => ':attribute field does not exist in :other.',
    'integer'              => ':attribute must be an integer.',
    'ip'                   => ':attribute must be a valid IP address.',
    'ipv4'                 => 'Specify the correct format IPv4 address for :attribute.',
    'ipv6'                 => 'Specify the correct format IPv6 address for :attribute.',
    'json'                 => ':attribute must be a valid JSON string.',
    'lt'                   => [
        'numeric' => 'For :attribute, specify a value smaller than :value.',
        'file'    => 'For :attribute, specify a file smaller than :value kB.',
        'string'  => 'Specify :attribute shorter than :value character.',
        'array'   => 'For :attribute, specify less than :value items.',
    ],
    'lte'                  => [
        'numeric' => 'For :attribute, specify a value less than or equal to :value.',
        'file'    => 'For :attribute, specify a file under :value kB.',
        'string'  => 'Specify :attribute with :value character or less.',
        'array'   => 'For :attribute, specify no more than :value items.',
    ],
    'max'                  => [
        'numeric' => ':attribute may not be greater than :max.',
        'file'    => ':attribute may not be greater than :max kilobytes.',
        'string'  => ':attribute may not be greater than :max characters.',
        'array'   => ':attribute may not have more than :max items.',
    ],
    'mimes'                => ':attribute must be a file of type: :values.',
    'mimetypes'            => ':attribute must be a file of type: :values.',
    'min'                  => [
        'numeric' => ':attribute must be at least :min.',
        'file'    => ':attribute must be at least :min kilobytes.',
        'string'  => ':attribute must be at least :min characters.',
        'array'   => ':attribute must have at least :min items.',
    ],
    'not_in'               => 'The selected :attribute is invalid.',
    'not_regex'            => 'The :attribute format is invalid.',
    'numeric'              => ':attribute must be a number.',
    'present'              => ':attribute field must be present.',
    'regex'                => ':attribute format is invalid.',
    'required'             => ':attribute field is required.',
    'required_if'          => ':attribute field is required when :other is :value.',
    'required_unless'      => ':attribute field is required unless :other is in :values.',
    'required_with'        => ':attribute field is required when :values is present.',
    'required_with_all'    => ':attribute field is required when :values is present.',
    'required_without'     => ':attribute field is required when :values is not present.',
    'required_without_all' => ':attribute field is required when none of :values are present.',
    'same'                 => ':attribute and :other must match.',
    'size'                 => [
        'numeric' => ':attribute must be :size.',
        'file'    => ':attribute must be :size kilobytes.',
        'string'  => ':attribute must be :size characters.',
        'array'   => ':attribute must contain :size items.',
    ],
    'string'               => ':attribute must be a string.',
    'timezone'             => ':attribute must be a valid zone.',
    'unique'               => ':attribute has already been taken.',
    'uploaded'             => ':attribute failed to upload.',
    'url'                  => ':attribute format is invalid.',

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
            'rule-name' => 'custom-message',
        ],
        'estimate_shipping_date' =>['after' => 'Please enter a date after the start date of aquaculture.'],
        'completed_date' =>['after' => 'Please enter a date after the start date of aquaculture.'],
        'start_date' =>['unique' => 'It is the already registered aquaculture start date.'],
        'ebi_bait_inventories_id' =>['required' => 'Does not exist.'],

    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'farm_name' => 'Farm name',
        'farm_image' => 'Farm image',
        'farm_name_en' => 'Farm name',
        'farm_image_en' => 'Farm image',
        'country_id' => 'Country id',
        'pond_name'=>'Pond aquaculture name',
        'pond_image_area'=>	'Pond aquaculture area',
        'water_amount'=> 'Amount of water',
        'pond_width'=> 'Width',
        'water_dept'=> 'Water depth',
        'pond_vertical_width'=>	'Vertical width',
        'farms_id'=> 'Farm id',
        'lat_long_se'=>	'Latitude and longitude (southeast)',
        'lat_long_ne'=> 'Latitude and longitude (northeast)',
        'lat_long_nw'=>	'Latitude and longitude (northwest)',
        'lat_long_sw'=>	'Latitude and longitude (southwest)',
        'tag1'=> 'Tag 1',
        'tag2'=> 'Tag 2',
        'tag3'=> 'Tag 3',
        'tag4'=> 'Tag 4',
        'amount' => 'Amount',
        'ebi_bait_inventories_id' => 'Type of bait (id of ebi_bait_inventories)',
        'baits_amount' => 'Baits amount',
        'pond_method'=>	'Aquaculture method',
        'delta_measure'=> 'Meas. Range Buffer',
        'created_at'=>'Creation date and time',
        'updated_user'=> 'Update user',
        'updated_at'=> 'Update date and time',
        'ebi_amount'=>	'Number of juvenile shrimp input',
        'area'=> 'Drawing placement number'	,
        'ammonia_values' => 'Ammonia',
        'copper_ion_values' => 'Copper ion concentration',
        'out_temp_values' => 'Temperature',
        'solution' => 'solution',
    ],
];
