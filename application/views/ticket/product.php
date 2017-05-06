
<script>
   $(document).ready(function() {


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

                $('#categories').change(function() {

                    //alert("OK");
                    
                    $.ajax({
                        url: ' <?php echo base_url(); ?>Ticket/select/'+$('#categories').val(),
                        //data: {categories: $('#categories').val()},
                        type: 'POST',
                        dataType:"json",
                        success: function(data) {
                            $('#id').val(data.id);
                            //$('#product_name').val(data.product_name);
                        },
                        error:function(data){
                            alert('ERROR'+data);
                        }
                    });
                    
                    
                });
   });
</script>

<div class="col-md-3">
            <div class="trip-info">

                <div class="trip-info-box">
                        <input type="button" id="bt1" class="bt" name="bt" value="Click">
                        <br>
                        <select name="categories" id="categories">
                            <option value="">เลือกข้อมูลหมวดหมู่</option>
                            
                            <?php foreach($cate as $c){ ?>
                            <option value="<?php echo $c['id']; ?>" selected="selected">
                                    <?php echo $c['categorie_name']; ?>
                                </option>
                            <?php } ?>
                        </select>
                        <br>
                        <input type="text" id="id"              name="id"           value="">
                        <br>
                        <input type="text" id="product_name"    name="product_name" value="">     
                    
                <!--
                    <form action="" method="POST" role="form">

                        <h3>จองแพ็คเกจท่องเที่ยว</h3>
                        
                        <div class="form-group">
                            <label for="">เลือกแพ็คเกจ</label>
                            <select class="form-control form-select ajax-processed" id="edit-node-type" name="node_type">
                            <?php foreach($ticket as $t){ ?>
                            <option value="<?php echo $t['id_ticket']; ?>" selected="selected"><?php echo $t['name_ticket']; ?></option>
                            <?php } ?>
                        </select>                        
                        </div>
                        
                        
                        <div class="form-group">
                            <label for="">วันที่ : <?php echo $t['detail_ticket']; ?></label>
                            <input type="date" name="" id="date" class="form-control" value="" min="0" max="" step="1" required="required" title="">
                        </div>

                        <div class="form-group">
                            <label for="">ผู้สูงอายุ ราคาตั๋ว/ท่าน :<?php echo $t['price_older']; ?> บาท</label>
                            <input type="number" name="" id="older" class="form-control" value="1" min="0" max="" step="1" required="required" title="">
                        </div>
                        <div class="form-group">
                            <label for="">ผู้ใหญ่ ราคาตั๋ว/ท่าน :<?php echo $t['price_adult']; ?> บาท</label>
                            <input type="number" name="" id="adult" class="form-control" value="1" min="0" max="" step="1" required="required" title="">
                        </div>
                        <div class="form-group">
                            <label for="">เด็ก ราคาตั๋ว/ท่าน :<?php echo $t['price_kid']; ?> บาท</label>
                            <input type="number" name="" id="kid" class="form-control" value="1" min="0" max="" step="1" required="required" title="">
                        </div>

                        <div class="form-group">
                            <label for="">จำนวนตั๋วรวม</label>
                        </div>
                        <div class="form-group">
                            <label for="">ราคารวม</label>
                        </div>





                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>


                    -->
                </div>





            </div>
        </div>