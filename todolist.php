<?php
$passlist=array( 'hogehoge' => 'hogepass', 'hoge2' => 'hoge2pass');

$user=$_POST['user'];
$pass=$_POST['pass'];
$page=$_POST['count'];
$data=$_POST['data'];

//データベース
$hostname='localhost';
$username='root';
$password='root';

$dbname="tododb";
$tablename='todotable';

$link = mysqli_connect($hostname,$username,$password);
if(! $link){ exit("Connect error!"); }

//userに値が入っていない場合
if(!isset($_POST['user']))
{
    echo_auth_page("ログイン");
    exit;
}

//$passlistに値がはいっていない||入力したパスワードが合っていない場合
if( (!isset($passlist[$user])) || $passlist[$user] != $pass)
{
    var_dump($user);
    var_dump($pass);
    echo_auth_page("パスワードが違います");
    exit;
}

//入力したパスワードがあっている場合
if($passlist[$user] == $pass)
{


    //データベースの作業
    //データベース、テーブルを作成
    if($data == "createdb"){
        $result=mysqli_query($link,"CREATE DATABASE $dbname CHARACTER SET utf8");
        if(!$result) { echo "Create database $dbname failed!\n"; }

        $result=mysqli_query($link,"USE $dbname");
        if(!$result) { exit("USE failed!"); }

        $result=mysqli_query($link,"CREATE TABLE $tablename (id INT NOT NULL AUTO_INCREMENT, day DATE, task VARCHAR(10) BINARY,dolimit VARCHAR(10) BINARY, PRIMARY KEY(id)) CHARACTER SET utf8");
        if(!$result) { 
            echo "Create table $tablename failed!\n"; 
            exit;
        }

        $data = "NULL";
        echo "データベースを作成しました";
    }
    //データベースを削除
    if($data == "dropdb"){

        $result=mysqli_query($link,"DROP DATABASE $dbname");
        if(!$result) { 
            echo "DROP database $dbname failed!\n"; 
            exit;
        }

        $data = "NULL";
        echo "データベースを削除しました";
    }

    //ログイン状態
    if( $page == "rogin")
    {
    echo_hello_page($user);
    exit;
    }
    //マイページに１度入っている場合
    if( $page == 2)
    {
     trans_conplete_new();
    }
    

}



////////////////////////////////////////////////////////////////////////
function echo_auth_page($msg)
{
echo <<<EOT
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="Content-Style-Type" content="text/css">
        <title>ログイン画面</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">    
    </head>
    <body>
    <h1>$msg</h1>
    <form method="POST" action="todolist.php">	
        username <input type="text" name="user" value=""><br>
        password <input type="password" name="pass" value=""><br>
        <input type="hidden" name="count" value="rogin">
        <button type="submit" name="login" value="login">Login</button>
    </form>
    <br>
    username = hogehoge
    <br>
    password = hogepass
    </body>
</html>
EOT;
}
////////////////////////////////////////////////////////////////////////
function echo_hello_page($who)
{
$user=$_POST['user'];
$pass=$_POST['pass'];
$page = "rogin";


echo <<<EOT
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="Content-Style-Type" content="text/css">
        <title>マイページ</title>
    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">   
    <style>
    body {
        padding-top: 50px;
        background-color: skyblue;
    }
    .starter-template {
        padding: 30px 15px;
        background-color: white;
    }            
    </style>
    </head>

    <body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Todoリスト</a>
			</div>
			<div id="navbar" class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li class="active"><a href="#">Home</a></li>
					<li><a href="#list">List</a></li>
					<li><a href="#form">Form</a></li>
				</ul>
			</div>
			<!--/.nav-collapse -->
		</div>
    </nav>

    <div class="container">
    <div class="starter-template">   
    
    <h1>こんにちは $who さん</h1>
<br>

    <h2 id="table">Todoリスト</h2>
    <table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>登録日</th>
            <th>タスク名</th>
            <th>タスク期限</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>11月10日</td>
            <td>Webプロ課題</td>
            <td>11月22日</td>
        </tr>
        <tr>
            <td>2</td>
            <td>11月23</td>
            <td>ES完成させる</td>
            <td>12月1日</td>
        </tr>
        <tr>
            <td>3</td>
            <td>11月25日</td>
            <td>試験勉強</td>
            <td>12月10日</td>
        </tr>
        <tr>
            <td>4</td>
            <td>11月25日</td>
            <td>バイト</td>
            <td>11月26日</td>
        </tr>
    </tbody>
</table>


    <h2 id="form">入力フォーム</h2>
    <form class="form-horizontal" action="#" method="post">
        <div class="form-group">
            <label class="col-sm-2 control-label" for="title">タイトル</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="comment" id="title">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="message">タスク内容</label>
            <div class="col-sm-10">
                <textarea class="form-control" rows="3"></textarea>
            </div>
        </div>
        <div class="form-group">
        <form method="POST" action="todolist.php">
	    <input type="hidden" name="user" value="$user">
        <input type="hidden" name="pass" value="$pass">
        <input type="hidden" name="count" value="$page">
	　　<button type="submit" name="Btn1" value="Btn1">新規投稿</button>
        </form>

        </div>
    
    </div>
        </form>
        <br>
        <br>
        
        <form method="POST" action="todolist.php">
	    <input type="hidden" name="user" value="$user">
        <input type="hidden" name="pass" value="$pass">
        <input type="hidden" name="count" value="$page">
        <button type="submit" name="data" value="createdb">データベースを作成</button>               
        </form>
        <br>

        <form method="POST" action="todolist.php">
	    <input type="hidden" name="user" value="$user">
        <input type="hidden" name="pass" value="$pass">
        <input type="hidden" name="count" value="$page">
        <button type="submit" name="data" value="dropdb">データベースを削除</button>               
        </form>
        <br>
        <a class="btn btn-primary" href="#">Topに戻る</a>

<br>

 </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    </body>
</html>
EOT;
}
?>