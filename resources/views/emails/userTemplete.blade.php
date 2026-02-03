<!DOCTYPE html >
<html >
   <head>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
   </head>
   <body style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; background-color: #fdfdfd; color: #74787e; height: 100%; hyphens: auto; line-height: 1.4; margin: 0; -moz-hyphens: auto; -ms-word-break: break-all; width: 100% !important; -webkit-hyphens: auto; -webkit-text-size-adjust: none; word-break: break-word;">
      <style>
         @media  only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }
            .footer {
                width: 100% !important;
            }
         }
         @media  only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
         }
      </style>
      <table class="wrapper" width="100%" cellpadding="0" cellspacing="0"
        style="font-family: Avenir, Helvetica, sans-serif;
        box-sizing: border-box; background-color: #fdfdfd;
        margin: 0; padding: 0; width: 100%; -premailer-cellpadding: 0;
        -premailer-cellspacing: 0; -premailer-width: 100%;">
         <tr>
            <td align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
               <table class="content" width="100%" cellpadding="0" cellspacing="0"
                style="font-family: Avenir, Helvetica, sans-serif;
                    box-sizing: border-box; margin: 0; padding: 0;
                    width: 100%; -premailer-cellpadding: 0;
                    -premailer-cellspacing: 0; -premailer-width: 100%;">
                    <tr>
                        <td class="header" style="font-family: Avenir, Helvetica, sans-serif;
                            box-sizing: border-box;
                            padding: 25px 0; text-align: center;">
                            @if(!empty($application->photo))
                                <a href="{{ route('frontend.home') }}"  style="text-decoration: none;" target="_blank">
                                    <img src="{{ asset('uploads/application/') . '/' . $application->photo }}"
                                    alt="{{ $application->name ?? config('app.name') }}" style="height: 80px; width: 40%;">
                                </a>
                            @else
                                <a href="{{ route('frontend.home') }}"
                                    style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;
                                    color: #bbbfc3; font-size: 19px; font-weight: bold;
                                    text-decoration: none; text-shadow: 0 1px 0 #ffffff;">
                                    {{ $application->name ?? config('app.name') }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="body" width="100%" cellpadding="0" cellspacing="0"
                            style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;
                            background-color: #ffffff; border-bottom: 1px solid #edeff2;
                            border-top: 1px solid #edeff2; margin: 0; padding: 0;
                            width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0;
                            -premailer-width: 100%;">
                            <table class="inner-body" align="center" width="700" cellpadding="0" cellspacing="0"
                                style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;
                                background-color: #ffffff; margin: 0 auto; padding: 0;
                                width: 700px; -premailer-cellpadding: 0;
                                -premailer-cellspacing: 0; -premailer-width: 700px;">
                                <!-- Body content -->
                                <tr>
                                    <td class="content-cell"
                                        style="font-family: Avenir, Helvetica,
                                        sans-serif; box-sizing: border-box;
                                        padding: 35px;">
                                        <h1 style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;
                                            color: #2F3133; font-size: 19px; font-weight: bold; margin-top: 0; text-align: left;">
                                            Heading 1
                                        </h1>
                                        <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;
                                            color: #74787e; font-size: 16px; line-height: 1.5em;
                                            margin-top: 0; text-align: left;">
                                            This is a paragraph filled with Lorem Ipsum and a link. Cumque dicta
                                            <a href="" style="font-family: Avenir, Helvetica, sans-serif;
                                                box-sizing: border-box; color: #3869d4;">
                                                doloremque eaque
                                            </a>,
                                            enim error laboriosam pariatur possimus tenetur veritatis voluptas.
                                        </p>
                                        <h2 style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;
                                            color: #2F3133; font-size: 16px; font-weight: bold;
                                            margin-top: 0; text-align: left;">
                                            Heading 2
                                        </h2>
                                        <div class="table" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                            <table style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;
                                                margin: 30px auto; width: 100%; -premailer-cellpadding: 0; -premailer-cellspacing: 0;
                                                -premailer-width: 100%;">
                                                <thead style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                                    <tr>
                                                        <th style="font-family: Avenir, Helvetica, sans-serif;
                                                            box-sizing: border-box; color: #74787e; border-bottom: 1px solid #edeff2;
                                                            padding-bottom: 8px;">
                                                            Product
                                                        </th>
                                                        <th style="font-family: Avenir, Helvetica, sans-serif;
                                                            box-sizing: border-box; color: #74787e; border-bottom: 1px solid #edeff2;
                                                            padding-bottom: 8px;">
                                                            Description
                                                        </th>
                                                        <th style="font-family: Avenir, Helvetica, sans-serif;
                                                            box-sizing: border-box; color: #74787e; border-bottom: 1px solid #edeff2;
                                                            padding-bottom: 8px; text-align: right;">
                                                            Price
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                                    <tr>
                                                        <td style="font-family: Avenir, Helvetica, sans-serif;
                                                        box-sizing: border-box; color: #74787e;
                                                        font-size: 15px; line-height: 18px;
                                                        padding: 10px 0;">
                                                        Product 1</td>
                                                        <td style="font-family: Avenir, Helvetica, sans-serif;
                                                            box-sizing: border-box; color: #74787e;
                                                            font-size: 15px; line-height: 18px;
                                                            padding: 10px 0;">
                                                            Lorem Ipsum
                                                        </td>
                                                        <td style="font-family: Avenir, Helvetica, sans-serif;
                                                            box-sizing: border-box; color: #74787e;
                                                            font-size: 15px; line-height: 18px;
                                                            padding: 10px 0;
                                                            text-align: right;">
                                                            $10
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="font-family: Avenir, Helvetica, sans-serif;
                                                            box-sizing: border-box; color: #74787e;
                                                            font-size: 15px; line-height: 18px;
                                                            padding: 10px 0;">
                                                            Product 2
                                                        </td>
                                                        <td style="font-family: Avenir, Helvetica, sans-serif;
                                                            box-sizing: border-box; color: #74787e;
                                                            font-size: 15px; line-height: 18px;
                                                            padding: 10px 0;">
                                                            Lorem ipsum dolor sit amet.
                                                        </td>
                                                        <td style="font-family: Avenir, Helvetica, sans-serif;
                                                            box-sizing: border-box; color: #74787e;
                                                            font-size: 15px; line-height: 18px;
                                                            padding: 10px 0;
                                                            text-align: right;">
                                                            $20
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <table class="action" align="center" width="100%" cellpadding="0" cellspacing="0"
                                            style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;
                                            margin: 30px auto; padding: 0; text-align: center; width: 100%;
                                            -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                                            <tr>
                                                <td align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                                    <table width="100%" border="0" cellpadding="0" cellspacing="0"
                                                        style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                                        <tr>
                                                            <td align="center" style="font-family: Avenir, Helvetica, sans-serif;
                                                                box-sizing: border-box;">
                                                            <table border="0" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif;
                                                            box-sizing: border-box;">
                                                                <tr>
                                                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                                                        <a href="#" class="button button-green" target="_blank" style="font-family: Avenir, Helvetica, sans-serif;
                                                                            box-sizing: border-box; border-radius: 3px;
                                                                            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);
                                                                            color: #ffffff; display: inline-block;
                                                                            text-decoration: none; -webkit-text-size-adjust: none;
                                                                            background-color: #2ab27b; border-top: 10px solid #2ab27b;
                                                                            border-right: 18px solid #2ab27b; border-bottom: 10px solid #2ab27b;
                                                                            border-left: 18px solid #2ab27b;">
                                                                            Green button
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        <table class="action" align="center" width="100%" cellpadding="0" cellspacing="0"
                                            style="font-family: Avenir, Helvetica, sans-serif;
                                            box-sizing: border-box; margin: 30px auto; padding: 0; text-align: center; width: 100%;
                                            -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 100%;">
                                            <tr>
                                                <td align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                                    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif;
                                                        box-sizing: border-box;">
                                                        <tr>
                                                            <td align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                                            <table border="0" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif;
                                                            box-sizing: border-box;">
                                                                <tr>
                                                                    <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                                                        <a href="#" class="button button-red" target="_blank" style="font-family: Avenir, Helvetica, sans-serif;
                                                                        box-sizing: border-box; border-radius: 3px;
                                                                            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);
                                                                            color: #ffffff; display: inline-block; text-decoration: none;
                                                                            -webkit-text-size-adjust: none; background-color: #bf5329;
                                                                            border-top: 10px solid #bf5329; border-right: 18px solid #bf5329;
                                                                            border-bottom: 10px solid #bf5329; border-left: 18px solid #bf5329;">
                                                                            Red button
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>

                                        <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787e; font-size: 16px;
                                            line-height: 1.5em; margin-top: 0; text-align: left;">
                                            Thanks,<br>
                                            MarkdownMail
                                        </p>
                                        <table class="subcopy" width="100%" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif;
                                            box-sizing: border-box; border-top: 1px solid #edeff2; margin-top: 25px; padding-top: 25px;">
                                            <tr>
                                                <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                                                    <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; color: #74787e;
                                                        line-height: 1.5em; margin-top: 0; text-align: left; font-size: 12px;">
                                                        This is the subcopy of the email
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box;">
                            <table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; margin: 0 auto;
                                padding: 0; text-align: center; width: 570px; -premailer-cellpadding: 0; -premailer-cellspacing: 0; -premailer-width: 570px;">
                            <tr>
                                <td class="content-cell" align="center" style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; padding: 35px;">
                                        <p style="font-family: Avenir, Helvetica, sans-serif; box-sizing: border-box; line-height: 1.5em; margin-top: 0;
                                        color: #aeaeae; font-size: 12px; text-align: center;">
                                            © 2020 MarkdownMail. All rights reserved.
                                        </p>
                                </td>
                            </tr>
                            </table>
                        </td>
                    </tr>
               </table>
            </td>
         </tr>
      </table>
   </body>
</html>
