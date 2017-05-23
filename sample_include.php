<?php

// We need to include 

include 'config.php';
p::initialize();

p::Out("
<style>

.n1, .n3, .n5, .n7{
	background: #000;
}

.n2, .n4, .n6, .n8{
	background: #fff;
}


.graphcanvas h3{
	text-align: center;
	margin: 0;
	padding-bottom: 3px;
	border-bottom: 1px solid black;
}

.graphcanvas{
	background: white;
	padding: 30px;
	border: 1px solid black;
}

.graph{
	display: flex;
	border: 1px solid;	
}

.bar{
	padding-top: 15px;
	padding-bottom: 15px;
	text-align: center;
	align-items: stretch;
}

.bar.n1{width: 97.8%;}
.bar.n2{width: 2.2%;}
.bar.n3{width: 89%;}
.bar.n4{width: 11%;}
.bar.n5{width: 60.2%;}
.bar.n6{width: 39.8%;}
.bar.n7{width: 89.2%;}
.bar.n8{width: 10.8%;}

.legenda{
	text-align: left;
	width: 100%;
}

.box{
	height: 14px;
	font-size: 13px;
	display: inline-block;
	margin-right: 10px;
	margin-top: 5px;
}

.data{
	font-size: 10px;
	background: #fff;
	padding: 3px;
	border: 1px solid black;
	display: inline-block;
	color: black;
}

.bar.n2 .data{
	margin-left: 7px
}

.box .block{
	width: 14px;
	height: 14px;
	border: 1px solid black;
	display: inline-block;
}

</style>
<div class='markdown-body home-margin'>
<br />
	<div class='graphcanvas'>
		<h3>Det tvärspråkliga förhållandet hos adverbiella konstruktioner *</h3>
		<h4>nederländska → svenska</h4>
		<div class='graph'>
			<div class='bar n1'>
				<div class='data'>97,8%</div>
			</div>
			<div class='bar n2'>
				<div class='data'>2,2%</div>
			</div>
		</div>
		<h4>svenska → nederländska</h4>
		<div class='graph'>
			<div class='bar n3'>
				<div class='data'>89%</div>
			</div>
			<div class='bar n4'>
				<div class='data'>11%</div>
			</div>
		</div>
		<div class='legenda'><br />
			<div class='box'>
					<div class='block n1'></div>
					adverbiell
			</div>
			<div class='box'>
					<div 	class='block n2'></div>
					pronominell
			</div>
		</div>
	</div> <br />

	<div class='graphcanvas'>
		<h3>Det tvärspråkliga förhållandet hos pronominella konstruktioner *</h3>
		<h4>nederländska → svenska</h4>
		<div class='graph'>
			<div class='bar n5'>
				<div class='data'>60,2%</div>
			</div>
			<div class='bar n6'>
				<div class='data'>39,8%</div>
			</div>
		</div>
		<h4>svenska → nederländska</h4>
		<div class='graph'>
			<div class='bar n7'>
				<div class='data'>89,2%</div>
			</div>
			<div class='bar n8'>
				<div class='data'>10,8%</div>
			</div>
		</div>
		<div class='legenda'><br />
			<div class='box'>
					<div class='block n1'></div>
					pronominell
			</div>
			<div class='box'>
					<div 	class='block n2'></div>
					adverbiell
			</div>
		</div>
	</div>


</div><br /><br />

");

(new pMainTemplate)->renderMinimal();