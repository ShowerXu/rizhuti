<?php

if ( !defined('ABSPATH') ) {exit;}
if(!is_user_logged_in())
{
	wp_die('请先登录系统');
}
date_default_timezone_set('Asia/Shanghai');
global $wpdb, $wppay_table_name;

$action=isset($_GET['action']) ?$_GET['action'] :false;
$id=isset($_GET['id']) && is_numeric($_GET['id']) ?intval($_GET['id']) :0;
// 保存
if($action=="save" && current_user_can('administrator'))
{
	$result = isset($_POST['result']) && is_numeric($_POST['result']) ?intval($_POST['result']) :0;
	$update_order = $wpdb->query("UPDATE $wppay_table_name SET pay_num = '88888888', pay_time = '".time()."' ,status='".$result."' WHERE id = '".$id."'");
	if(!$update_order){
		echo '<div id="message" class="updated notice is-dismissible"><p>系统更错处理失败</p></div>';
	}
	else {
		echo '<div id="message" class="updated notice is-dismissible"><p>更新成功</p></div>';
	}
	unset($id);
}

// 内页
if($id && current_user_can('administrator'))
{
	$info=$wpdb->get_row("SELECT * FROM $wppay_table_name where id=".$id);
	if(!$info->id)
	{
		echo '<div id="message" class="updated notice is-dismissible"><p>订单ID无效</p></div>';
		exit;
	}
	?>
	<div class="wrap">
   	<h2>查看订单详情</h2>
<form method="post" action="<?php echo admin_url('admin.php?page=wppay_orders_page&action=save&id='.$id); ?>" style="width:70%;float:left;background-color: #fff;padding: 20px;">

        <table class="form-table">
            <tr>
                <td valign="top" width="30%"><strong>订单号</strong><br />
                </td>
                <td><?php echo $info->order_num?></td>
            </tr>
            <tr>
                <td valign="top" width="30%"><strong>用户ID</strong><br />
                </td>
                <td><?php echo $userName = ($info->user_id != 0 ) ? get_user_by('id',$info->user_id)->user_login : '游客' ; ?></td>
            </tr>
            <tr>
                <td valign="top" width="30%"><strong>商品名称</strong><br />
                </td>
                <td><?php echo get_the_title($info->post_id)?>
                </td>
            </tr>
             <tr>
                <td valign="top" width="30%"><strong>价格</strong><br />
                </td>
                <td><?php echo $info->order_price ?>
                </td>
            </tr>
            <tr>
                <td valign="top" width="30%"><strong>支付状态</strong><br />
                </td>
                <td><input type="radio" name="result" id="res1" value="1" <?php if($info->status==1) echo "checked";?>/>已支付 
                <input type="radio" name="result" id="res1" value="0" <?php if($info->status==0) echo "checked";?>/>未支付
                </td>
            </tr>
            <tr>
                <td valign="top" width="30%"><strong>下单时间</strong><br />
                </td>
                <td><?php echo date('Y-m-d h:i:s',$info->create_time) ?>
                </td>
            </tr>
            <tr>
                <td valign="top" width="30%"><strong>支付时间</strong><br />
                </td>
                <td><?php echo $times = ($info->pay_time) ? date('Y-m-d h:i:s',$info->pay_time) : '' ; ?>
                </td>
            </tr>
            <tr>
                <td valign="top" width="30%"><strong>支付商户订单号</strong><br />
                </td>
                <td><?php echo $info->pay_num ?>
                </td>
            </tr>
    </table>
        <br /> <br />
        <table> <tr>
        <td><p class="submit">
            <input type="submit" name="Submit" value="保存设置" class="button-primary"/>
            </p>
        </td>

        </tr> </table>

</form>
			</div>
	<?php
	exit;
}

$total   = $wpdb->get_var("SELECT COUNT(id) FROM $wppay_table_name WHERE order_type =1");

$perpage = 20;
$pages = ceil($total / $perpage);
$page=isset($_GET['paged']) ?intval($_GET['paged']) :1;
$offset = $perpage*($page-1);
$list = $wpdb->get_results("SELECT * FROM $wppay_table_name WHERE order_type =1 ORDER BY create_time DESC limit $offset,$perpage");

?>

<!-- 默认显示页面 -->
<div class="wrap">
	<h2>所有订单</h2>
	<div class="tablenav top">
	    <p class="search-box">
	<label class="screen-reader-text" for="post-search-input">搜索订单 :</label>
	<input type="search" id="post-search-input" name="s" value="">
	<input type="submit" id="search-submit" class="button" value="搜索订单"></p>

	    <div class="alignleft actions">
	        <label class="screen-reader-text" for="filter-by-date">
	            按日期筛选
	        </label>
	        <select id="filter-by-date" name="m">
	            <option selected="selected" value="0">
	                全部日期
	            </option>
	        </select>
	        <label class="screen-reader-text" for="status">
	            按订单状态过滤
	        </label>
	        <select class="postform" id="status" name="status">
	            <option selected="selected" value="0">
	                订单未支付
	            </option>
	            <option class="level-0" value="1">
	                订单已支付
	            </option>
	        </select>
	        <input class="button" id="post-query-submit" name="filter_action" type="submit" value="筛选">
	        </input>
	    </div>
	    <div class="tablenav-pages one-page">
	        <span class="displaying-num">
	            共<?php echo $total ?>个订单 
	        </span>
	    </div>
	    <br class="clear">
	    </br>
	</div>

	<table class="wp-list-table widefat fixed striped posts">
		<thead>
			<tr>
				<th>订单号</th>
				<th>用户ID</th>	
				<th>商品名称</th>
				<th>价格</th>
				<th>状态</th>
				<th>下单时间</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
	<?php
		if($list) {
			foreach($list as $value){
				echo "<tr id=\"order-info\" data-num=\"$value->order_num\">\n";
				echo "<td>".$value->order_num."</td>";
				if($value->user_id){
					echo "<td>".get_user_by('id',$value->user_id)->user_login."</td>";
				}else{
					echo "<td>游客</td>";
				}
				echo "<td><a target='_blank' href='".get_permalink($value->post_id)."'>".get_the_title($value->post_id)."</a></td>\n";
				echo "<td>".$value->order_price."</td>\n";
				$statusno = ($value->status == 0) ? 'selected="selected"' : '' ;
				$statusyes = ($value->status == 1) ? 'selected="selected"' : '' ;
				echo '<td><select class="select" id="status" name="status" disabled>
				    <option '.$statusno.' value="0">
				        未支付
				    </option>
				    <option '.$statusyes.' value="1">
				        已支付
				    </option>
				</select></td>';
				echo '<td>'.date('Y-m-d h:i:s',$value->create_time).'</td>';
				echo '<td><a href="'.admin_url('admin.php?page=wppay_orders_page&id='.$value->id).'">操作/详情</a></td>';
				
				echo "</tr>";
			}
		}
		else{
			echo '<tr><td colspan="6" align="center"><strong>没有订单</strong></td></tr>';
		}
	?>
	</tbody>
	</table>
    <?php echo c_admin_pagenavi($total,$perpage);?>
    <script>
            jQuery(document).ready(function($){

            });
	</script>
</div>
