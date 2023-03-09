<form action="">
    <div class="card">
        <div class="card-header">
            <div class="input-group">
                <input type="text" class="form-control" value="{{ $job->title }}">
                <button class="btn btn-success" type="button">
                    Переименовать
                </button>
            </div>
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
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $rule->id }}" aria-expanded="{{ $index===0 }}"
                                        aria-controls="collapseOne">
                                    {{ $rule->regex }}
                                </button>
                            </h2>
                            <div id="collapse{{ $rule->id }}" class="accordion-collapse collapse show"
                                 aria-labelledby="headingOne" data-bs-parent="#accordionRule">
                                <div class="accordion-body">
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
                                                   value="{{ $rule->webhook_ulr }}">
                                        </label>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label w-100">
                                            Метод отправки (GET,POST)
                                            <select class="form-select" aria-label="Webhook метод"
                                                    name="webhook_method">
                                                <option value=""
                                                        @if(empty($rule->webhook_method)) selected @endif></option>
                                                <option value="GET" @if($rule->webhook_method==='GET') selected @endif>
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
                                            <textarea name="webhook_data" cols="30" rows="10" class="form-control">
                                                {{ $rule->webhook_data }}
                                            </textarea>
                                        </label>
                                    </div>
                                    <a href="#" class="btn btn-success">Сохранить</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            @endif
        </div>
    </div>
</form>
