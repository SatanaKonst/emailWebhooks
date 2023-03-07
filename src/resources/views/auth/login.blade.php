@include('chunks.header')

<section class="mt-5">
    <div class="row">
        <div class="flex-column">
            <form method="post" action="/login">
                @csrf <!-- {{ csrf_field() }} -->
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email адрес</label>
                    <input type="email" class="form-control" aria-describedby="emailHelp" name="email">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Пароль</label>
                    <input type="password" class="form-control" name="password">
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" name="remember">
                    <label class="form-check-label">Запомнить меня</label>
                </div>
                <button type="submit" class="btn btn-primary">Войти</button>
            </form>
        </div>
    </div>
</section>


@include('chunks.footer')
