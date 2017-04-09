<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title><?php echo ($title); ?></title>

    <!-- Bootstrap core CSS -->
    <link href="/spider/Public/css/bootstrap.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="/spider/Public/css/ie10-viewport-bug-workaround.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="/spider/Public/css/blog.css" rel="stylesheet">

</head>

<body>

<div class="blog-masthead">
    <div class="container">
        <nav class="blog-nav">
            <a class="blog-nav-item " href="<?php echo U('index/index/');?>">Home</a>
            <a class="blog-nav-item active" href="#"><?php echo ($title); ?></a>
        </nav>
    </div>
</div>

<div class="container">

    <div class="blog-header">
        <h1 class="blog-title"><?php echo ($title); ?></h1>
        <p class="lead blog-description">This is a page to show what I get from the pdf.</p>
    </div>

    <div class="row">

        <div class="col-sm-12 blog-main">

            <div class="blog-post">
                <h2 class="blog-post-title"><?php echo ($title); ?></h2>


                <hr>
                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><h2><?php echo ($key); ?></h2>
                 <blockquote>
                    <p><?php echo ($vo); ?></p>
                 </blockquote><?php endforeach; endif; else: echo "" ;endif; ?>
                </div><!-- /.blog-post -->




        </div><!-- /.blog-main -->


    </div><!-- /.row -->

</div><!-- /.container -->

<footer class="blog-footer">

    <p> Made by <a>WeijunWang</a> using bootstrap template </a> </p>
    <p>
        <a href="#">Back to top</a>
    </p>
    <p style="font-family:Microsoft YaHei">京ICP备16052119号</p>
</footer>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="/spider/Public/js/jquery.min.js"></script>
<script src="/spider/Public/js/bootstrap.min.js"></script>
</body>
</html>