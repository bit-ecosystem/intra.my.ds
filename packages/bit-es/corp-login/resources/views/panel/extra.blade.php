<!-- The Slogan -->
<div id="slogan">
    <h2>{{ __('Company.Slogan') }}</h2>
</div>

<style>
    body {
        background: url('{{ asset('images/atm.jpg') }}') no-repeat center center fixed;
        background-size: cover;
    }

    @media screen and (min-height: 878px) and (min-width: 1024px) {
        main {
            position: absolute;
            left: 100px;
            top: 50px;
        }

        #slogan {
            position: fixed;
            left: 275px;
            bottom: 25px;
            color: #80B5F2;
            font-family: Arial, sans-serif;
            font-size: 3em;
            font-weight: bold;
            z-index: 10;
            width: 700px;
            white-space: normal;
            text-align: center;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Redirects the logo/header to the dashboard when clicked
        const headers = document.querySelectorAll('.fi-simple-header');
        headers.forEach(header => {
            header.addEventListener('click', function() {
                window.location.href = "{{ route('filament.staff.pages.dashboard') }}";
            });
        });
    });
</script>