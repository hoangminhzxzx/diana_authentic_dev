<!DOCTYPE html>
<html>
<head>
    <title>dianaauthentic.com</title>
</head>

<body>
<div class="" align="center">
    <table style="max-width:730px" width="100%">
        <tbody>
        <tr>
            <td colspan="2"></td>
            <td colspan="3" height="1px" bgcolor="#E8E8E8"></td>
            <td colspan="3"></td>
        </tr>
        <tr>
            <td bgcolor="#FFF">
                <div class="" style="margin-bottom:15px">
                    <div>
                        <table class="" style="width:100%" width="100%" lang="header" cellspacing="0" cellpadding="0"
                               border="0">
                            <tbody>
                            <tr>
                                <td style="padding-top:30px;background:#ffffff;height:70px" width="100%" valign="top"
                                    height="70" bgcolor="#FFFFFF">
                                    <table style="width:100%;height:70px" width="100%" height="70" cellspacing="0"
                                           cellpadding="0" border="0">
                                        <tbody>
                                        <tr>
                                            <td style="width:20px" width="20">
                                                <div lang="space40"></div>
                                            </td>
                                            <td valign="middle" align="center">
                                                <a href="https://dianaauthentic.com/" style="text-decoration:none"
                                                   target="_blank">
                                                    <img src="https://dianaauthentic.com/public/images/logo_diana1.png" style="display:block;max-width:209px;border:none" class="" width="209">
                                                </a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="" style="padding-top:0px">
                    <div class="" style="color:#0f146d;text-align:center">
                        Cám ơn bạn đã đặt hàng tại Diana Authentic!
                    </div>
                    <div class="">
                        <h2>Xin chào {{ ucwords($details_send_mail['customer_name']) }} ,</h2>
                        <p>Diana Authentic đã nhận được yêu cầu đặt hàng của bạn và đang xử lý nhé. Bạn sẽ nhận được thông báo tiếp theo khi đơn hàng đã sẵn sàng được giao.</p>
                    </div>
                </div>
                <div class="">
                    <div class="">
                        Đơn hàng được giao đến
                    </div>
                    <div class="">
                        <table width="100%" cellspacing="0" cellpadding="2">
                            <tbody>
                            <tr>
                                <td style="color:#0f146d;font-weight:bold" width="25%" valign="top">Tên:</td>
                                <td width="75%" valign="top">{{ ucwords($details_send_mail['customer_name']) }}</td>
                            </tr>
                            <tr>
                                <td style="color:#0f146d;font-weight:bold" valign="top">Địa chỉ nhà:</td>
                                <td valign="top">{{ $details_send_mail['address'] }}</td>
                            </tr>
                            <tr>
                                <td style="color:#0f146d;font-weight:bold" valign="top">Điện thoại:</td>
                                <td valign="top">{{ $details_send_mail['customer_phone'] }}</td>
                            </tr>
                            <tr>
                                <td style="color:#0f146d;font-weight:bold" valign="top">Email:</td>
                                <td valign="top">
                                    <a href="mailto:{{ $details_send_mail['email'] }}" target="_blank">{{ $details_send_mail['email'] }}</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="" style="padding-bottom:0px; margin-top: 2rem; margin-bottom: 1rem;">
                    <div class="">
                        <div class="" >
                        <table style="width:100%" cellspacing="0" cellpadding="0">
                            <tbody>
                            @foreach($details_send_mail['items'] as $item)
                            <tr>
                                <td style="width:40%">
                                    <div style="padding-right:10px">
                                        <a href="" target="_blank">
                                            <img src="{{ url($item['thumbnail']) }}" style="width:100%;max-width:160px">
{{--                                            <img src="https://dianaauthentic.com/public/storage/uploads/fb3667ad1d1de754c42b3176c5929bfd-LtImlk4hH8QyGdlT.jpg" style="width:100%;max-width:160px">--}}
                                        </a>
                                    </div>
                                </td>
                                <td style="width:60%">
                                    <div class="">
                                        <a href="{{ route('client.product.detail', ['slug' => $item['slug']]) }}" target="_blank">
                                            <span style="font-size:14px">{{ $item['title'] }}</span>
                                        </a>
                                    </div>
                                    <div class="">
                                        <span style="font-size:14px">{{ number_format($item['price'], 0, '.', '.') }} VNĐ</span>
                                    </div>
                                    <div class="">
                                        <span style="font-size:14px">Số lượng: {{ $item['qty'] }}</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
    <div class="" style="padding-top:0px; max-width: 730px; text-align: left;">
        <div class="">
            <div class="">
                <div class="">
                    <table class="" style="border-bottom:1px solid #d8d8d8" cellspacing="0" cellpadding="0">
                        <tbody>
                        <tr>
                            <td style="color:#585858;width:49%" valign="top">Thành tiền:</td>
                            <td valign="top" align="right">{{ number_format($details_send_mail['total_bill']['total_price_pend'], 0, '.', '.') }} VNĐ</td>
                        </tr>
                        <tr>
                            <td style="color:#585858" valign="top">Giảm giá:</td>
                            <td valign="top" align="right">(0) VNĐ</td>
                        </tr>
                        <tr>
                            <td style="color:#585858" valign="top">Tổng cộng:</td>
                            <td valign="top" align="right">
                                <div style="color:#f27c24;font-weight:bold">{{ number_format($details_send_mail['total_bill']['total_price_final'], 0, '.', '.') }} VNĐ</div>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <br>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="" style="padding-top:0px">
    <div class="" style="color:#0f146d;text-align:center">
        Thanks for your order!
    </div>
</div>

</body>

</html>
