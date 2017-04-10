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

    <title>HomeWork</title>

    <link href="/spider/Public/css/bootstrap.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="/spider/Public/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/spider/Public/css/dashboard.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">DBMS HOMEWORK</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">HOMEWORK</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="#">Profile</a></li>
            <li><a href="#">Help</a></li>
          </ul>
          <form class="navbar-form navbar-right">
            <input type="text" class="form-control" placeholder="Search...">
          </form>
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
                <li class="active"><a href="#">导航栏<span style="font-size: small">（暂不启用）</span> <span class="sr-only">(current)</span></a>
                </li>
                <li><a href="#">link</a></li>
                <li><a href="#">link</a></li>
                <li><a href="#">link0</a></li>
            </ul>
            <ul class="nav nav-sidebar">
                <li><a href=""></a></li>
                <li><a href="">this is link</a></li>
                <li><a href="">this is link</a></li>
                <li><a href="">this is link</a></li>
                <li><a href="">this is link</a></li>
            </ul>
            <ul class="nav nav-sidebar">
                <li><a href="">some link</a></li>
                <li><a href="">some link</a></li>
                <li><a href="">some link</a></li>
            </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 class="page-header">Plant</h1>

          <div class="row placeholders">
            <div class="col-xs-6 col-sm-3 placeholder">
              <img src="/spider/Public/image/data0.png" width="200" height="200" class="img-responsive" alt="Generic placeholder thumbnail">
              <h4>data0</h4>
              <span class="text-muted">待定...</span>
            </div>
            <div class="col-xs-6 col-sm-3 placeholder">
              <img src="/spider/Public/image/data0.png" width="200" height="200" class="img-responsive" alt="Generic placeholder thumbnail">
              <h4>data1</h4>
              <span class="text-muted">待定...</span>
            </div>
            <div class="col-xs-6 col-sm-3 placeholder">
              <img src="/spider/Public/image/data0.png" width="200" height="200" class="img-responsive" alt="Generic placeholder thumbnail">
              <h4>data2</h4>
              <span class="text-muted">待定...</span>
            </div>
            <div class="col-xs-6 col-sm-3 placeholder">
              <img src="/spider/Public/image/data0.png" width="200" height="200" class="img-responsive" alt="Generic placeholder thumbnail">
              <h4>data4</h4>
              <span class="text-muted">待定...</span>
            </div>
          </div>

          <h2 class="sub-header">plants list</h2>
          <div class="table-responsive">
            <table class="table table-striped">
              <thead>
                    <tr>
                        <th>Symbol</th>
                        <th>Scientific Name</th>
                        <th>Common Name</th>
                        <th>Fact Sheet</th>
                        <th>Plant Guide</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td><?php echo ($vo["Symbol"]); ?></td>
                            <td><a href="<?php echo U('index/showInfo/',array('science'=>$vo['Scientific_Name']));?>"><?php echo ($vo["Scientific_Name"]); ?></a></td>
                            <td><?php echo ($vo["Common_Name"]); ?></td>
                            <td><a href="<?php echo U('index/fact/',array('science'=>$vo['Scientific_Name'],id=>'fs'));?>"><?php echo ($vo["fs_pdf"]); ?></a></td>
                            <td><a href="<?php echo U('index/fact/',array('science'=>$vo['Scientific_Name'],id=>'pg'));?>"><?php echo ($vo["pg_pdf"]); ?></a></td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
   <script src="/spider/Public/js/jquery.min.js"></script>
<script src="/spider/Public/js/bootstrap.min.js"></script>
 <script src="/spider/Public/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>