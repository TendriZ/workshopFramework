<script src="{{ asset('template/vendors/js/vendor.bundle.base.js') }}"></script>

<script src="{{ asset('template/vendors/chart.js/Chart.min.js') }}"></script>
<script src="{{ asset('template/vendors/progressbar.js/progressbar.min.js') }}"></script>
<script src="{{ asset('template/vendors/jvectormap/jquery-jvectormap.min.js') }}"></script>
<script src="{{ asset('template/vendors/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<script src="{{ asset('template/vendors/owl-carousel-2/owl.carousel.min.js') }}"></script>
<script src="{{ asset('template/js/jquery.cookie.js') }}" type="text/javascript"></script>

<script src="{{ asset('template/js/off-canvas.js') }}"></script>
<script src="{{ asset('template/js/hoverable-collapse.js') }}"></script>
<script src="{{ asset('template/js/misc.js') }}"></script>
<script src="{{ asset('template/js/settings.js') }}"></script>
<script src="{{ asset('template/js/todolist.js') }}"></script>

<script>
function submitWithSpinner(btn) {
    var form = btn.closest('form');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
    form.submit();
}

function deleteWithSpinner(btn) {
    if (!confirm('Yakin ingin menghapus data ini?')) return;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
    btn.closest('form').submit();
}
</script>

<script src="{{ asset('template/js/dashboard.js') }}"></script>
