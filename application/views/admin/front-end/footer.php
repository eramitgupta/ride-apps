<div class="mt-5 text-center">
    <div>
        <p>© <script>
                document.write(new Date().getFullYear())
            </script> All Right Reserved <i class="mdi mdi-heart text-danger"></i> Developed By <a href="https://swasoftech.com/" target="_black">Swasoftech Pvt. Ltd.</a></p>
    </div>
</div>

</div>
</div>
</div>
</div>
<!-- end account-pages -->

<script>
    function sAlert(icon, title, url = '') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
        if(url!=""){
            Toast.fire({
                icon: icon,
                title: title,
            }).then((Toast) => {
                if (Toast) {
                    window.location.href = url;
                }
            });
        } else {
            Toast.fire({
                icon: icon,
                title: title,
            })
        }
    }
</script>
<!-- JAVASCRIPT -->
<script src="<?= base_url('public/assets/libs/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('public/js/front-end.js') ?>"></script>
<script src="<?= base_url('public/assets/libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('public/assets/libs/metismenu/metisMenu.min.js'); ?>"></script>
<script src="<?= base_url('public/assets/libs/simplebar/simplebar.min.js'); ?>"></script>
<script src="<?= base_url('public/assets/libs/node-waves/waves.min.js') ?>"></script>
<!-- App js -->
<script src="<?= base_url('public/assets/js/app.js'); ?>"></script>
</body>

</html>