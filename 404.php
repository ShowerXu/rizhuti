<?php get_header(); ?>
<style type="text/css">
	:root{
	  --width: 800px;
	  --height: 600px;
	}

	body{
	  background: rgba(218, 223, 225);
	  font-family: 'Lato', sans-serif;
	}

	.explorer{
	  width: var(--width);
	  height: var(--height);
	  background: #ffffff;
	  border-radius: 10px;
	  box-shadow: 0px 0px 25px rgba(0, 0, 0, .1);
	  position: relative;
	  overflow: hidden;

	  margin: 50px auto;
	}

	.explorer:before{
	  content: attr(data-url);
	  border-top: 30px solid #ebebeb;
	  border-right: 60px solid #ebebeb;
	  border-bottom: 15px solid #ebebeb;
	  border-left: 10px solid #ebebeb;
	  color: rgba(0, 0, 0, .7);
	  padding: 5px;
	  width: 100%;
	  border-radius: 10px 10px 0 0;
	  position: absolute;
	  box-sizing: border-box;
	}

	.explorer:after{
	  content: '';
	  width: 14px;
	  height: 14px;
	  border-radius: 50%;
	  background: rgba(239, 72, 54, 1);
	  box-shadow: 20px 0 0 0 rgba(249, 191, 59, 1), 40px 0 0 0 rgba(38, 194, 129);
	  top: 8px; left: 10px;
	  position: absolute;
	}

	.explorer span{
	  font-size: 3em;
	  text-align: center;
	  position: absolute;
	  top: 50%;
	  left: 50%;
	  transform: translate(-50%, -50%);
	  opacity: 0;
	  animation-fill-mode: forwards;
	}

	.explorer span:before{
	  content: '404';
	  font-size: 3em;
	  display: block;
	}

	@keyframes loading {
	  from{ box-shadow: calc(-1 * var(--width)) 3px 1px -1px rgba(89, 171, 227, .5); }
	  to{ box-shadow: 0 3px 1px -1px rgba(89, 171, 227, .7); }
	}

	@keyframes error {
	  from{ opacity: 0; }
	  to{ opacity: 1; }
	}
</style>
<?php global $wp; $current_url = home_url(add_query_arg(array(),$wp->request)); ?>
<section class="container">
	<div class="explorer" data-url="<?php echo $current_url; ?>">
      <span>没有内容</span>
    </div>
</section>
<script type="text/javascript">
const explorer = document.querySelector(".explorer");
let i = 0, data = "", url = explorer.getAttribute("data-url");

let typing = setInterval(() => {
  if(i == url.length){
    clearInterval(typing);
    document.styleSheets[0].insertRule('.explorer:before{ animation: loading 1s .5s; }', 0);
    document.styleSheets[0].insertRule('.explorer span{ animation: error 2s 1.5s; }', 0);
  }else{
    data += url[i];
    explorer.setAttribute("data-url", data);
    i++;
  }
}, 100);
</script>
<?php get_footer(); ?>