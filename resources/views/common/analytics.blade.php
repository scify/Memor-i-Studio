@if(isset($_COOKIE[config('cookies_consent.cookie_prefix')
. 'cookies_consent_targeting']))
    @if(config('app.firebase_api_key'))
        <script type="module">
            // Import the functions you need from the SDKs you need
            import {initializeApp} from "https://www.gstatic.com/firebasejs/9.6.6/firebase-app.js";
            import {getAnalytics} from "https://www.gstatic.com/firebasejs/9.6.6/firebase-analytics.js";
            // TODO: Add SDKs for Firebase products that you want to use
            // https://firebase.google.com/docs/web/setup#available-libraries

            // Your web app's Firebase configuration
            // For Firebase JS SDK v7.20.0 and later, measurementId is optional
            const firebaseConfig = {
                apiKey: "{{ config('app.firebase_api_key') }}",
                authDomain: "memor-i-desktop.firebaseapp.com",
                databaseURL: "https://memor-i-desktop-default-rtdb.europe-west1.firebasedatabase.app",
                projectId: "memor-i-desktop",
                storageBucket: "memor-i-desktop.appspot.com",
                messagingSenderId: "{{ config('app.firebase_messaging_sender_id') }}",
                appId: "{{ config('app.firebase_app_id') }}",
                measurementId: "{{ config('app.firebase_measurement_id') }}"
            };
            // Initialize Firebase
            const app = initializeApp(firebaseConfig);
            const analytics = getAnalytics(app);
        </script>
    @endif
    @if(config('app.google_analytics_id'))
        <script>
            (function (i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

            ga('create', '{{ config('app.google_analytics_id') }}', 'auto');
            ga('set', 'anonymizeIp', true);
            ga('send', 'pageview');
        </script>
    @endif
@endif