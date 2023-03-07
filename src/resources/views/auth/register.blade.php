@include('chunks.header')

<section class="mt-5">
    <div class="row">
        <div class="flex-column">
            <form method="post" action="/register">
                @csrf <!-- {{ csrf_field() }} -->
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Имя</label>
                    <input type="text" class="form-control" aria-describedby="emailHelp" name="name">
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email адрес</label>
                    <input type="email" class="form-control" aria-describedby="emailHelp" name="email">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Пароль</label>
                    <input type="password" class="form-control" name="password">
                </div>
                <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
            </form>
        </div>
    </div>
</section>


@include('chunks.footer')
