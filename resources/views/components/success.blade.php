<div style="z-index: 20;" class="col d-flex justify-content-center position-fixed animate__animated animate__slideInLeft">
    @if (session()->has('success'))
        <div class="alert alert-success badge" id="alert">
            {{ session('success') }}
            <a class="fs-5 ms-3 text-decoration-none" style="cursor: pointer;" data-bs-dismiss="alert">x</a>
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger badge text-dark" id="alert">
            {{ session('error') }}
            <a class="fs-5 ms-3 text-decoration-none" style="cursor: pointer;" data-bs-dismiss="alert">x</a>
        </div>
    @endif
</div>
