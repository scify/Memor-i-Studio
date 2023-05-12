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

    'accepted'             => 'Il :attribute deve essere accettato.',
    'active_url'           => 'Il :attribute non è un URL valido.',
    'after'                => 'Il :attribute deve essere una data successiva al :date.',
    'after_or_equal'       => 'Il :attribute deve essere una data successiva o uguale al :date.',
    'alpha'                => 'Il :attribute può contenere solo lettere.',
    'alpha_dash'           => 'Il :attribute può contenere solo lettere, numeri e trattini',
    'alpha_num'            => 'Il :attribute può contenere solo lettere e numeri',
    'array'                => 'Il :attribute deve essere una tabella.',
    'before'               => 'Il :attribute deve essere una data precedente al :date.',
    'before_or_equal'      => 'Il :attribute deve essere una data precedente o uguale al :date.',
    'between'              => [
        'numeric' => 'Il :attribute deve essere tra :min e :max.',
        'file'    => 'Il :attribute deve essere tra :min e :max kilobytes.',
        'string'  => 'Il :attribute deve avere dai :min ai :max caratteri.',
        'array'   => 'Il :attribute deve avere tra :min e :max elementi.',
    ],
    'boolean'              => 'Il campo :attribute deve essere vero o falso.',
    'confirmed'            => 'La conferma dell\':attribute non corrisponde.',
    'date'                 => 'Il :attribute non è una data valida.',
    'date_format'          => 'Il :attribute non corrisponde al formato :format.',
    'different'            => 'Il :attribute e il :other devono essere differenti.',
    'digits'               => 'Il :attribute deve avere :digits cifre.',
    'digits_between'       => 'Il :attribute deve avere tra :min e :max cifre.',
    'dimensions'           => 'Il :attribute ha dimensioni di immagine non valide.',
    'distinct'             => 'Il campo :attribute ha un valore duplicato.',
    'email'                => 'Il :attribute deve essere un indirizzo email valido.',
    'exists'               => 'Il :attribute selezionato non è valido.',
    'file'                 => 'Il :attribute deve essere un file.',
    'filled'               => 'Il campo :attribute è obbligatorio.',
    'image'                => 'Il :attribute deve essere un\'immagine.',
    'in'                   => 'Il :attribute selezionato non è valido.',
    'in_array'             => 'Il campo :attribute non esiste in :other.',
    'integer'              => 'Il :attribute deve essere un numero intero.',
    'ip'                   => 'Il :attribute deve essere un valido indirizzo IP.',
    'json'                 => 'Il :attribute deve essere una valida stringa JSON.',
    'max'                  => [
        'numeric' => 'Il :attribute non può essere più grande di :max.',
        'file'    => 'Il :attribute non può essere più grande di :max kilobytes.',
        'string'  => 'Il :attribute non può essere più grande di :max caratteri.',
        'array'   => 'Il :attribute non può avere più :max elementi.',
    ],
    'mimes'                => 'Il :attribute deve essere un file di tipo :values.',
    'mimetypes'            => 'Il :attribute deve essere un file di tipo :values.',
    'min'                  => [
        'numeric' => 'Il :attribute deve essere almeno :min.',
        'file'    => 'Il :attribute deve essere almeno :min kilobytes.',
        'string'  => 'Il :attribute deve essere almeno di :min caratteri.',
        'array'   => 'Il :attribute deve avere almeno :min elementi.',
    ],
    'not_in'               => 'Il :attribute scelto non è valido.',
    'numeric'              => 'Il :attribute deve essere un numero.',
    'present'              => 'Il campo :attribute deve essere presente.',
    'regex'                => 'Il formato dell\':attribute non è valido.',
    'required'             => 'Il campo :attribute è obbligatorio.',
    'required_if'          => 'Il campo :attribute è obbligatorio quando :other è :value.',
    'required_unless'      => 'Il campo :attribute è obbligatorio a meno che :other è in :values.',
    'required_with'        => 'Il campo :attribute è obbligatorio quando I :values sono presenti.    ',
    'required_with_all'    => 'Il campo :attribute è obbligatorio quando I :values sono presenti.',
    'required_without'     => 'Il campo :attribute è obbligatorio quando I :values non sono presenti.',
    'required_without_all' => 'Il campo :attribute è obbligatorio quando alcun :values è presente.',
    'same'                 => 'Il :attribute e il :other devono corrispondere.',
    'size'                 => [
        'numeric' => 'Il :attribute deve essere :size.',
        'file'    => 'Il :attribute deve essere :size kilobytes.',
        'string'  => 'Il :attribute deve essere :size caratteri.',
        'array'   => 'Il :attribute deve contenere :size elementi.',
    ],
    'string'               => 'Il :attribute deve essere una stringa.',
    'timezone'             => 'Il :attribute deve essere una zona valida.',
    'unique'               => 'Il :attribute è gia in uso.',
    'uploaded'             => 'Il :attribute non ha eseguito l\'upload.',
    'url'                  => 'Il formato del :attribute non è valido.',

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
        'card.*.image' => [
            'required' => 'Ogni carta deve avere un\'immagine.',
        ],
        'card.*.sound' => [
            'required' => 'Ogni carta deve avere un file audio.',
        ]
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

    'attributes' => [],

];
