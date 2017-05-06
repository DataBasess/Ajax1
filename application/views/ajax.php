<!DOCTYPE html>
<html lang="">
	<head>
		<title>Title Page</title>
		<meta charset="utf-8">
		<script type="text/javascript" src=" <?php echo base_url(); ?>js/jquert-3.2.1.js "></script>

		<script >

				//alert("OK");
					//แสดงป๊อบอัพ
				
					//เรียก id
				//$("input[name=bt]")
					//เรียกด้วย name
				//$("input[type=button]")
					//เรียกด้วย type
				//$(".bt")
					//เรียกด้วย class
			$(document).ready(function(){

				// get  index.php?dd=1$ddd=3  //ไม่ปลอดภัยเพราะเห็นurl
				// post index.php 				// ปลอดภัยเพราะซ่อน url
				// ajax
				/*$
				( document ).on( "click", "#bt1", function() {
  					alert( "Goodbye!" );  // jQuery 1.7+
					});
				*/

				
				
				$( document ).on( "click", "#bt1", function() {

					/*
  					$.get("ajax/get",{id:"1",val:"Anuson",name:"Databass"},function(res){
  										
  						alert(res); 
  						});


					});
					แบบget
					

					
					$.post("ajax/post",{id:"1",val:"Anusorn",name:"Databass"},function(res){
  										
  						alert(res); 
  						});


					});
					แบบpost
					
					*/
					$.ajax({
						url:"Ticket/select/1",
						//data:"id=1&name=anusorn&nickname=they",
						dataType:"json",
						type:"POST",
						success:function(res){
							$("#id").val(res.id);
							$("#name").val(res.product_name);
							/*$("#surname").val(res.surname);
							$("#age").val(res.age);
							//alert(res.val);

							$("span").html(res.id);
							$("input[name=name]").val(res.name);
							$("input[name=surname]").val(res.surname);
							$("input[name=age]").val(res.age);
							*/




						},
						error:function(err){
							alert("ERROR"+err);
						}
					});				

				});

				
				$( document ).on( "click", "#add", function(){
						
						//var edit =false;

						if ($("input[name=product_name]").val()=="") {
							$("input[name=product_name]").focus();
							return false;

						}

						if ($("input[name=price]").val()=="") {
							$("input[name=price]").focus();
							return false;

						}


						
						var i = $("#data tbody tr").length;
						

						$.ajax({
							url:"ajax/add",
							type:"POST",
							cache: false,
							data:"product_name="+$("input[name=product_name]").val()+"&price="+$("input[name=price]").val(),
							success:function(res){
							
								if(res=="ok"){
									i++;
									$(".showtotal").text(i);
									var html ="<tr><td>"+i+"</td><td>"+$("input[name=product_name]").val()+"</td><td>"+$("input[name=price]").val()+"</td></tr>";
									$(html).appendTo("#data tbody");

									$("input[name=product_name]").val("");
									$("input[name=price]").val("");
									alert("บันทึกข้อมูลเรียบร้อย");


								}



							},
							error:function(err){
								alert("ERROR"+err);
							}

						});
						
						
						
					});

				
			});
		</script>
		

		
	</head>
	<body >
		<h1 class="text-center">ทดสอบ ajax</h1>
		

		
		<input type="button" id="bt1" class="bt" name="bt" value="Click">
		
		<input type="text" id="id" name="text" value="">

		<input type="text" id="name" name="text" value="">
		<!--
		<input type="text" id="surname" name="text" value="">
		<input type="text" id="age" name="text" value="">
		-->
		<br>

		<label>id</label>
		<span type="text" id="id" name="id" value=""> </span>
		<br>
		<label>product_name</label>
		<input type="text" id="product_name" name="product_name" value="">
		<br>
		<label>price</label>
		<input type="text" id="price" name="price" value="">
		<br>
		<input type="button" class="add" id="add" value="เพิ่ม">
		<br>
		

		<p> <span class="showtotal"></span> </p>
		<div class="table-responsive">
			<table id="data" class="table table-hover">
				<thead>
					<tr>
						<th>ลำดับ</th>
						<th>สินค้า</th>
						<th>ราคา</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$i=1;
					if(count($rs)>0){
						foreach ($rs as $r) {

							echo"<tr>";
							echo"<td>".$i."</td>";
							echo"<td>".$r['product_name']."</td>";
							echo"<td>".$r['price']."</td>";
							echo"</tr>";
							$i++;
						}
						

					}

					 ?>
				</tbody>
			</table>
		</div>



		
	</body>
</html>