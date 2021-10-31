<?php 
/*!
 * Theme Name:One Nav
 * Theme URI:https://www.iotheme.cn/
 * Author:iowen
 * Author URI:https://www.iowen.cn/
 */
?>
<style>
    #preloader_3{position:relative}
    #preloader_3:before{width:20px;height:20px;border-radius:20px;content:'';position:absolute;background:#db448b;left:-20px;animation: preloader_3_before 1.5s infinite ease-in-out;}
    #preloader_3:after{width:20px;height:20px;border-radius:20px;content:'';position:absolute;background:#f1404b;animation: preloader_3_after 1.5s infinite ease-in-out;}
    @keyframes preloader_3_before{0% {transform: translateX(0px) rotate(0deg)}50% {transform: translateX(50px) scale(1.2) rotate(260deg); background:#f1404b;border-radius:0px;}100% {transform: translateX(0px) rotate(0deg)}}
    @keyframes preloader_3_after{0% {transform: translateX(0px)}50% {transform: translateX(-50px) scale(1.2) rotate(-260deg);background:#db448b;border-radius:0px;}100% {transform: translateX(0px)}}
</style>
<div id="preloader_3"></div>