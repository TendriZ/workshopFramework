<!-- plugins:js -->
<script src="{{ asset('template/vendors/js/vendor.bundle.base.js') }}"></script>
<!-- endinject -->

<!-- Plugin js for this page -->
<script src="{{ asset('template/vendors/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('template/vendors/progressbar.js/progressbar.min.js') }}"></script>
<script src="{{ asset('template/vendors/jvectormap/jquery-jvectormap.min.js') }}"></script>
<script src="{{ asset('template/vendors/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<script src="{{ asset('template/vendors/owl-carousel-2/owl.carousel.min.js') }}"></script>
<script src="{{ asset('template/js/jquery.cookie.js') }}" type="text/javascript"></script>
<!-- End plugin js for this page -->

<!-- inject:js -->
<script src="{{ asset('template/js/off-canvas.js') }}"></script>
<script src="{{ asset('template/js/hoverable-collapse.js') }}"></script>
<script src="{{ asset('template/js/misc.js') }}"></script>
<script src="{{ asset('template/js/settings.js') }}"></script>
<script src="{{ asset('template/js/todolist.js') }}"></script>
<!-- endinject -->

<!-- Global: Submit with Spinner (Studi Kasus 1) -->
<script>
function submitWithSpinner(btn) {
    var form = btn.closest('form');
    // Cek HTML5 validity
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    // Ubah button jadi spinner, disable untuk cegah double submit
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
    // Submit form
    form.submit();
}
</script>

<!-- Custom js for this page -->
<script src="{{ asset('template/js/dashboard.js') }}"></script>
<!-- End custom js for this page -->
