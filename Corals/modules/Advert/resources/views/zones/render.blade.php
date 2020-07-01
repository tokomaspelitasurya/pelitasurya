@isset($zone)
    {!! \Advert::getRandomWeightedBanner($zone) !!}

    <script>
        if (typeof embed !== 'undefined') {
            window.onload = function () {
                var embedChild = new embed.Child();
                embedChild.sendHeight();
            }
        }

    </script>
@else
    <p class="text-center text-danger">
        <strong>
            Zone [{{ @$zone_key }}] Cannot be found
        </strong>
    </p>
@endisset