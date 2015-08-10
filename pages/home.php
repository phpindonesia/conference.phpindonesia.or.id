<?php
$db  = dbConn::getConnection();
?>
    <!-- Header Carousel -->
    <header id="myCarousel" class="carousel slide">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <!-- li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li -->
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            <div class="item active">
                <div class="fill" style="background-image:url('images/sabuga.jpg'); background-size: 100% 100%;"></div>
                <div class="carousel-caption">
                    <!-- h1>October 19-22, 2015</h1 -->
					<h2>Gedung Ganesha (Sabuga), Bandung</h2>
                    <h3>Join the most exciting year in PHP!</h3>
                    <a class="regbtn" href="#">REGISTER NOW</a><br><br>
                </div>
            </div>
        </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <span class="icon-prev"></span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="icon-next"></span>
        </a>
    </header>
    <style type="text/css">
	.page-logo{ background:#101010; padding-top:6px;}
	.idlogo{ text-align:center;
	vertical-align: middle;
	border-top:#000;
	}
	/*.img2:hover{ background:url("images/itinf-hover.png"); background-size:100% 100%;  }*/
   </style>
   
   	<link rel="stylesheet" type="text/css" href="plugins/popup/style.css">

<script>
function hover1(element) {
    element.setAttribute('src', 'images/zend-hover.png');
}
function unhover1(element) {
    element.setAttribute('src', 'images/zend-w.png');
}
function hover2(element) {
    element.setAttribute('src', 'images/itinf-hover.png');
}
function unhover2(element) {
    element.setAttribute('src', 'images/itinf.png');
}
function hover3(element) {
    element.setAttribute('src', 'images/cloudc-hover.png');
}
function unhover3(element) {
    element.setAttribute('src', 'images/cloudc.png');
}
function hover4(element) {
    element.setAttribute('src', 'images/php-hover.png');
}
function unhover4(element) {
    element.setAttribute('src', 'images/php.png');
}
</script>
<script type="text/javascript">
jQuery(document).ready(function($){
	var offset = 300,
		offset_opacity = 1200,
		scroll_top_duration = 700,
		$back_to_top = $('.btn btn-default');
	
	$back_to_top.on('click', function(event){
		event.preventDefault();
		$('body,html').animate({
			scrollTop: 0 ,
		 	}, scroll_top_duration
		);
	});

});
</script>
   <!-- Page Logo -->
   <div class="page-logo">
    <div class="container">
     <div class="col-log-1">
       <div class="idlogo">
      <img class="img1" src="images/zend-w.png" onmouseover="hover1(this);" onmouseout="unhover1(this);" width="30%"><h4 style="color:#ccc;">Zend International</h4>
       </div>
     </div>
     <div class="col-log-1">
       <div class="idlogo">
       <img class="img2" src="images/itinf.png" onmouseover="hover2(this);" onmouseout="unhover2(this);" width="30%"><h4 style="color:#ccc;">IT Infrastructure</h4>
     </div>
     </div>
     <div class="col-log-1">
       <div class="idlogo">
        <img class="img3" src="images/cloudc.png" onmouseover="hover3(this);" onmouseout="unhover3(this);" width="30%"><h4 style="color:#ccc;">Cloud Computing</h4>
      </div>
     </div>
     <div class="col-log-1">
       <div class="idlogo">
        <img class="img4" src="images/php.png" onmouseover="hover4(this);" onmouseout="unhover4(this);" width="30%"><h4 style="color:#ccc;">PHP Enterprise</h4>
      </div>
     </div>
	 </div>
   </div>


    <!-- Page Content -->
    <div class="container">

        <!-- Call to Action Section -->
        <style type="text/css">
@import "bourbon";

@import url(//fonts.googleapis.com/css?family=Oswald:400);
.chart {
  width: 200px;
  height: 200px;
  }
.doughnutTip {
	position:absolute;
  min-width: 20px;
  max-width: 150px;
  padding: 5px 15px;
  border-radius: 1px;
  background: rgba(0,0,0,.8);
  color: #ddd;
  font-size: 10px;
  text-shadow: 0 1px 0 #000;
  text-transform: uppercase;
  text-align: center;
  line-height: 1.3;
  letter-spacing: .06em;
  box-shadow: 0 1px 3px rgba(0,0,0,0.5);
  pointer-events: none;
  &::after {
      position: absolute;
      left: 50%;
      bottom: -6px;
      content: "";
      height: 0;
      margin: 0 0 0 -6px;
      border-right: 5px solid transparent;
      border-left: 5px solid transparent;
      border-top: 6px solid rgba(0,0,0,.7);
      line-height: 0;
  }
}
.doughnutSummary {
  position: absolute;
  top: 45%;
  left: 50%;
  color: #000;
  text-align: center;
  text-shadow: 0 -1px 0 #111;
  cursor: default;
}
.doughnutSummaryTitle {
  position: absolute;
  top: 50%;
  width: 100%;
  margin-top: -27%;
  font-size: 12px;
  letter-spacing: .06em;
}
.doughnutSummaryNumber {
  position: absolute;
  top: 50%;
  width: 100%;
  margin-top: -15%;
  font-size: 25px;
}
.chart path:hover { opacity: 0.65; }
		</style>
<h1><i>Who Will Attend?</i></h1>
<div class="col-md-3">
        <div class="well pie">
        <h4>Praktisi IT</h4>
            <div class="row" align="center">
                <div id="doughnutChart1" class="chart"></div>            </div>
                <hr />
                CEO, CTO, CIO, Project Manager, System Analyst, Others
        </div>
        </div>
<div class="col-md-3">
        <div class="well pie">
        <h4>Pelaku Industri</h4>
            <div class="row" align="center">
                <div id="doughnutChart2" class="chart"></div>
            </div>
             <hr />
                Finance, Insurance, Factory Mining, Travel, Hospital, Health Care, Other
        </div>
        </div>
        
        
        
        
        
        <div class="col-md-3">
        <div class="well pie">
        <h4>Sektor Pemerintahan</h4>
            <div class="row" align="center">
                <div id="doughnutChart3" class="chart"></div>
            </div>
            <hr />Pendidikan, Pertahanan, Pembangunan, Perdagangan, Komunikasi, Others
        </div>
        </div>
        <div class="col-md-3">
        <div class="well pie">
        <h4>Pelaksana Pendidikan</h4>
            <div class="row" align="center">
                <div id="doughnutChart4" class="chart"></div>
            </div>
            <hr />Lecture Student
        </div>
        </div>

<div style="clear:both;"></div>
<hr />
            <div class="row" style="padding:12px;" align="center">
                <h1>Setelah mengikuti Sesi Utama, maka perserta diarahkan untuk mengikuti Track sesuai dengan minat</h1>
        </div>
<hr />
        <div class="row" style="padding:12px;">
        <img src="<?php echo $host; ?>images/agenda.jpg" width="100%" />
        </div>
