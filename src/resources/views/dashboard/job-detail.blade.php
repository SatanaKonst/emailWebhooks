@include('chunks.header')

@if(empty($error))
    @include('dashboard.chunks.jobDetail')
@else
    @include('chunks.error')
@endif

@include('chunks.footer')
