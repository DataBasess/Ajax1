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
    <h1 class="text-center">Hello World</h1>

<ul>
  <li><strong>list</strong> item 1 - one strong tag</li>
  <li><strong>list</strong> item <strong>2</strong> -
    two <span>strong tags</span></li>
  <li>list item 3</li>
  <li>list item 4</li>
  <li>list item 5</li>
  <li>list item 6</li>
</ul>

    <div class="container">
        <div class="row">
            <div class="col-md-offset-5 col-md-2">
                
                    <form action="" method="POST" class="form-horizontal" role="form">
                        <div class="form-group">
                            <legend class="text-center">Login</legend>
                        </div>
                        <div class="form-group">
                            <input type="username" name="" id="username" class="form-control" value="" required="required" pattern="" title="">
                        </div>
                        <div class="form-group">
                            <input type="password" name="" id="password" class="form-control" required="required" title="">
                        </div>


                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>

                


            </div>
        </div>
    </div>

    <form>
  





    <!-- jQuery -->
    <script src="//code.jquery.com/jquery.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="Hello World"></script>

    <script>


        /* Global JavaScript File for working with jQuery library */  
        // execute when the HTML file's (document object model: DOM) has loaded
        $(document).ready(function() {    /* USERNAME VALIDATION */    // use element id=username 
               // bind our function to the element's onblur event
            $( "li" ).filter( ":even" ).css( "background-color", "red" );
              
            $('#username').blur(function() {      // get the value from the username field                              
                    
                var username = $('#username').val();          // Ajax request sent to the CodeIgniter controller "ajax" method "username_taken"
                     // post the username field's value
                    
                $.post('/index.php/ajax/username_taken',        {
                        'username': username
                    },         // when the Web server responds to the request
                          
                    function(result) {         // clear any message that may have already been written
                                
                        $('#bad_username').replaceWith('');                  // if the result is TRUE write a message to the page
                                
                        if (result) {          
                            $('#username').after('<div id="bad_username" style="color:red;">' +             '<p>(That Username is already taken. Please choose another.)</p></div>');        
                        }      
                    }    );  
            });  
        });

    </script>
</body>

</html>
