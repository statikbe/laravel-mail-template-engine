@foreach($blocks as $block)
{{--    TODO block based mail loading--}}
    @include($design.'.blocks.'.$block['type'], ['data' => $block['data']])
@endforeach