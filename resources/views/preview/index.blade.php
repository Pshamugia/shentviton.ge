@extends('layouts.app')

@section('content')
    <h1>Design Preview</h1>

    <div class="row">
        <div class="col-md-6">
            <h2>Front Design</h2>
            <img id="frontImage" class="img-fluid" alt="Front Design">
        </div>

        <div class="col-md-6">
            <h2>Back Design</h2>
            <img id="backImage" class="img-fluid" alt="Back Design">
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let key = "{{ $key }}";

            let frontImage = localStorage.getItem(`${key}.front_image`);
            let backImage = localStorage.getItem(`${key}.back_image`);

            if (frontImage) {
                document.getElementById("frontImage").src = frontImage;
            } else {
                document.getElementById("frontImage").alt = "Front design not found";
            }

            if (backImage) {
                document.getElementById("backImage").src = backImage;
            } else {
                document.getElementById("backImage").alt = "Back design not found";
            }
        });
    </script>
@endsection
