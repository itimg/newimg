<?php 
/*!
 * Theme Name:One Nav
 * Theme URI:https://www.iotheme.cn/
 * Author:iowen
 * Author URI:https://www.iowen.cn/
 */
?>
<style>
    .loader{--size:32px;--duration:800ms;width:32px;transform-style:preserve-3d;transform-origin:50% 50%;transform:rotateX(60deg) rotateZ(45deg) rotateY(0deg) translateZ(0px);position:relative}
    .loader .box{width:32px;height:32px;transform-style:preserve-3d;position:absolute;top:0;left:0}
    .loader .box:nth-child(1){transform:translate(100%,0);animation:box1 800ms linear infinite}
    .loader .box:nth-child(2){transform:translate(0,100%);animation:box2 800ms linear infinite}
    .loader .box:nth-child(3){transform:translate(100%,100%);animation:box3 800ms linear infinite}
    .loader .box:nth-child(4){transform:translate(200%,0);animation:box4 800ms linear infinite}
    .loader .box > div{--translateZ:calc(var(--size) / 2);--rotateY:0deg;--rotateX:0deg;background:#f65c5c;width:100%;height:100%;transform:rotateY(var(--rotateY)) rotateX(var(--rotateX)) translateZ(var(--translateZ));position:absolute;top:auto;right:auto;bottom:auto;left:auto}
    .loader .box > div:nth-child(1){top:0;left:0}
    .loader .box > div:nth-child(2){background:#f21414;right:0;--rotateY:90deg}
    .loader .box > div:nth-child(3){background:#f54444;--rotateX:-90deg}
    .loader .box > div:nth-child(4){background:rgba(165,100,100,.15);top:0;left:0;--translateZ:calc(var(--size) * 3 * -1)}
    @keyframes box1{0%,50%{transform:translate(100%,0)}
    100%{transform:translate(200%,0)}
    }@keyframes box2{0%{transform:translate(0,100%)}
    50%{transform:translate(0,0)}
    100%{transform:translate(100%,0)}
    }@keyframes box3{0%,50%{transform:translate(100%,100%)}
    100%{transform:translate(0,100%)}
    }@keyframes box4{0%{transform:translate(200%,0)}
    50%{transform:translate(200%,100%)}
    100%{transform:translate(100%,100%)}
    }
</style>
<div class="loader">
    <div class="box">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
    <div class="box">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
    <div class="box">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
    <div class="box">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>