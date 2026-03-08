<div id="slogan">
    <h2>{{ __('Company.Slogan') }}</h2>
</div>
<style>
    body {
        background: url('{{ asset('images/atm.jpg') }}') no-repeat center center fixed;
        background-size: cover;
    }

    */ #slogan {
        position: fixed;
        left: 100px; 
        margin-top: 50px;
        color: bisque;
        font-family: Arial;
        font-size: 2em;
        font-weight: bold;
        text-shadow: #3f6212 2px 2px 5px;
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const headers = document.querySelectorAll('.fi-simple-header');
        headers.forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', function() {
                window.location.href = "{{ route('filament.staff.pages.dashboard') }}";
            });
        });
    });
</script>