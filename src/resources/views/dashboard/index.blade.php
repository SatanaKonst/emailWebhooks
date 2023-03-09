@include('chunks.header')

@if(!empty($jobs))
    @include('dashboard.chunks.jobList')
@endif

@include('chunks.footer')
