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
	$viptype = isset($_POST['viptype']) && is_numeric($_POST['viptype']) ?intval($_POST['viptype']) :0;
	$daysnum = isset($_POST['daysnum']) && is_numeric($_POST['daysnum']) ?intval($_POST['daysnum']) :0;
	$usersuid = isset($_POST['usersuid']) && is_numeric($_POST['usersuid']) ?intval($_POST['usersuid']) :0;
	// // 写入usermeta
	if ($usersuid) {
		if ( true ) {
			//更新等级 
			if (update_user_meta( $usersuid, 'vip_type', $viptype )) {
				echo '<div id="message" class="updated notice is-dismissible"><p>会员类型更新成功</p></div>';
			}
		}

		if ($daysnum !=0) {
			$this_vip_time=get_user_meta($usersuid,'vip_time',true); //当前时间
		    $time_stampc = intval($this_vip_time)-time();// 到期时间减去当前时间
		    if ($time_stampc > 0) {
		        $nwetimes= intval($this_vip_time);
		    }else{
		        $nwetimes= time();
		    }
			update_user_meta( $usersuid, 'vip_time', $nwetimes+$daysnum*24*3600 );   //更新到期时间
			echo '<div id="message" class="updated notice is-dismissible"><p>更新成功，会员到期时间为：'.round($nwetimes+($daysnum*24*3600)).'</p></div>';
		}
	}
	
	unset($id);
}

// 内页
if($id && current_user_can('administrator'))
{
	$info=$wpdb->get_row("SELECT * FROM $wpdb->users WHERE ID = '".$id."'");

	if(!$info->ID)
	{
		echo '<div id="message" class="updated notice is-dismissible"><p>会员ID无效</p></div>';
		exit;
	}
	?>
	<div class="wrap">
   	<h2>查看/修改会员详情</h2>
<form method="post" action="<?php echo admin_url('admin.php?page=wppay_vip_page&action=save&id='.$id); ?>" style="width:70%;float:left;background-color: #fff;padding: 20px;">

        <table class="form-table">
            <tr>
                <td valign="top" width="30%"><strong>用户ID</strong><br />
                </td>
                <td><?php echo $info->user_login?></td>
            </tr>
            <tr>
                <td valign="top" width="30%"><strong>会员邮箱</strong><br />
                </td>
                <td><?php echo $info->user_email?>
                </td>
            </tr>
             <tr>
                <td valign="top" width="30%"><strong>会员昵称</strong><br />
                </td>
                <td><?php echo $info->display_name ?>
                </td>
            </tr>
            <tr>
                <td valign="top" width="30%"><strong>会员类型（当前：<?php echo vip_type_name($info->ID) ?>）</strong><br />
                </td>
                <td>
                	<input type="radio" name="viptype" id="viptype" value="0" <?php if(vip_type($info->ID)==0) echo "checked";?>/>普通会员 
                	<input type="radio" name="viptype" id="viptype" value="31" <?php if(vip_type($info->ID)==31) echo "checked";?>/>包月会员
                	<input type="radio" name="viptype" id="viptype" value="365" <?php if(vip_type($info->ID)==365) echo "checked";?>/>包年会员
                	<input type="radio" name="viptype" id="viptype" value="3600" <?php if(vip_type($info->ID)==3600) echo "checked";?>/>终身会员
                </td>
            </tr>
            
            <!-- 到期时间 -->
            <tr>
                <td valign="top" width="30%"><strong>会员到期时间</strong><br /></td>
                <td><?php echo vip_time($info->ID); ?> （从未开通过则显示当前时间）</td>
            </tr>

            <tr>
            <td valign="top" width="30%"><strong>增加会员天数</strong><br />
                </td>
                <td>
                	<input type="number" name="daysnum" id="daysnum" value="0" /> 可以按天数,-1则等于减去一天
                </td>
            </tr>

    </table>
        <br /> <br />
        <table> <tr>
        <td><p class="submit">
        	<input type="hidden" name="usersuid" id="usersuid" value="<?php echo $info->ID ?>" />
            <input type="submit" name="Submit" value="保存设置" class="button-primary"/>
            </p>
        </td>

        </tr> </table>

</form>
			</div>
	<?php
	exit;
}

// userlist
$total   = $wpdb->get_var("SELECT COUNT(id) FROM $wpdb->users WHERE user_status =0 ");
$perpage = 20;
$pages = ceil($total / $perpage);
$page=isset($_GET['paged']) ?intval($_GET['paged']) :1;
$offset = $perpage*($page-1);
$UserList = $wpdb->get_results("SELECT * FROM $wpdb->users WHERE user_status =0 ORDER BY ID DESC limit $offset,$perpage");


?>

<!-- 默认显示页面 -->
<div class="wrap">
	<h2>所有会员</h2>
	<div class="tablenav top">
	    <p class="search-box">
	<label class="screen-reader-text" for="post-search-input">搜索 :</label>
	<input type="search" id="post-search-input" name="s" value="">
	<input type="submit" id="search-submit" class="button" value="搜索"></p>

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
	                普通会员
	            </option>
	            <option class="level-0" value="1">
	                包月会员
	            </option>
	            <option class="level-0" value="2">
	                包年会员
	            </option>
	            <option class="level-0" value="3">
	                终身会员
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
				<th>用户ID</th>
				<th>会员邮箱</th>	
				<th>会员昵称</th>
				<th>会员类型</th>
				<th>VIP到期时间</th>
				<th>注册日期</th>
				<th>操作/详情</th>
			</tr>
		</thead>
		<tbody>
	<?php
		if($UserList) {
			foreach($UserList as $value){
				echo "<tr>";
				echo "<td>".$value->user_login."</td>";
				echo "<td>".$value->user_email."</td>";
				echo "<td>".$value->display_name."</td>";
				echo "<td>".vip_type_name($value->ID)."</td>";
				echo '<td>'.vip_time($value->ID).'</td>';
				echo "<td>".$value->user_registered."</td>";
				echo '<td><a href="'.admin_url('admin.php?page=wppay_vip_page&id='.$value->ID).'">操作/详情</a></td>';
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
