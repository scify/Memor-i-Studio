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
        console.log(app);
        console.log(analytics);
    </script>
@endif
