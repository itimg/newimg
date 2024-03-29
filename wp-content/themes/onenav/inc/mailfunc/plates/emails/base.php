<?php
/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-08-07 21:18:41
 * @LastEditors: iowen
 * @LastEditTime: 2021-08-20 11:44:19
 * @FilePath: \onenav\inc\mailfunc\plates\emails\base.php
 * @Description: 
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Email</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <style>
        * {box-sizing: border-box;}
        body {font-family: 'PingFang SC','Helvetica Neue','Helvetica','STHeitiSC-Light','Arial','Microsoft yahei','\005fae\008f6f\0096c5\009ed1',Verdana,sans-serif;font-weight: 400;font-size: 14px;line-height: 1.6;color: #333333;background: #f2f5f8; }
        a {color: #3292ff;text-decoration: none;border-bottom-style: dotted;border-bottom-width: 1px;}
        a:hover{text-decoration:none !important;border-bottom-style: solid;}
        h1,h2,h3,h4,h5,h6{font-weight: 500;line-height: 1.5;margin-bottom: 20px;}
        p {padding: 10px 0;margin-bottom: 10px;}
        .btn {font-weight: 500;border-radius: 4px;padding: 16px 30px;text-align: center;background: #3885ff;text-decoration: none;color: #fff;margin: 15px auto;font-size: 16px;display: inline-block;border: none;}
        .btn:hover{border: none;}
        .btn-success {background: #2ecc71}
        blockquote {border-radius: 3px;background: #f5f5f5;margin: 10px 0;padding: 15px 20px;color: #455667;}
        .center{text-align: center;}
    </style>
</head>
<body>
    <div class="wrapper" style="margin:0;padding:0;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
        <tbody>
        <tr>
            <td class="inner" style="padding:30px 25px 40px 25px;background:#f2f5f8;" bgcolor="#f2f5f8" align="center">
                <table width="650" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                    <tr>
                        <td width="100%" style="border-radius:4px;background:#ffffff;" bgcolor="#ffffff" align="center">
                            <!-- Header -->
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                <tr>
                                    <td width="50%" align="left"><div style="width:150px;height:60px;padding: 15px 0 0 20px;"><img src="<?=$this->e($logo)?>"  title="<?=$this->e($blogName)?>" height="26" style="display:inline;margin:0;max-height:26px;height:26px;width: auto;" border="0"></div></td>
                                    <td width="50%" align="right"><div style="width:250px;height:60px;line-height:60px;padding-right:20px;">
                                            <a href="<?=$this->e($home)?>" title="<?=$this->e($blogName)?>" style="font-size:12px;line-height:60px;color:#222222;text-decoration:none;border:none;padding:0 6px;">首页</a>
                                            <a href="<?=$this->e($home)?>" title="最新收录" style="font-size:12px;line-height:60px;color:#222222;text-decoration:none;border:none;padding:0 6px;">最新收录</a>
                                        </div></td>
                                </tr>
                                </tbody>
                            </table>
                            <!-- Main Body -->
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tbody>
                                <tr>
                                    <td width="100%">
                                        <div style="padding:20px 20px 40px;font-size:15px;line-height:1.5;color:#3d464d;">
                                            <!-- Content -->
                                            <?=$this->section('content')?>
                                            <!-- ./ Content -->
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <!-- Footer -->
                        </td>
                    </tr>
                    <tr>
                        <!-- Outer Footer -->
                        <td width="100%" align="center" style="font-size:10px;line-height: 1.5;color: #999999;padding: 5px 0;text-align:center;">
                            <p style="margin:10px 0 0;">此为系统自动发送邮件, 请勿直接回复.</p>
                            <p style="margin:10px 0 0;">版权所有 © <?php echo date('Y'); ?> <?=$this->e($blogName)?></p>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>