<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Reminder App</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    @livewireStyles
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
    @livewireScripts
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>






<script>
    $(document).ready(function () {
        // Listen for the 'open-modal' event from Livewire
        Livewire.on('open-modal', function (modalId) {
            if (modalId && modalId !== '') {
                const $modal = $('#' + modalId);
                if ($modal.length) {
                    $modal.modal({
                        backdrop: 'static', // Prevent closing when clicking outside the modal
                        keyboard: false      // Prevent closing with the escape key
                    });
                    $modal.modal('show');
                } else {
                    console.error(`Modal with ID #${modalId} not found.`);
                }
            } else {
                console.error('Invalid modal ID.');
            }
        });

        // Listen for the 'close-modal' event from Livewire
        Livewire.on('close-modal', function (modalId) {
            if (modalId && modalId !== '') {
                const $modal = $('#' + modalId);
                if ($modal.length) {
                    $modal.modal('hide');

                    // Ensure the modal is fully disposed of after hiding
                    $modal.on('hidden.bs.modal', function () {
                        $(this).modal('dispose');
                    });
                } else {
                    console.error(`Modal with ID #${modalId} not found.`);
                }
            } else {
                console.error('Invalid modal ID.');
            }
        });
    });

    function handleFileChange(event) {
        event.stopPropagation();
    }

</script>




</html>
