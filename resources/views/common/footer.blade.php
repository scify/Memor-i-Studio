<script src="{{ mix('js/manifest.js') }}"></script>
<script src="{{ mix('js/vendor.js') }}"></script> {{-- Vendor libraries like jQuery, bootstrap --}}
<script src="{{ mix('js/libs.js') }}"></script> {{-- Vendor libraries like jQuery, bootstrap --}}
<script src="{{ mix('js/app.js') }}"></script> {{-- our application common code --}}
<script src="{{ mix('js/equivalenceSetsController.js') }}"></script>
@include('common.analytics')
@stack('scripts')
