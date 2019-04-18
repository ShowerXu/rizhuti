
function delOrder(order_num){
    // DEl ORDER_NUM
    
    popup.showToast({
        type: "it",
        text: "删除订单："+order_num,
        time: 1e5
    });
    if (order_num) {
        $.post(wppay_ajax_url, {"action": "del_order","order_num": order_num},function (result) {
                // if start
                if( result.status == 1 ){
                    popup.showToast({
                        type: "text",
                        text: "删除成功",
                        time: 1e5
                    });
                    
                    setTimeout(function() {location.reload();}, 2000);

                }else{
                    popup.showToast({
                        type: "text",
                        text: "删除订单失败",
                        time: 1e5
                    });
                    // setTimeout(function() {location.reload();}, 2000);
                    
                }
                // end
            }
        ,'json'); 
    }
    return false;
}

jQuery(function($){
 	// 头像上传
 	$("#addPic").change(function(){
		$("#AvatarForm").ajaxSubmit({
			dataType:  'json',
			beforeSend: function() {
				$('#udptips').html('上传中...');
			},
			uploadProgress: function(event, position, total, percentComplete) {
				
			},
			success: function(data) {
				if (data == "1") {
					$('#udptips').html('头像修改成功');
					location.reload();
				}else if(data == "2"){
					 $('#udptips').html('图片最大支持80KB');
				}else if(data == "3"){
					 $('#udptips').html('图片格式只支持.jpg .png .gif');
				}else{
					 $('#udptips').html('上传失败');
				}
			},
			error:function(xhr){
				popup.showToast({type: "text",text:"上传失败，请重试！"});	
			}
		});
	});



    //REMOVE THIS - it's just to show error messages 
	$('.user-usermeta-form').find('button[type="submit"]').on('click', function(event){
		event.preventDefault();
	});

    //修改个人信息
	$('#user-action-info').on('click', function(){
		var user_email = $("input[name='user_email']").val();
		var reg = /^([a-zA-Z]|[0-9])(\w|\-)+@[a-zA-Z0-9]+\.([a-zA-Z]{2,4})$/;
		if(!reg.test(user_email)){
			popup.showToast({type: "text",text: "邮箱格式不正确"}); return;
		}
		popup.showToast({type: "it",text: "修改中..."});
		$.post(
			TBUI.uri+'/action/user.php',
			{
				nickname: $("input[name='nickname']").val(),
				user_email: user_email,
				action2: "1"
			},
			function (data) {
				if (data) {
					popup.showToast({type: "text",text:data});
				}
				setTimeout(function() {location.reload();}, 2000);
			}
		);
	})

	//解除绑定QQ
	$('#unset-bind-qq').on('click', function(){
		$.post(
			TBUI.uri+'/action/user.php',
			{
				action2: "3"
			},
			function (data) {
				if (data) {
					popup.showToast({type: "text",text:data});
				}
				setTimeout(function() {location.reload();}, 2000);
			}
		);
	})

	//修改密码
	$('#user-action-paw').on('click', function(){
		$.post(
			TBUI.uri+'/action/user.php',
			{
				password: $("input[name='password']").val(),
				password2: $("input[name='password2']").val(),
				action2: "2"
			},
			function (data) {
				if (data) {
					popup.showToast({type: "text",text:data});
				}
				setTimeout(function() {location.reload();}, 2000);
			}
		);
	})

	// tougao-edito
	$(document).on('click', '.publish_post', function(event) {
		
	    event.preventDefault();
	    var title = $.trim($('#post_title').val());
	    var status = $(this).data('status');
	    $('#post_status').val(status);
	    var content = editor.txt.html();
	    var filterHtml = filterXSS(content);
	    $("input[name='editor']").val(filterHtml);
	    if( !content ){
	        alert('请填写文章内容！');
	        return false;
	    }

	    if( title == 0 ){
	        alert('请输入文章标题！');
	        return false;
	    }

	    $.ajax({
	        url: TBUI.siteurl+'wp-admin/admin-ajax.php',
	        type: 'POST',
	        dataType: 'json',
	        data: $('#post_form').serializeArray(),
	    }).done(function( data ) {
	        if( data.state == 200 ){
	            alert(data.tips);
	            window.location.href = data.url;
	        }else{
	            alert(data.tips);
	        }
	    }).fail(function() {
	        alert('出现异常，请稍候再试！');
	    });
	    
	});

});