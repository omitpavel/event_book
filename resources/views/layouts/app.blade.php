<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Reminder App</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/css/bootstrap.min.css" crossorigin="anonymous">

    @livewireStyles
</head>
<body>
    <div class="container">
        @yield('content')
    </div>
    @livewireScripts
</body>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.0/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
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
