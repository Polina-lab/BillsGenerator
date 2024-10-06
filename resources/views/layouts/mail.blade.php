<html>
<center style="background-color:#e9e9e9;"bgcolor="#e9e9e9">

<table style="border-collapse:collapse;margin:0;padding:0;width:100%;max-width:600px;background-color:#ffffff;" border-spacing="0" cellpadding="0" cellspacing="0" border="0" bgcolor="#e9e9e9">
    <tbody>
        <tr>
            <td>
                <table style="width:100%;border:20px;border-style:solid;border-color:#e9e9e9;border-collapse:collapse;margin:0;padding:0;background-color:#ffffff;border-spacing: 0;" border-spacing="0" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" align="center">
                    <tbody>
                    <tr style="min-width:100%;text-align:center;height:40px;">
                        <td>
                            <span style="font-family:'Montserrat',Arial,sans-serif;font-size:12px">
                                {{ trans('letter.not_correct' , [] , $data['lang'] ?? 'en') }}
                                <a href="" target="_blank" style="color: #000000;">
                                {{ trans('letter.view_page' , [] , $data['lang'] ?? 'en') }}
                                </a>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            @include('includes.header')
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #ffffff">
                            <table style="background-color: #ffffff; width:100%;border:20px">
                                <tbody>
                                    @yield('content')
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            @include('includes.footer')
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
</center>
</html>




