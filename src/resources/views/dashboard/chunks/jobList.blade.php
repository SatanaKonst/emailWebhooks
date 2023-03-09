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
