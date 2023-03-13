<div class="row mb-4">
    <div class="container-fluid">
        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addJobModal">
            Добавить
        </button>
    </div>
</div>

<div class="row">
    <div class="container-fluid">
        <ol class="list-group list-group-numbered">
            @foreach($jobs as $job)
                <li class="list-group-item d-flex justify-content-between align-items-start">
                    <div class="ms-2 me-auto">
                        <a href="/dashboard/{{$job->id}}/">
                            <div class="fw-bold">
                                {{ $job->title }}
                            </div>
                        </a>
                    </div>
                    <span class="badge bg-primary rounded-pill">
                    Правил: {{ count($job->rules) }}
                </span>
                </li>
            @endforeach
        </ol>
    </div>
</div>

<div class="modal fade" id="addJobModal" tabindex="-1" aria-labelledby="addJobModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/dashboard/addJob" method="POST">
                @csrf <!-- {{ csrf_field() }} -->
                <input type="hidden" name="redirect" value="/dashboard">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Добавить задачу</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label>
                        Название задачи
                        <input type="text"
                               class="form-control"
                               name="title"
                               value=""
                               placeholder="Название задачи"
                               required>
                    </label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>
