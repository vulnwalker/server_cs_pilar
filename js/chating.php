<?php
$tipe = @$_GET['tipe'];
$cek = "";
$err = "";
$content = "";


if(!empty($tipe)){
  include "../include/config.php";
  foreach ($_POST as $key => $value) {
      $$key = $value;
  }
}


switch($tipe){

    case 'loadTable':{
      $getData = sqlQuery("select * from chating");
      while($dataChating = sqlArray($getData)){
        foreach ($dataChating as $key => $value) {
            $$key = $value;
        }

        if($status == "1"){
            $status = "PUBLISH";
        }else{
            $status = "NON PUBLISH";
        }
        $data .= "     <tr>
                          <td>$nama</td>
                          <td><img src='$gambar'  class='materialboxed' style='width:100px;height:100px;'></img> </td>
                          <td>$status</td>
                          <td class='text-right'>
                              <a onclick=updateChating($id) class='btn btn-simple btn-warning btn-icon edit'><i class='material-icons'>dvr</i></a>
                              <a onclick=deleteChating($id) class='btn btn-simple btn-danger btn-icon remove'><i class='material-icons'>close</i></a>
                          </td>
                      </tr>
                    ";
      }

      $tabel = "<table id='datatables' class='table table-striped table-no-bordered table-hover' cellspacing='0' width='100%' style='width:100%'>
          <thead>
              <tr>
                  <th>Nama</th>
                  <th>Gambar</th>
                  <th>Status</th>
                  <th class='disabled-sorting text-right'>Actions</th>
              </tr>
          </thead>
          <tbody>
            $data
          </tbody>
      </table>";
      $content = array("tabelChating" => $tabel);

      echo generateAPI($cek,$err,$content);
    break;
    }

     default:{
        ?>
        <script>
        var url = "http://"+window.location.hostname+"/api.php?page=chating";
        </script>
        <script src="js/chating.js"></script>
        <?php
          if(!isset($_GET['join'])){
              ?>
              <script src="https://cdn.socket.io/socket.io-1.2.0.js"></script>
              <script src="http://code.jquery.com/jquery-1.11.1.js"></script>
              <script>setMenuEdit("baru")</script>
              <meta http-equiv="refresh" content="20" >
              <div id="content">
        				<section>
        					<div class="section-body contain-lg">
        						<div class="row">
        							<div class="col-lg-12">
        								<div class="card">
        									<div class="card-body no-padding">
        										<div class="table-responsive no-margin">
                              <form id='formUserManagement' name="formUserManagement" action="#">
                                <table class="table table-striped no-margin table-hover blue" id='tabelBody'>
                                  <thead>
                                    <tr>
                                      <th>No</th>
                                      <th>Nama</th>
                                      <th>Waktu Join</th>
                                      <th>Status</th>
                                      <th>Action</th>
                                    </tr>
                                  </thead>
                                  <?php
                                      $queue = json_decode(file_get_contents("http://".$_SERVER['HTTP_HOST'].":3421/customer/queue"), true);
                                  ?>
                                    <tbody>
                                        <?php if (count($queue) > 0): ?>
                                            <?php foreach ($queue as $key => $data): ?>
                                                <tr>
                                                    <td><?php echo $data['id']; ?></td>
                                                    <td><?php echo $data['username']; ?></td>
                                                    <td><?php echo $data['join_at']; ?></td>
                                                    <td><?php echo $data['status']; ?></td>
                                                    <td>
                                                        <?php if ($data['status'] == "new"): ?>
                                                    <a href="pages.php?page=chating&join=<?php echo $data['username']; ?>" target="_blank">Chat!</a>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                        <tr>
                                            <td colspan="5">
                                                No customer in lobby.
                                            </td>
                                        </tr>
                                        <?php endif; ?>

                                    </tbody>
                                </table>
                              </form>
        										</div>
        									</div>
        								</div>
        							</div>
        						</div>
        					</div>
        				</section>
        			</div>
              <?php
          }else{


            $room = null;
            $headText = "";
            if (!empty($_SESSION['username']))
            {
                $room = $_SESSION['username'];
                $headText .= "You are ".$_SESSION['username'].".";
                if (!empty($_GET['join']))
                {
                  $room = $_GET['join'];
                  $headText .= "You want to talk with ".$_GET['join'].".";

              }

            }
            else
            {
                var_dump("Who are you?");
                die();
            }
            ?>

                    <script type='text/javascript' src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
                    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
                    <style>
                    .chat
                    {
                        list-style: none;
                        margin: 0;
                        padding: 0;
                    }

                    .chat li
                    {
                        margin-bottom: 10px;
                        padding-bottom: 5px;
                        border-bottom: 1px dotted #B3A9A9;
                    }

                    .chat li.left .chat-body
                    {
                        margin-left: 60px;
                    }

                    .chat li.right .chat-body
                    {
                        margin-right: 60px;
                    }


                    .chat li .chat-body p
                    {
                        margin: 0;
                        color: #777777;
                    }

                    .panel .slidedown .glyphicon, .chat .glyphicon
                    {
                        margin-right: 5px;
                    }
                    .panel {
                      margin-top: 60px;
                    }
                    .panel-body
                    {
                        overflow-y: scroll;
                        height:800px;
                    }

                    .url-preview {
                        cursor: pointer;
                    }

                    ::-webkit-scrollbar-track
                    {
                        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
                        background-color: #F5F5F5;
                    }

                    ::-webkit-scrollbar
                    {
                        width: 12px;
                        background-color: #F5F5F5;
                    }

                    ::-webkit-scrollbar-thumb
                    {
                        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
                        background-color: #555;
                    }
                    </style>
                    <div class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <!-- Start Modal -->
                                <div class="col-md-12">
                                  <div class="panel panel-primary">
                                      <div class="panel-heading">
                                          <span class="glyphicon glyphicon-comment"></span> Chat
                                      </div>
                                      <div class="panel-body">
                                          <ul class="chat chatlist">

                                          </ul>
                                      </div>
                                      <div class="panel-footer">
                                          <form action="">
                                              <div class="input-group">
                                                  <input id="message" type="text" class="form-control input-sm" placeholder="Type your message here..." autocomplete="off">
                                                  <span class="input-group-btn">
                                                      <button class="btn btn-primary" id="btn-chat">
                                                          Send</button>
                                                  </span>
                                              </div>
                                          </form>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <div class="row iframe-box" style="margin-top: 10px; display: none;">
                              <div class="col-md-12 well">
                                  <button type="button" class="close" data-dismiss="iframe-box" aria-label="Close" style="top: -10px;
                      position: relative;" onclick="closeIframe()"><span aria-hidden="true">&times;</span></button>


                                </div>
                            </div>
                            <!-- end row -->
                        </div>
                    </div>


                <script type="template" id="show-message-as-other">
                      <li class="left clearfix"><span class="chat-img pull-left">
                        <?php if (empty($_GET['join'])): ?>
                            <img src="//placehold.it/50/55C1E7/fff&amp;text=STAFF" alt="User Avatar" class="img-circle">
                        <?php else: ?>
                            <img src="//placehold.it/50/55C1E7/fff&amp;text=Cus" alt="User Avatar" class="img-circle">
                        <?php endif; ?>
                    </span>
                        <div class="chat-body clearfix">
                            <div class="header">
                                <strong class="primary-font username">xxx</strong> <small class="pull-right text-muted">
                                    <span class="glyphicon glyphicon-time"></span><span class="send_at">xxx</span></small>
                            </div>
                            <p class="message"></p>
                        </div>
                    </li>
                </script>

                <script type="template" id="show-message-as-sender">
                    <li class="right clearfix"><span class="chat-img pull-right">
                        <img src="//placehold.it/50/FA6F57/fff&amp;text=ME" alt="User Avatar" class="img-circle">
                    </span>
                        <div class="chat-body clearfix">
                            <div class="header">
                                <small class=" text-muted"><span class="glyphicon glyphicon-time"></span><span class="send_at">xxx</span></small>
                                <strong class="pull-right primary-font username">xxx</strong>
                            </div>
                            <p class="message"></p>
                        </div>
                    </li>
                </script>

                <script src="//cdn.socket.io/socket.io-1.2.0.js"></script>
                <script>
                    var socket = io('//<?php echo $_SERVER['HTTP_HOST']; ?>:3421');
                    var username = '<?php echo $_SESSION["username"]; ?>';
                    var room = '<?php echo $room; ?>';
                    var isStaff = <?php echo empty($_GET['join']) ? 'false' : 'true'; ?>;

                    socket.emit('login', {username: username, isStaff: isStaff});
                    socket.emit('subscribe', {room: room});

                    window.onbeforeunload = function() {
                        return "You're about to end your chat session, are you sure?";
                    };

                    $( window ).unload(function() {
                        socket.emit('unsubscribe', {room: room});
                        return "Bye now!";
                    });

                    $('form').submit(function(){
                        socket.emit('send', { room: room, message: $('#message').val()});
                        $('#message').val('');
                        return false;
                    });

                    socket.on('message', function(data){
                        var html = (data.sender == username) ? $("#show-message-as-sender") : $("#show-message-as-other");

                        var $html = $(html.html());

                        $html.find(".header .username").text(data.sender);
                        $html.find(".header .send_at").text(data.send_at);

                        data.message = $("<div/>").text(data.message).html();

                        if (data.isStaff)
                        {
                            data.message = '<span class="label label-primary">CS</span> ' + data.message;
                        }

                        var urlPattern = /(\b(https?):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim;
                        var result = data.message.match(urlPattern);
                        if (result)
                        {
                            for (var i=result.length-1; i>=0; i--) {
                                if (result[i].indexOf("#preview") > -1 && data.isStaff)
                                {
                                    data.message = data.message.replace(result[i], '<span class="label label-info url-preview">'+result[i].replace("#preview", "")+'</span>');
                                }
                            }

                        }

                        $html.find(".message").html(data.message);

                        // console.log(msg);
                        $('.chatlist').append($html);

                        scrollToBottom($('.panel-body')[0]);
                    });

                    $(".chatlist").on('click', '.url-preview', function(e){
                        var $this = $(this);
                        $('.iframe-box iframe').attr("src",$this.text());
                        $('.panel-body').height('150px');
                        $('.iframe-box').fadeIn();
                        scrollToBottom($('body')[0]);
                    });

                    function scrollToBottom($dom)
                    {
                        $dom.scrollTop = $dom.scrollHeight;
                    }
                    function closeIframe()
                    {
                        $('.iframe-box').hide();
                        $('.panel-body').height('480px');
                        scrollToBottom($('body')[0]);
                    }
                </script>
          <?php
          }
         ?>



<?php

     break;
     }

}


?>
