
jQuery(function($){
    var payment_alipay_html= '<div class="pay-item" data-id="1"><i class="alipay"></i><span>支付宝</span></div>';
    var payment_weixin_html= '<div class="pay-item" data-id="2" style="border-bottom:0;"><i class="weixinpay"></i><span>微信支付</span></div>';

    //免签专用js，为了懒惰处理js 直接换文件，不判断*_*
    // CONET PAY This POST Alipay FOR POST
    $("#pay-loader").on("click",function(){
        var post_id = $(this).data("post");
        var order_type = 1;
        // 获取支付方式
        popup.showCustomModal({
            template: "PayMethod",  // AlipayQrcode; WeixinpayQrcode ;Popup；PayMethod
            layerClose: 1,
            data: {html: payment_alipay_html+payment_weixin_html}
        });

        $("#customModal .modal .pay-button-box .pay-item").on("click",function(){
            var pay_type = $(this).attr('data-id');
            // $(this).html('获取支付信息...');
            popup.hideModal('customModal');
            popup.showToast({
                type: "it",
                text: "订单创建中...",
                time: 4000
            });
            senData(pay_type,post_id,order_type);
        });
        return false;
    });

    // CONET PAY This VIP Alipay for USER PAGE
    $("#pay-vip").on("click",function(){
      var post_id = 0; 
      var order_type = $("input[name='order_type']:checked").val();
      // 获取支付方式
      popup.showCustomModal({
          template: "PayMethod",  // AlipayQrcode; WeixinpayQrcode ;Popup；PayMethod
          layerClose: 1,
          data: {html: payment_alipay_html+payment_weixin_html}
      });
      $("#customModal .modal .pay-button-box .pay-item").on("click",function(){
          var pay_type = $(this).attr('data-id');
          // $(this).html('获取支付信息...');
          popup.hideModal('customModal');
          popup.showToast({
              type: "it",
              text: "订单创建中...",
              time: 4000
          });
          senData(pay_type,post_id,order_type);
      });
      return false;
    });
    
    // 请求订单 paytype ; postid
    function senData(pay_type,post_id,order_type){
      if (pay_type == 1) {
        // 支付宝方式
        action = "skbalipay";
        template = "AlipayQrcode";
      }else if(pay_type == 2){
        //微信方式
        action = "skbweixinpay";
        template = "WeixinpayQrcode";
      }
      $.post(wppay_ajax_url, {"action": action,"post_id": post_id,"order_type": order_type},function (result) {
        // if start
        if( result.status == 200 ){
            popup.hideToast();
            popup.showCustomModal({
                template: template,  // AlipayQrcode; WeixinpayQrcode ;Popup；PayMethod
                layerClose: 0,
                data: {
                    price: result.price,
                    code: result.qr,
                    desc:'付款后自动到账，填写金额要保持一致<br/>期间请勿关闭此页面'
                }
            });
            // 每4秒检测一次是否支付，如果支付则刷新页面
            wppayOrder = setInterval(function() {
                $.post(wppay_ajax_url, {
                    "action": "check_pay",
                    "post_id": post_id,
                    "order_num": result.num,
                    "order_type": order_type
                }, function(data) {
                    if(data.status == "1"){
                        clearInterval(wppayOrder);
                        popup.hideModal('customModal');
                        popup.showToast({
                            type: "text",
                            text: "恭喜您支付成功"
                        });
                        setTimeout(function() {location.reload();}, 2000);
                    }
                });
            }, 4000);

        }else if( result.status == 201 ){
            popup.showToast({
                type: "text",
                text: result.msg
            });
        }else{
            popup.showToast({
                type: "text",
                text: result.msg
            });
        }
        // end
      },'json');
    }

});