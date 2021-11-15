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

    'accepted'             => 'Το :attribute πρέπει να γίνει αποδεκτό.',
    'active_url'           => 'Το :attribute δεν είναι έγκυρη διεύθυνση URL.',
    'after'                => 'Το :attribute δεν πρέπει να είναι ημερομηνία μετά τη :date.',
    'after_or_equal'       => 'Το :attribute πρέπει να είναι μια ημερομηνία μετά ή ίδια με τη :date.',
    'alpha'                => 'Το :attribute μπορεί να περιέχει μόνο γράμματα.',
    'alpha_dash'           => 'Το :attribute μπορεί να περιέχει μόνο γράμματα, αριθμούς, και παύλες.',
    'alpha_num'            => 'Το :attribute μπορεί να περιέχει μόνο γράμματα και αριθμούς.',
    'array'                => 'Το :attribute πρέπει να είναι πίνακας.',
    'before'               => 'Το :attribute πρέπει να είναι ημερομηνία πριν τη :date.',
    'before_or_equal'      => 'Το :attribute πρέπει να είναι μια ημερομηνία πριν ή ίδια με τη :date.',
    'between'              => [
        'numeric' => 'Το :attribute πρέπει να είναι ανάμεσα σε :min και :max.',
        'file'    => 'Το :attribute πρέπει να είναι ανάμεσα σε :min και :max kilobytes.',
        'string'  => 'Το :attribute πρέπει να είναι ανάμεσα σε :min και :max χαρακτήρες.',
        'array'   => 'Το :attribute πρέπει να είναι ανάμεσα σε :min και :max στοιχεία.'
    ],
    'boolean'              => 'Το πεδίο :attribute πρέπει να είναι σωστό ή λάθος.',
    'confirmed'            => 'Η επιβεβαίωση  :attribute δεν είναι ίδια.',
    'date'                 => 'Το :attribute δεν είναι έγκυρη ημερομηνία.',
    'date_format'          => 'Το :attribute δεν ταιριάζει στη μορφή :format.',
    'different'            => 'Το :attribute και :other πρέπει να είναι διαφορετικά.',
    'digits'               => 'Το :attribute πρέπει να είναι :digits ψηφία.',
    'digits_between'       => 'Το :attribute πρέπει να είναι ανάμεσα σε :min και :max ψηφία.',
    'dimensions'           => 'Το :attribute έχει μη έγκυρες διαστάσεις εικόνας.',
    'distinct'             => 'Το πεδίο :attribute έχει διπλή τιμή.',
    'email'                => 'Το :attribute πρέπει να είναι έγκυρη διεύθυνση email.',
    'exists'               => 'Το επιλεγμένο :attribute δεν είναι έγκυρο.',
    'file'                 => 'Το :attribute πρέπει να είναι αρχείο.',
    'filled'               => 'Το πεδίο :attribute απαιτείται.',
    'image'                => 'Το :attribute πρέπει να είναι εικόνα.',
    'in'                   => 'Το επιλεγμένο :attribute δεν είναι έγκυρο.',
    'in_array'             => 'Το πεδίο :attribute δεν υπάρχει στο :other.',
    'integer'              => 'Το :attribute πρέπει να είναι ακέραιος αριθμός.',
    'ip'                   => 'Το :attribute πρέπει να είναι έγκυρη διεύθυνση IP.',
    'json'                 => 'Το :attribute πρέπει να είναι μια έγκυρη συμβολοσειρά JSON.',
    'max'                  => [
        'numeric' => 'Το :attribute δε μπορεί να είναι μεγαλύτερο από :max.',
        'file'    => 'Το :attribute δε μπορεί να είναι μεγαλύτερο από :max kilobytes.',
        'string'  => 'Το :attribute δε μπορεί να είναι μεγαλύτερο από :max χαρακτήρες.',
        'array'   => 'Το :attribute δε μπορεί να έχει περισσότερα από :max στοιχεία.',
    ],
    'mimes'                => 'Το :attribute πρέπει να είναι αρχείο τύπου: :values.',
    'mimetypes'            => 'Το :attribute πρέπει να είναι αρχείο τύπου: :values.',
    'min'                  => [
        'numeric' => 'Το :attribute πρέπει να είναι τουλάχιστον :min.',
        'file'    => 'Το :attribute πρέπει να είναι τουλάχιστον :min kilobytes.',
        'string'  => 'Το :attribute πρέπει να είναι τουλάχιστον :min χαρακτήρες.',
        'array'   => 'Το :attribute πρέπει να έχει τουλάχιστον :min στοιχεία.',
    ],
    'not_in'               => 'Το επιλεγμένο :attribute δεν είναι έγκυρο.',
    'numeric'              => 'Το :attribute πρέπει να είναι αριθμός.',
    'present'              => 'Το πεδίο :attribute πρέπει να υπάρχει.',
    'regex'                => 'Η μορφή :attribute δεν είναι έγκυρη.',
    'required'             => 'Το πεδίο :attribute είναι απαραίτητο.',
    'required_if'          => 'Το πεδίο :attribute είναι απαραίτητο όταν το :other είναι :value.',
    'required_unless'      => 'Το πεδίο :attribute είναι απαραίτητο εκτός αν το :other είναι σε :values.',
    'required_with'        => 'Το πεδίο :attribute είναι απαραίτητο όταν υπάρχουν :values.',
    'required_with_all'    => 'Το πεδίο :attribute είναι απαραίτητο όταν υπάρχουν :values',
    'required_without'     => 'Το πεδίο :attribute είναι απαραίτητο όταν δεν υπάρχουν :values.',
    'required_without_all' => 'Το πεδίο :attribute είναι απαραίτητο όταν δεν υπάρχουν καθόλου :values.',
    'same'                 => 'Το :attribute και :other πρέπει να ταιριάζουν.',
    'size'                 => [
        'numeric' => 'Το :attribute πρέπει να είναι :size.',
        'file'    => 'Το :attribute πρέπει να είναι :size kilobytes.',
        'string'  => 'Το :attribute πρέπει να είναι :size χαρακτήρες.',
        'array'   => 'Το :attribute πρέπει να περιέχει :size στοιχεία.',
    ],
    'string'               => 'Το :attribute πρέπει να είναι συμβολοσειρά.',
    'timezone'             => 'Το :attribute πρέπει να είναι μια έγκυρη ζώνη.',
    'unique'               => 'Το :attribute χρησιμοποιείται ήδη.',
    'uploaded'             => 'Η φόρτωση του :attribute απέτυχε.',
    'url'                  => 'Η μορφή του :attribute δεν είναι έγκυρη.',

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
            'rule-name' => 'προσαρμοσμένο-μήνυμα',
        ],
        'card.*.image' => [
            'required' => 'Κάθε κάρτα θα έπρεπε να έχει μια εικόνα',
        ],
        'card.*.sound' => [
            'required' => 'Κάθε εικόνα θα έπρεπε να έχει ένα αρχείο ήχου',
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
