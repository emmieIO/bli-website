@props([
    'type',
    'message'
])

@if ($type && $message)
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            if (window.notyf) {
                window.notyf.open({
                    type: @json($type),
                    message: @json($message)
                });
            } else {
                console.warn("Notyf is not initialized.");
            }
        });
    </script>
@endif
