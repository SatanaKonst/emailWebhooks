<div class="row mb-4">
    <div class="container-fluid">
        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                data-bs-target="#addJobRuleModal">
            Добавить
        </button>
    </div>
</div>

<div class="row">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <form action="/dashboard/updateJob" method="post" class="w-100">
                    @csrf
                    <input type="hidden" name="redirect" value="/dashboard/{{$job->id}}">
                    <input type="hidden" name="job_id" value="{{$job->id}}">
                    <div class="input-group">
                        <input type="text" class="form-control" name="title" value="{{ $job->title }}">
                        <button class="btn btn-success" type="submit">
                            Переименовать
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-body">
                @if(!empty($job->last_run))
                    <h5 class="card-title">Последний запуск задания: {{ date('d.m.Y H:i:s', $job->last_run) }}</h5>
                @endif
                @if(!empty($job->rules))

                    <div class="accordion mb-3" id="accordionRule">
                        @foreach($job->rules as $index=>$rule)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <div class="btn-group w-100">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapse{{ $rule->id }}"
                                                aria-expanded="{{ $index===0 }}"
                                                aria-controls="collapseOne">
                                            {{ $rule->sender }}
                                        </button>
                                        <form action="/dashboard/removeRule" method="post">
                                            @csrf
                                            <input type="hidden" name="redirect" value="/dashboard/{{$job->id}}">
                                            <input type="hidden" name="rule_id" value="{{$rule->id}}">
                                            <button type="submit" class="btn btn-danger">
                                                Удалить
                                            </button>
                                        </form>
                                    </div>
                                </h2>
                                <div id="collapse{{ $rule->id }}" class="accordion-collapse collapse"
                                     aria-labelledby="headingOne" data-bs-parent="#accordionRule">
                                    <div class="accordion-body">
                                        <form action="/dashboard/updateRule" method="post">
                                            @csrf
                                            <input type="hidden" name="redirect" value="/dashboard/{{$job->id}}">
                                            <input type="hidden" name="rule_id" value="{{$rule->id}}">

                                            <div class="mb-3">
                                                <label class="form-label w-100">
                                                    Заголовок письма
                                                    <input type="text" class="form-control" name="theme"
                                                           value="{{ $rule->theme }}">
                                                </label>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label w-100">
                                                    Отправитель письма
                                                    <input type="email" class="form-control" name="sender"
                                                           value="{{ $rule->sender }}">
                                                </label>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label w-100">
                                                    Регулярное выражение
                                                    <input type="text" class="form-control" name="regex"
                                                           value="{{ $rule->regex }}">
                                                </label>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label w-100">
                                                    URL на который отправить запрос
                                                    <input type="text" class="form-control" name="webhook_ulr"
                                                           value="{{ $rule->webhook_url }}">
                                                </label>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label w-100">
                                                    Метод отправки (GET,POST)
                                                    <select class="form-select" aria-label="Webhook метод"
                                                            name="webhook_method">
                                                        <option value=""
                                                                @if(empty($rule->webhook_method)) selected @endif></option>
                                                        <option value="GET"
                                                                @if($rule->webhook_method==='GET') selected @endif>
                                                            GET
                                                        </option>
                                                        <option value="POST"
                                                                @if($rule->webhook_method==='POST') selected @endif>
                                                            POST
                                                        </option>
                                                    </select>
                                                </label>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label w-100">
                                                    Данные которые надо отправить в запросе <br>
                                                    <ul>
                                                        <li>GET - параметры запроса</li>
                                                        <li>POST - json</li>
                                                    </ul>
                                                    <textarea name="webhook_data" cols="30" rows="10"
                                                              class="form-control">
                                                            {{ $rule->webhook_data }}
                                                        </textarea>
                                                </label>
                                            </div>

                                            <button type="submit" class="btn btn-success">Сохранить</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="addJobRuleModal" tabindex="-1" aria-labelledby="addJobRuleModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/dashboard/addJobRule" method="POST">
                @csrf <!-- {{ csrf_field() }} -->
                <input type="hidden" name="redirect" value="/dashboard/{{$job->id}}">
                <input type="hidden" name="jobs_id" value="{{$job->id}}">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Добавить Правило</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <label>
                            Заголовок письма
                            <input type="text" class="form-control" name="theme" value=""
                                   placeholder="Заголовок письма">
                        </label>
                    </div>
                    <div class="row mb-2">
                        <label>
                            Отправитель
                            <input type="email" class="form-control" name="sender" value="" placeholder="Отправитель">
                        </label>
                    </div>
                    <div class="row mb-2">
                        <label>
                            Regex для выборки данных из письма
                            <a href="#" class="btn btn-secondary btn-sm" data-bs-toggle="tooltip"
                               title="Ищет по заголовку, отправителю и контенту">?
                            </a>
                            <input type="text" class="form-control" name="regex" value=""
                                   placeholder="Regex для поиска">
                        </label>
                    </div>

                    <div class="row mb-2">
                        <label>
                            WebHook Метод
                            <select name="webhook_method" class="form-control">
                                <option value="GET">GET</option>
                                <option value="POST">POST</option>
                            </select>
                        </label>
                    </div>

                    <div class="row mb-2">
                        <label>
                            Url на который отправить запрос
                            <input type="text" class="form-control" name="webhook_url" value=""
                                   placeholder="Url на который отправить запрос">
                        </label>
                    </div>

                    <div class="row mb-2">
                        <label>
                            Данные которые надо отправить (Json)
                            <a href="#" class="btn btn-secondary btn-sm"
                               data-bs-toggle="tooltip"
                               title="Для постановки данных из regex используйте плейсхолдер #REGEX_DATA#">?
                            </a>
                            <textarea name="webhook_data"
                                      class="form-control"
                                      cols="30"
                                      rows="10"
                                      placeholder="Данные которые надо отправить (json)"></textarea>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>
