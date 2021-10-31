/*
 * @Author: iowen
 * @Author URI: https://www.iowen.cn/
 * @Date: 2021-07-26 12:01:17
 * @LastEditors: iowen
 * @LastEditTime: 2021-09-13 22:18:20
 * @FilePath: \onenav\js\add-hot.js
 * @Description: 
 */
(function($){
    $(document).on('click','a#hot-option',function(){
        var _this = $(this);
        var parent = _this.parents(".csf-cloneable-content.ui-accordion-content");
        var body = '<div class="rule-option row">\
        <div class="popup-l">\
        <form id="add-rule" name="add-rule" method="post" class="px-1">\
            <h3>自定义规则：<span class="rule-help text-sm">\
            <a href="http://wpa.qq.com/msgrd?v=3&amp;uin=2227098556&amp;site=qq&amp;menu=yes" rel="external nofollow" target="_blank" title="联系我">代写</a>\
            <span class="rule-help-text">代写收费￥19，规则公开，所有用户都可以搜索到。</span>\
            </span> <a class="text-sm ml-3" href="https://www.iotheme.cn/io-api-user-manual.html"  target="_blank">api 使用手册</a></h3>\
            <b style="color:#e20">警告：请不要添加中华人民共和国法律所不允许的内容，发现将立即删除！</b></br></br>\
            <div class="form-input">\
            模块名称 *: <input type="text" name="name" required="required" placeholder="百度热点榜">\
            模块标签: <input type="text" name="description" placeholder="https://top.baidu.com/buzz.php?p=top10 百度热点榜">\
            目标地址 *: <input class="rule-label" type="text" name="url" required="required" placeholder="https://top.baidu.com/buzz.php?p=top10">\
            显示名称 *: <input type="text" name="title" required="required" placeholder="百度">\
            小标题: <input type="text" name="subtitle" placeholder="热点">\
            标题 xpath 规则 *: <input class="rule-label" type="text" name="rule[title]" required="required" placeholder=\'//a[@class="list-title"]/text()\'>\
            url xpath 规则 *: <input class="rule-label" type="text" name="rule[url]" required="required" placeholder=\'//a[@class="list-title"]/@href\'>\
            热度 xpath 规则: <input class="rule-label" type="text" name="rule[hot]" placeholder=\'//td[@class="last"]/span/text()\'>\
            资讯平台 xpath 规则: <span for="is_merge" >资讯平台合入标题<input name="is_merge" type="checkbox" value="1" id="is_merge" checked></span><input class="rule-label" type="text" name="rule[platform]">\
            url补全: <input class="rule-label" type="text" name="url_prefix">\
            cookie: <textarea class="rule-label" name="rule[cookie]"></textarea>\
            缓存时间(分钟) *: <input type="text" name="refresh_time" required="required" value="30" style="width: 90px;margin-bottom: 0;"> 服务器数据缓存时间，缓存时间不低于10分钟。请根据目标站更新频率设置时间。比如吾爱破解排行榜更新频率为1天，请设置时间为 24x60=1440 分钟。<br><br>\
            <input type="hidden" name="rule_id" value="'+parent.find("input[data-depend-id='rule_id']").val()+'">\
            <input type="hidden" name="user" value="'+_this.data('user')+'">\
            <input type="hidden" name="parent_id" value="'+parent.attr('id')+'">\
            <input type="hidden" name="action" value="add">\
            <input type="hidden" id="rule_verify" name="verify" value="0">\
            </div>\
            <input type="submit" class="btn btn-verify add-submit" value="验证">\
        </form></div>\
        <div class="popup-r">\
            <div class="px-1">\
                <h3>规则库：<span class="text-sm">搜索“官方”可显示最新官方规则</span></h3>\
                <form id="search-rule"><input type="text" name="key_word" id="key_word" placeholder="ID、关键词、域名"><input class="btn btn-search" type="submit" value="搜索"></form>\
                <i class="fa fa-fw fa-info-circle fa-fw"></i> ID列表：<a target="_blank" href="https://www.ionews.top/list.html">查看</a>\
                <ul id="rule_list" data-parent_id="'+parent.attr('id')+'">\
                    <li>Loading...</li>\
                </ul>\
            </div>\
            <div class="state-div" style="position:absolute;display:none;bottom:0;width:100%">\
                抓取&反馈: <textarea class="response-state" rows="5"></textarea>\
            </div>\
        </div>\
        </div>';
        pupopTip(body);
        getRuleList('',null);
    });
    $(document).on('click','a#hot-modify',function(){
        var _this = $(this);
        var parent = _this.parents(".csf-cloneable-content.ui-accordion-content");
        $.ajax({
            url: '//ionews.top/api/getruleinfo.php', 
            data: {
                rule_id:parent.find('input[data-depend-id="rule_id"]').val(),
                key:io_theme.apikey
            },
            type: 'POST',
            dataType: 'json',
        })
        .done(function(response) {  
            if(response.state == 1){
                if(response.data.user == _this.data('user')){
                    var _body ='\
                    <input type="hidden" name="rule_id" value="'+parent.find("input[data-depend-id='rule_id']").val()+'">\
                    ';
                }else{
                    var _body ='\
                    <span style="color:#fa0">注：此规则为系统模板，无法修改，可以根据此模板修改为新的规则</span></br></br>\
                    <input type="hidden" name="rule_id" value="">\
                    ';
                }
                var body = '<div class="rule-option">\
                <form id="add-rule" name="add-rule" method="post">\
                    <h3>自定义规则：<span class="rule-help text-sm">\
                    <a href="http://wpa.qq.com/msgrd?v=3&amp;uin=2227098556&amp;site=qq&amp;menu=yes" rel="external nofollow" target="_blank" title="联系我">代写</a>\
                    <span class="rule-help-text">代写收费￥19，规则公开，所有用户都可以搜索到。</span>\
                    </span> <a class="text-sm ml-3" href="https://www.iotheme.cn/io-api-user-manual.html"  target="_blank">api 使用手册</a></h3>\
                    <b style="color:#e20">警告：请不要添加中华人民共和国法律所不允许的内容，发现将立即删除！</b></br></br>\
                    <div class="form-input">\
                    '+_body+'\
                    模块名称 *: <input type="text" name="name" required="required" value="'+response.data.name+'">\
                    模块标签: <input type="text" name="description" value="'+response.data.description+'">\
                    目标地址 *: <input class="rule-label" type="text" name="url" required="required" value="'+response.data.url+'">\
                    显示名称 *: <input type="text" name="title" required="required" value="'+response.data.title+'">\
                    小标题: <input type="text" name="subtitle" value="'+response.data.subtitle+'">\
                    标题 xpath 规则 *: <input class="rule-label" type="text" name="rule[title]" required="required" value="">\
                    url xpath 规则 *: <input class="rule-label" type="text" name="rule[url]" required="required" value="">\
                    热度 xpath 规则: <input class="rule-label"t type="text" name="rule[hot]" value="">\
                    资讯平台 xpath 规则: <span for="is_merge" >资讯平台合入标题<input name="is_merge" type="checkbox" value="1" id="is_merge" '+(response.data.is_merge==1?'checked':'')+'></span><input class="rule-label" type="text" name="rule[platform]">\
                    url补全: <input class="rule-label" type="text" name="url_prefix" value="'+response.data.url_prefix+'">\
                    cookie: <textarea class="rule-label" name="rule[cookie]"></textarea>\
                    缓存时间(分钟) *: <input type="text" name="refresh_time" required="required" value="'+response.data.refresh_time+'" style="width: 90px;margin-bottom: 0;"> 服务器数据缓存时间，缓存时间不低于10分钟。请根据目标站更新频率设置时间。比如吾爱破解排行榜更新频率为1天，请设置时间为 24x60=1440 分钟。<br><br>\
                    <input type="hidden" name="user" value="'+_this.data('user')+'">\
                    <input type="hidden" name="parent_id" value="'+parent.attr('id')+'">\
                    <input type="hidden" name="action" value="modify">\
                    <input type="hidden" id="rule_verify" name="verify" value="0">\
                    </div>\
                    <input type="submit" class="btn btn-verify add-submit" value="验证">\
                </form>\
                <div class="state-div" style="display:none;margin-top:20px">\
                    抓取&反馈: <textarea class="response-state" rows="5"></textarea>\
                </div></div>';
                pupopTip(body);
                $('input[name="rule[title]"]').val(response.data.rule.title);
                $('input[name="rule[url]"]').val(response.data.rule.url);
                $('input[name="rule[hot]"]').val(response.data.rule.hot);
                $('input[name="rule[platform]"]').val(response.data.rule.platform);
                $('textarea[name="rule[cookie]"]').val(response.data.rule.cookie);
                return;
            }else{
                pupopTip('获取内容失败，请重试！');
            }
        })
        .fail(function() {  
            pupopTip('获取内容失败，请重试！');
        });
    });
    function pupopTip(pupText) {
        var popup = $('<div class="io-popup-box">\
            <div class="popup-content">\
            <i class="popup-close fas fa-times"></i>\
            <div>'+pupText+' </div>\
            </div></div>');
        $("body").append(popup); 
        $('.io-popup-box').fadeIn();
        $('body').on('click','.popup-close',function() {
            $('.io-popup-box').fadeOut(500,function () {$(this).remove()})
        })
    }
    var keyWord;
    $(document).on('submit','#search-rule',function(){
        var _this = $(this); 
        var key_word = _this.find('#key_word').val();
        if(keyWord == key_word){
            return false;
        }else{
            keyWord = key_word;
            getRuleList(keyWord,_this.find('.submit'));
        }
        return false;
    });
    var old_text='';
    $(document).on('focus','.rule-label',function(){
        if($('#rule_verify').val()==1)
            old_text = $(this).val();
    });
    $(document).on('input propertychange','.rule-label',function(){
        if($('#rule_verify').val()==1){
            $('#rule_verify').val(0);
            $('.btn.add-submit').addClass('btn-verify').val('验证');
        }else if(old_text!='' && old_text==$(this).val()){
            $('#rule_verify').val(1);
            $('.btn.add-submit').removeClass('btn-verify').val('保存');
        }
    });
    $(document).on('blur','.rule-label',function(){
        old_text = '';
    });
    function getRuleList(keyWord,but){
        if(but) but.val("检索中...").attr("disabled",true);
        var _url = '//ionews.top/api/getrulelist.php'; 
        var _data = {key_word:keyWord};
        if(keyWord == ''){
            _url    = io_theme.ajaxurl; 
            _data   = {action:'load_hot_list'};
        }
        $.ajax({
            url: _url, 
            data: _data,
            type: 'POST',
            dataType: 'json',
        })
        .done(function(response) {  
            //console.log(response);
            if(response.state == 1){  
                var li='';
                response.data.forEach(element => {
                    var rule_id     = element.id?element.id : element.rule_id;
                    var ico         = element.ico?element.ico:'';
                    var is_iframe   = element.is_iframe?1:0;
                    if(keyWord==""||element.source=="system")
                        var badge = '<span class="badge badge-success ml-2">官方</span>';
                    else
                        var badge = '<span class="badge badge-secondary ml-2">用户</span>';
                    li += '<li class="rule-box" data-rule_id="'+rule_id+'" data-ico="'+ico+'" data-is_iframe="'+is_iframe+'" data-type="'+element.type+'"><h4 style="margin:0"><span class="rule-name">'+element.name+'</span>'+badge+'</h4><span class="text-sm">ID: '+rule_id+'</span><br><span class="rule-description text-sm">'+element.description+'</span></li>';
                });
                $('#rule_list').html(li);
            }else{ 
                $('#rule_list').html(response.message);
            }
            if(but) but.val("搜索").removeAttr("disabled");
        })
        .fail(function() {  
            $('#rule_list').html('失败，请重试！');
            if(but) but.val("搜索").removeAttr("disabled"); 
        });
    }
    $(document).on('click','.rule-box',function(){
        var _this = $(this); 
        var parent = $('#'+$("#rule_list").data('parent_id'));
        
        parent.find('input[data-depend-id="name"]').val(_this.find('.rule-name').text());
        parent.find('input[data-depend-id="description"]').val(_this.find('.rule-description').text());
        parent.find('input[data-depend-id="rule_id"]').val(_this.data('rule_id'));
        parent.find('input[data-depend-id="type"]').val(_this.data('type'));
        if(_this.data('is_iframe'))
            parent.find('input[data-depend-id="is_iframe"]').val(_this.data('is_iframe'));
        if(_this.data('ico'))
            parent.find('input[data-depend-id="ico"]').val(_this.data('ico'));
        $('.io-popup-box').fadeOut(500,function () {$(this).remove()});
    });
    $(document).on('submit','#add-rule',function(){
        var _this = $(this); 
        //var rule_id =_this.find('input[name="rule_id"]').val();
        var verify =_this.find('input[name="verify"]').val();
        if(verify == 1){
            //获取 rule_id 并保存
            var parent = $('#'+_this.find("input[name='parent_id']").val());
            _this.find('.add-submit').val("保存中...").attr("disabled",true);
            $.ajax({
                url: '//ionews.top/api/addrule.php', 
                data: _this.serialize(),
                type: 'POST',
                dataType: 'json',
            })
            .done(function(response) {  
                if(response.state == 1){ 
                    _this.find('.add-submit').val("保存").removeAttr("disabled"); 
                    _this.find('input[name="verify"]').val(1);
                    parent.find('input[data-depend-id="name"]').val(response.name);
                    parent.find('input[data-depend-id="description"]').val(response.description);
                    parent.find('input[data-depend-id="rule_id"]').val(response.rule_id);
                    parent.find('input[data-depend-id="type"]').val(response.type);
                    $('.io-popup-box').fadeOut(500,function () {$(this).remove()});
                }else{
                    _this.find('.add-submit').val("保存").removeAttr("disabled"); 
                    $('.response-state').val(JSON.stringify(response.data,null,2));
                    $('.state-div').fadeIn();
                }
            })
            .fail(function() {  
                _this.find('.add-submit').val("保存").removeAttr("disabled");
            });
            return false;
        }
        //执行验证
		_this.find('.add-submit').val("验证中...").attr("disabled",true);
		$.ajax({
			url: '//ionews.top/api/get.php', 
			data: _this.serialize()+'&key='+io_theme.apikey,
			type: 'POST',
			dataType: 'json',
		})
		.done(function(response) {
			if(response.state == 1){ 
                _this.find('.add-submit').removeClass('btn-verify').val("保存").removeAttr("disabled"); 
                _this.find('input[name="verify"]').val(1);
			}else{
                _this.find('.add-submit').val("验证").removeAttr("disabled"); 
            }
            $('.response-state').val(JSON.stringify(response.data,null,2));
            $('.state-div').fadeIn();
		})
		.fail(function() {  
			_this.find('.add-submit').val("验证").removeAttr("disabled");
		});
		return false;
    });
})(jQuery);