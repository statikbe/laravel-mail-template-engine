@extends($design.'.mail')

@section('content')
    <!-- START CENTERED WHITE CONTAINER -->
    <table role="presentation" class="main">
        <!-- START MAIN CONTENT AREA -->
        <tr>
            <td class="wrapper">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td>
                            {!! $content !!}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <!-- END MAIN CONTENT AREA -->
    </table>
    <!-- END CENTERED WHITE CONTAINER -->
@endsection

@section('footer')
    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
        {{--<tr>
            <td class="content-block">
                <span class="apple-link">Company Inc, 3 Abbey Road, San Francisco CA 94102</span>
                <br> Don't like these emails? <a href="#">Unsubscribe</a>.
            </td>
        </tr>--}}
        <tr>
            <td class="content-block powered-by">
                Powered by <a href="https://github.com/statikbe/laravel-mail-template-engine">Laravel Mail Template Engine</a>.
            </td>
        </tr>
    </table>
@endsection