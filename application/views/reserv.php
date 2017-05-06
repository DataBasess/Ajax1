<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Title Page</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

    
</head>


<body>
    
    <div class="container">
        <div class="col-md-3">
            <div class="trip-info">

                <div class="trip-info-box">
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
                </div>




            </div>
        </div>
    </div>
    
   





    <!-- jQuery -->
    <script src="//code.jquery.com/jquery.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="Hello World"></script>
</body>

</html>
