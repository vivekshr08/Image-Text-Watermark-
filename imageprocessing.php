<?php 
	session_start();
?>
<link href="assets/css/bootstrap-colorpicker.min.css" rel="stylesheet">
<!--<link href="https://itsjavi.com/bootstrap-colorpicker/docs/assets/main.css" rel="stylesheet">-->
<script src="assets/scripts/jquery-3.1.1.min.js"></script>
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>-->
<script src="assets/scripts/bootstrap-colorpicker.js"></script>
<script>
$(document).ready(function(){ 
	i=1;
	$("#backbtn").click(function(){
		//alert('Go Back and upload new image');	
		window.location.href="index.php";
	});
	
	$(function () {
	  $('#cp6').colorpicker({
		  color: "#88cc33",
		  horizontal: true,
		  format: 'rgb'
	  });
	});
});

function watermark()
{
	//alert('1');
	var text = $("#inputtext").val();
	var textfont = $("#inputfont").val();
	var textlinechar = $("#newlinechars").val();
	var textsize = $("#fontsize").val();
	var color = $(".colpic").val();
	var imgname = "<?php print($_SESSION['IMGPATH']);?>";
	var textangle = $("#textangle").val();
	var textopacity = $("#textopacity").val();
	var textposition = $("#textposition").val();
	//alert(text+' ## '+textfont+' ## '+textlinechar+' ## '+ textsize+' ## '+color+' ## '+imgname);
	$("#processedimg").html('<img src="assets/imgs/loading-1.gif?'+Math.random()+'">');
	$.post( "process.php", { text: text, textfont: textfont, textlinechar: textlinechar, textsize: textsize, color: color, imgname: imgname, textangle: textangle, textopacity: textopacity, textposition: textposition}, 
	function( data )
	{
		if(data)
		{	
			//alert('Process Data=>' +data);
			//var imgs = "<img src=\""+data+"\" width=\"80%\">";
			//alert('Process New Image=>' +imgs);
			$("#processedimg").html('<img src="'+data+'?'+Math.random()+'" width="80%">');
			i++;
		}
		//data = '';
	});
}
</script>

<div style="width:100%; height:auto; border:1px solid #fff; overflow:hidden;">
	<div style=" width:100%; margin:5px 0 20px 0; text-align:center; font-style:italic;">
    	<h2>Image Processing with text</h2>
    </div>
    
	<div style="width:60%; height:auto; float:left; border-right:1px solid #fff; text-align:center; vertical-align:middle;" id="processedimg">
		<img src="<?php print($_SESSION['IMGPATH']);?>" width="100%">
	</div>
    
	<div style="width:39%; height:auto; float:right;">
    	 <p>
         	<label for="inputtext"><i><strong>Watermark Text</strong></i> : </label>
         	<br/>
            <input name="inputtext" id="inputtext" type="text" style="width: 60%; height:25px;" placeholder="Input Text" onchange="watermark()">
            <br /><span  id="inputerrmsg" class="pure-form-message-inline" style="color:red;"><i></i></span>
          </p>
          <p> 
             <label for="inputtext"><i><strong>Characters in Line</strong></i> : </label>
             <br />
             <select name="newlinechars" id="newlinechars" style="width: 30%; height:25px;" onchange="watermark()">
            	<option value="15">15</option>
                <option value="20" selected="selected">20</option>
                <option value="25">25</option>
                <option value="30">30</option>
                <option value="35">35</option>
                <option value="40">40</option>
             </select>
             <br />
           </p>
           
           <p> 
             <label for="inputtext"><i><strong>Font</strong></i> : </label>
             <br />
             <select name="inputfont" id="inputfont" style="width: 30%; height:25px;" onchange="watermark()">
            	<option value="Arial">Arial</option>
                <option value="Georgia">Georgia</option>
                <option value="Verdana">Verdana</option>
            </select>
             <br />
           </p>
           
           <p> 
             <label for="inputtext"><i><strong>Font Size</strong></i> : </label>
             <br />
             <select name="fontsize" id="fontsize" style="width: 30%; height:25px;" onchange="watermark()">
            	<?php for($i=6; $i<=100; $i++){?>
                <option value="<?php print($i);?>" <?php if($i==40){?>selected<?php }?>><?php echo $i.' px';?></option>
                <?php } ?>
             </select>
             <br />
           </p>
           
           <p> 
             <label for="Angle"><i><strong>Angle</strong></i> : </label>
             <br />
             <select name="textangle" id="textangle" style="width: 30%; height:25px;" onchange="watermark()">
            	<?php for($i=0; $i<=100; $i=$i+5){?>
                <option value="<?php print($i);?>" <?php if($i==0){?>selected<?php }?>><?php echo $i;?></option>
                <?php } ?>
            </select>
             <br />
           </p>
           
           <p> 
             <label for="Opacity"><i><strong>Opacity</strong></i> : </label>
             <br />
             <select name="textopacity" id="textopacity" style="width: 30%; height:25px;" onchange="watermark()">
            	<?php for($i=100; $i>=0; $i--){?>
                <option value="<?php print($i);?>" <?php if($i==100){?>selected<?php }?>><?php echo $i;?></option>
                <?php } ?>
            </select>
             <br />
           </p>
           
           <p> 
             <label for="inputtext"><i><strong>Color</strong></i> : </label>
             <br />
             <input type="text" name="colornumber" class="colpic" style="width: 30%; height:25px;" id="cp6" onchange="watermark()"/>
             <br />
           </p>
           
            <p> 
             <label for="Position"><i><strong>Position</strong></i> : </label>
             <br />
             <select name="textposition" id="textposition" style="width: 30%; height:25px;" onchange="watermark()">
            	<option value="11">Top Left</option>
                <option value="12">Top Center</option>
                <option value="13">Top Right</option>
                <option value="21">Middle Left</option>
                <option value="22" selected="selected">Middle Center</option>
                <option value="23">Middle Right</option>
                <option value="31">Bottom Left</option>
                <option value="32">Bottom Center</option>
                <option value="33">Bottom Right</option>
            </select>
             <br />
           </p>
           
	</div>

</div>
<div style="width:100%; margin:0 auto; text-align:center; padding-top:15px;"><input type="button" name="backbtn" id="backbtn" value="Upload New Image" style="    width: 135px; height: 32px; background-color: #000; text-align: center; color: #FFF; font-weight: bold; border-bottom-style: outset;"></div>