<table id="background-table" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody>
    <tr>
        <td align="center">
            <table class="w640" border="0" cellpadding="0" cellspacing="0" width="640">
                <tbody>
                <tr class="large_only">
                    <td class="w640" height="20" width="640"></td>
                </tr>
                <tr class="mobile_only">
                    <td class="w640" height="10" width="640"></td>
                </tr>
                <tr class="mobile_only">
                    <td class="w640" height="10" width="640"></td>
                </tr>
                <tr class="mobile_only">
                    <td class="w640" align="center" width="640">
                        <table class="w640" border="0" cellpadding="0" cellspacing="0" width="640">
                            <tr class="mobile_only">
                                <td class="w40" width="40"></td>
                                <td class="w560" width="560" valign="top" align="center">
{{--                                    TODO: add your logo (you can use Request::getSchemeAndHttpHost().'/location.svg') --}}
                                    <img class="mobile_only mobile-logo" border="0" src="" alt="Logo" width="200px" height="" />
                                </td>
                                <td class="w40" width="40"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="large_only">
                    <td class="w640"  height="20" width="640"></td>
                </tr>
                <tr>
                    <td class="w640" width="640" colspan="3" height="20"></td>
                </tr>
                <tr>
                    <td id="header" class="w640" align="center" width="640">
                        <table class="w640" border="0" cellpadding="0" cellspacing="0" width="640">
                            <tr>
                                <td class="w30" width="30"></td>
{{--                                    TODO: add your logo (you can use Request::getSchemeAndHttpHost().'/location.svg') --}}
                                <td id="logo" width="200px" valign="top" align="center">
                                    <img border="0" src="" alt="Logo" width="200px" height="" />
                                </td>
                                <td class="w30" width="30"></td>
                            </tr>
                            <tr>
                                <td colspan="3" height="20" class="large_only"></td>
                            </tr>
                            <tr>
                                <td colspan="3" height="20" class="large_only"></td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td class="w640" bgcolor="#ffffff" width="640">
                        <table class="w640" border="0" cellpadding="0" cellspacing="0" width="640">
                            <tbody>

                            @include('vendor.statikbe.mail.templates.sunny.contentStart')

                            {!! $content !!}

                            <br>

                            <footer>
                                <div>
                                    @if(getSiteOrganisation() == \App\Page::GSF)
                                        Met sportieve groeten,

                                        Team Gezinssport Vlaanderen
                                    @else
                                        Met jeugdige groeten,

                                        Team AFYA
                                    @endif

                                </div>
                                <br>
                                <div>
                                    @if(getSiteOrganisation() == \App\Page::GSF)
                                        Tel: +32 (0)2 507 88 22
                                    @else
                                        Tel: +32 (0)2 507 88 67
                                    @endif
                                </div>
                            </footer>

                            @include('vendor.statikbe.mail.templates.sunny.contentEnd')

                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="w640" bgcolor="#ffffff" width="640" colspan="3" height="20"></td>
                </tr>
                <tr>
                    <td class="w640" bgcolor="#ffffff" width="640" colspan="3" height="20"></td>
                </tr>
                <tr>
                    <td>
                        <table width="640" class="w640" align="center" cellpadding="0" cellspacing="0">
                            <tr>
                                <td class="w50" width="50"></td>
                                <td class="w410" width="410">
                                    @if (isset($reminder))
                                        <p id="permission-reminder" class="footer-content-left" align="left">{!! $reminder !!}</p>
                                    @endif
                                </td>
                                <td valign="top">
                                    <table align="right">
                                        <tr>
                                            <td colspan="2" height="10"></td>
                                        </tr>
                                        <tr>
                                            @if (isset($twitter))
                                                <td><a href="https://twitter.com/{{ $twitter }}"><img src="{{ Request::getSchemeAndHttpHost() }}/vendor/beautymail/assets/images/ark/twitter.png" alt="Twitter" height="25" width="25" style="border:0" /></a></td>
                                            @endif

                                            @if (isset($facebook))
                                                <td><a href="https://facebook.com/{{ $facebook }}"><img src="{{ Request::getSchemeAndHttpHost() }}/vendor/beautymail/assets/images/ark/fb.png" alt="Facebook" height="25" width="25" style="border:0" /></a></td>
                                            @endif
                                        </tr>
                                    </table>
                                </td>
                                <td class="w15" width="15"></td>
                            </tr>

                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="w640" width="640" colspan="3" height="20"></td>
                </tr>
                <tr>
                    <td id="footer" class="w640" height="60" width="640" align="center">

                        @section('footer')
                        @show

                    </td>
                </tr>
                <tr>
                    <td class="w640" width="640" colspan="3" height="40"></td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>