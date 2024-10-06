<template>
    <div>
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <title>Reset Password</title>
                <style type="text/css">
                body {margin: 0; padding: 0; min-width: 100%!important;}
                .content {width: 100%; max-width: 600px;}
                </style>
            </head>   

        <body>
        <center>
            <table border="0" sellpadding="0" cellspacing="0" bgcolor="#e9e9e9" style="border-collapse: collapse; margin: 0; padding: 0; width: 100%; max-width: 600px;">
                <tr>
                    <td>
                        <table border="0" align="center" sellpadding="10" cellspacing="0" bgcolor="#ffffff" style="border-collapse: collapse; width: 100%; border: 20px; border-style: solid; border-color: #e9e9e9;">
                            <tr style="min-width: 100%; text-align: center; height: 50px;">
                                <td>
                                    <span style="font-family: 'Montserrat', Arial, sans-serif; font-size: 14px;">
                                        {{ $t('letter.not_correct') }} 
                                        <a href="" target="_blank" style="color: #000000;">
                                            {{ $t('letter.view_page') }}
                                        </a>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table border="0" sellpadding="0" cellspacing="0" bgcolor="#26a647" align="center" style="border-collapse: collapse; width: 90%; color: #ffffff;" >  
                                        <table border="0" sellpadding="0" cellspacing="0" bgcolor="#26a647" align="center" style="border-collapse: collapse; width: 96%; color: #ffffff; font-family: 'Montserrat', Arial, sans-serif;">                                     
                                        <tr style="width: 55%;">
                                            <td>
                                            <img src="@/assets/img/email/logoWhite.svg" alt="Logo." style="width: 90px; height: 50px;">

                                            </td>
                                            <td style="width: 45%;">
                                                <img src="@/assets/img/email/letter_fon1.svg" alt="" style="position: absolute; width: 290px; height: 110px;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="height: 60px; vertical-align: center;">  
                                                <span style="font-size: 15px">                                             
                                                    <b>    
                                                        {{ $t('letter.reset_password') }}  
                                                    </b>  
                                                </span>                                               
                                            </td>
                                        </tr>
                                        <tr></tr>
                                        <tr>
                                            <td style="height: 50px; vertical-align: top;">
                                                <span style="height: 70px;">
                                                    <a href="" target="_blank" style="padding: 6px 40px 6px 40px; height: 70px; background-color: #ffffff; border-radius: 5px; color: #26a647; font-size: 15px; font-weight: 600;">
                                                    {{ $t('letter.reset') }}
                                                    </a>
                                                </span>
                                            </td>
                                        </tr>
                                        </table> 
                                    </table>
                                </td>
                            </tr>

                            <table  border="0" align="center" sellpadding="0" cellspacing="0" style="border-collapse: collapse; width: 90%; color: #ffffff;">
                            <tr style="min-width: 100%; text-align: center; height: 35px; vertical-align: bottom; color: #26a647; font-size: 18px;">
                                <td>
                                    <span>
                                        <strong>
                                            {{ $t('letter.hallo') }} Name SurName !
                                        </strong>
                                    </span>                                      
                                </td>
                            </tr>
                            <tr style="min-width: 100%; text-align: center; color: #9b9b9b;">
                                <td>
                                    <span style="font-size: 14px;">
                                        {{ $t('letter.reset_requested') }}
                                    </span>
                                </td>
                            </tr> 
                            <tr style="min-width: 100%; text-align: center; color: #9b9b9b;">
                                <td>
                                    <span style="font-size: 14px;">
                                        {{ $t('letter.click_create') }}
                                    </span>
                                </td>
                            </tr>                          

                            <tr style="min-width: 100%; text-align: center; height: 70px; color: #9b9b9b;">
                                <td>
                                    <span>
                                        <a href="" target="_blank" style="padding: 5px 20px 5px 20px; width: 200px; background-color: #fdb92d; border-radius: 5px; color: #ffffff;">
                                            {{ $t('letter.res_password') }}
                                        </a>
                                    </span>                                    
                                </td>
                            </tr>

                            <tr style="min-width: 100%; text-align: center; height: 30px; vertical-align: top; color: #9b9b9b;">
                                <td>
                                    <span style="font-size: 14px;">
                                        {{ $t('letter.or_use') }}
                                    </span>
                                </td>
                            </tr>

                            <tr style="min-width: 100%; text-align: center; height: 38px; ">
                                <td style="height: 50px;">
                                    <span style="font-size: 17px; color: #000000;">
                                        <b>FgTyhr65Js</b>
                                    </span>                                   
                                </td>
                            </tr>

                            <tr style="min-width: 100%; text-align: center; height: 80px; vertical-align: top; color: #9b9b9b; border-bottom: 2px; border-bottom-style: solid; border-bottom-color: #26a647;">
                                <td>
                                    <span style="font-size: 14px;">
                                        {{ $t('letter.please_login') }}                                       
                                    </span>
                                </td>
                            </tr>

                            <tr style="min-width: 100%; text-align: center; height: 70px; color: #9b9b9b;">
                                <td>
                                    <span style="font-size: 14px;">
                                        {{ $t('letter.not_request') }}
                                    </span>
                                </td>
                            </tr>

                            </table>

                            <tr>
                                <td style="padding-right: 5px;">
                                    <table border="0" align="center" sellpadding="0" cellspacing="0" bgcolor="#26a647" style="border-collapse: collapse; width: 90%; height: 100px; color: #ffffff; font-family: 'Montserrat', Arial, sans-serif;" > 
                                        <tr>
                                            <td style="width: 26%; text-align: right;">
                                                <span style="font-size: 10px;">
                                                    <a href="https://bills.gloreal.ee/help/about" target="_blank" style="color: #ffffff;">
                                                        {{ $t('menu.about_us')}}
                                                    </a>                                                   
                                                </span>
                                            </td>
                                            <td style="width: 10%;"></td>
                                            <td style="width: 34%; min-width: 60px; text-align: center;">
                                                <span style="font-size: 10px;">
                                                    <a href="https://bills.gloreal.ee/login" target="_blank" style="color: #ffffff;">
                                                        {{ $t('menu.login')}}
                                                    </a>
                                                </span>
                                            </td>
                                            <td style="width: 34%; text-align: center;">
                                                <span style="font-size: 10px;">
                                                    <a href="https://bills.gloreal.ee/help" target="_blank" style="color: #ffffff;">
                                                        {{ $t('menu.help')}}
                                                    </a>
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4">
                                                <img src="@/assets/img/email/letter_fon2.svg" alt="" style="position: absolute; width: 300px; height: 150px;">
                                            </td>
                                        </tr>    
                                        <tr>
                                            <td style="vertical-align: bottom; text-align: left;text-align: right; white-space: nowrap;">
                                                <span style="font-size: 14px;">{{ $t('menu.join_us') }}</span>
                                                <a href="https://www.instagram.com/gloreal_estate/" target="_blank">
                                                    <img src="@/assets/img/email/insta.svg" alt="" style="width: 17px; height: 17px;">
                                                </a>
                                                <a href="https://www.facebook.com/glorealkinnisvara/" target="_blank">
                                                    <img src="@/assets/img/email/messenger.svg" alt="" style="width: 17px; height: 17px;">
                                                </a>
                                                <a href="https://www.facebook.com/glorealkinnisvara/" target="_blank">
                                                    <img src="@/assets/img/email/facebook.svg" alt="" style="width: 17px; height: 17px;">
                                                </a>
                                            </td>
                                            <td></td>
                                            <td style="vertical-align: bottom; text-align: right;">
                                                <tr>
                                                    <img src="@/assets/img/email/logoWhite.svg" alt="Logo." style="width: 90px; height: 50px;">
                                                </tr>
                                                <tr>                                                  
                                                    <a href="https://bills.gloreal.ee/privacy" target="_blank" style="font-size: 10px; color: #ffffff;">
                                                        {{ $t('menu.privacy_policy')}}
                                                    </a>                                                   
                                                </tr>
                                            </td>
                                            <td>

                                                
                                                <table border="0" align="center" sellpadding="0" cellspacing="0" bgcolor="#26a647" style="border-collapse: collapse; width: 100%;">
                                                   <tr>
                                                       <td style="text-align: right; line-height: 10px;">
                                                           <strong style="font-size: 10px; color: #ffffff;">
                                                               {{ $t('menu.contact_us')}}
                                                           </strong>
                                                        </td>
                                                    </tr>
                                                   <tr style="height: 5px;"></tr>
                                                   <tr>
                                                       <td style="text-align: right; line-height: 10px; ">
                                                            <span style="font-size: 10px; color: #ffffff;">
                                                                Glo Real Network OÜ
                                                            </span>
                                                       </td>
                                                    </tr>
                                                   <tr>
                                                       <td style="text-align: right; line-height: 10px;">
                                                            <a href="tel:055599313 " value="+372 55599313 " target="_blank" style="font-size: 10px; color: #ffffff;">+372 555 99 313       
                                                            </a>                                     
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: right; line-height: 10px;">
                                                            <a href="mailto:info@gloreal.ee" style="font-size: 10px; color: #ffffff;">
                                                            info@gloreal.ee
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: right; line-height: 10px;">
                                                            <a href="https://i-gen.ee/" target="_blank" style="font-size: 10px; color: #ffffff;">
                                                            https://i-gen.ee
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: right; line-height: 10px;">
                                                            <span style="font-size: 10px; color: #ffffff;">
                                                                Estonia pst 5, III {{ $t('menu.korrus')}}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="text-align: right; line-height: 10px;">
                                                            <span style="font-size: 10px; color: #ffffff;">
                                                                {{ $t('menu.tallinn_harjumaa')}}
                                                            </span>
                                                        </td>
                                                    </tr>
                                               
                                            </table>
                                            </td>
                                        </tr>
                                        <tr style="height: 10px"></tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="height: 30px"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </center>
        </body>
        </html>
    </div>
</template>
<script>
export default {
    layout: "letter"
}
</script>
<style scoped>
</style>