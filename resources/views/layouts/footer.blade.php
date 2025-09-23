<!-- Main Footer -->
<footer class="main-footer">
    <!-- To the right -->
   
</footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="{{asset('public/assets/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('public/assets/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('public/assets/dist/js/adminlte.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/1.1.2/js/bootstrap-multiselect.min.js" integrity="sha512-lxQ4VnKKW7foGFV6L9zlSe+6QppP9B2t+tMMaV4s4iqAv4iHIyXED7O+fke1VeLNaRdoVkVt8Hw/jmZ+XocsXQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('public/assets/select2.min.js') }}"></script>
<script>
    @if(Session::has('success'))
    toastr.success("{{ Session::get('success') }}");
    @endif


    @if(Session::has('info'))
    toastr.info("{{ Session::get('info') }}");
    @endif


    @if(Session::has('warning'))
    toastr.warning("{{ Session::get('warning') }}");
    @endif


    @if(Session::has('error'))
    toastr.error("{{ Session::get('error') }}");
    @endif
</script>
</body>

</html>