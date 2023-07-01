{{-- <div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
    <div class="toast-header">
        <strong class="mr-auto">Notification</strong>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body">
        <span id="toast-message"></span>
    </div>
</div>

<script>
    function showToast(message, type) {
        var toastElement = $('#toast');
        var toastMessage = $('#toast-message');

        toastElement.removeClass('bg-success bg-danger bg-warning').addClass('bg-' + type);
        toastMessage.text(message);

        var toast = new bootstrap.Toast(toastElement[0]);
        toast.show();
    }

</script>

<style>
    .toast {
        position: fixed;
        top: 80%;
        right: 30px;
        z-index: 999;
        max-width: 300px;
    }
</style> --}}

<div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
    <div class="toast-header">
        <strong class="mr-auto">Notification</strong>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body">
        <span id="toast-message"></span>
    </div>
</div>

<script>
    function showToast(message, type) {
        var toastElement = $('#toast');
        var toastMessage = $('#toast-message');

        toastElement.removeClass('bg-success bg-danger bg-warning').addClass('bg-' + type);
        toastMessage.html(message);

        var toast = new bootstrap.Toast(toastElement[0]);
        toast.show();
    }

</script>

<style>
    .toast {
        position: fixed;
        top: 10%;
        right: 30px;
        z-index: 999;
        max-width: 300px;
        color: white;
        font-size: 15px;
        text-shadow: 0.5px 0.5px 0.5px black;
    }
</style>
