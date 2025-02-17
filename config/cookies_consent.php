<?php

return [
    /**
     * This prefix will be applied when setting and getting all cookies.
     * If not set, the cookies will not be prefixed.
     * If set, a good strategy is to also add a trailing underscore "_", that will be added between the field value, and each cookie.
     * For example, if `cookie_prefix` is set to `my_app_`, then the cookies will be stored in a JSON object with the key `my_app_cookies_consent_selection`.
     * Example:
     *
     * {
     *    "my_app_cookies_consent_selection": {
     *       "strictly_necessary": true,
     *      "performance": false,
     *     "targeting": false
     *   }
     * }
     */
    'cookie_prefix' => 'memori_studio_',
    'display_floating_button' => true, // Set to false to display the footer link instead
    'hide_floating_button_on_mobile' => false, // Set to true to hide the floating button on mobile
    'use_separate_page' => false, // Set to true to use a separate page for cookies explanation
    /*
    |--------------------------------------------------------------------------
    | Editor
    |--------------------------------------------------------------------------
    |
    | Choose your preferred cookies to be shown. You can add more cookies as desired.
    |
    | Built-in: "strictly_necessary"
    |
    */
    'cookies' => [
        'strictly_necessary' => [
            [
                'name' => 'memori_studio_cookies_consent',
                'description' => 'cookies_consent::messages.cookie_cookies_consent_description',
                'duration' => 'cookies_consent::messages.years',
                'duration_count' => 1,
                'policy_external_link' => null,
            ],
            [
                'name' => 'XSRF-TOKEN',
                'description' => 'cookies_consent::messages.cookie_xsrf_token_description',
                'duration' => 'cookies_consent::messages.hours',
                'duration_count' => 2,
                'policy_external_link' => null,
            ],
            [
                'name' => 'laravel_session',
                'description' => 'cookies_consent::messages.cookie_laravel_session_description',
                'duration' => 'cookies_consent::messages.hours',
                'duration_count' => 2,
                'policy_external_link' => null,
            ],
        ],
        'targeting' => [
            [
                'name' => '_ga',
                'description' => 'cookies.google_analytics._ga_description',
                'duration' => 'cookies_consent::messages.years',
                'duration_count' => 2,
                'policy_external_link' => 'https://policies.google.com/privacy?hl=en-US',
            ],
            [
                'name' => '_gid',
                'description' => 'cookies.google_analytics._gid_description',
                'duration' => 'cookies_consent::messages.days',
                'duration_count' => 1,
                'policy_external_link' => 'https://policies.google.com/privacy?hl=en-US',
            ],
            [
                'name' => '_gat',
                'description' => 'cookies.google_analytics._gat_description',
                'duration' => 'cookies_consent::messages.minutes',
                'duration_count' => 1,
                'policy_external_link' => 'https://policies.google.com/privacy?hl=en-US',
            ],
            [
                'name' => 'bcookie',
                'description' => 'cookies.linkedin.bcookie_description',
                'duration' => 'cookies_consent::messages.years',
                'duration_count' => 2,
                'policy_external_link' => 'https://www.linkedin.com/legal/privacy-policy',
            ],
            [
                'name' => '_fbp',
                'description' => 'cookies.facebook._fbp_description',
                'duration' => 'cookies_consent::messages.months',
                'duration_count' => 3,
                'policy_external_link' => 'https://www.facebook.com/policy/cookies/',
            ],
            [
                'name' => '_gcl_au',
                'description' => 'cookies.google_ads._gcl_au_description',
                'duration' => 'cookies_consent::messages.months',
                'duration_count' => 2,
                'policy_external_link' => 'https://policies.google.com/privacy?hl=en-US',
            ],
            [
                'name' => 'guest_id',
                'description' => 'cookies.twitter.guest_id_description',
                'duration' => 'cookies_consent::messages.years',
                'duration_count' => 2,
                'policy_external_link' => 'https://twitter.com/en/privacy',
            ],
            [
                'name' => 'guest_id_ads',
                'description' => 'cookies.twitter.guest_id_ads_description',
                'duration' => 'cookies_consent::messages.years',
                'duration_count' => 2,
                'policy_external_link' => 'https://twitter.com/en/privacy',
            ],
            [
                'name' => 'guest_id_marketing',
                'description' => 'cookies.twitter.guest_id_marketing_description',
                'duration' => 'cookies_consent::messages.years',
                'duration_count' => 2,
                'policy_external_link' => 'https://twitter.com/en/privacy',
            ],
            [
                'name' => 'li_gc',
                'description' => 'cookies.linkedin.li_gc_description',
                'duration' => 'cookies_consent::messages.years',
                'duration_count' => 2,
                'policy_external_link' => 'https://www.linkedin.com/legal/privacy-policy',
            ],
            [
                'name' => 'lidc',
                'description' => 'cookies.linkedin.lidc_description',
                'duration' => 'cookies_consent::messages.day',
                'duration_count' => 1,
                'policy_external_link' => 'https://www.linkedin.com/legal/privacy-policy',
            ],
            [
                'name' => 'muc_ads',
                'description' => 'cookies.twitter.muc_ads_description',
                'duration' => 'cookies_consent::messages.years',
                'duration_count' => 2,
                'policy_external_link' => 'https://twitter.com/en/privacy',
            ],
            [
                'name' => 'personalization_id',
                'description' => 'cookies.twitter.personalization_id_description',
                'duration' => 'cookies_consent::messages.years',
                'duration_count' => 2,
                'policy_external_link' => 'https://twitter.com/en/privacy',
            ],
        ],
    ],
    'enabled' => [
        'strictly_necessary',
    ],
    'required' => ['strictly_necessary'],
    /*
     * Set the cookie duration in days.  Default is 365 days.
     */
    'cookie_lifetime' => 365,
];