<?php 

   $success = include('src/calculation.php');
   if (!$success) {
       echo "File can't be loaded!";
   }   

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tip Calculator</title>
    <link rel="stylesheet" type="text/css" href="css/stylesheet.css">
    <!--<link rel="stylesheet" type="text/css" href="../../css/bootstrap.css">
    <script type="text/javascript" src="../../js/jquery-3.1.1.min.js"></script>
    <script src="../../js/bootstrap.js"></script>
    <script src="https://code.jquery.com/jquery-1.11.0.js" integrity="sha256-zgND4db0iXaO7v4CLBIYHGoIIudWI5hRMQrPB20j0Qw=" crossorigin="anonymous"></script>-->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <!--JQuery-->
    <!--<script src="https://code.jquery.com/jquery-1.11.0.js" integrity="sha256-zgND4db0iXaO7v4CLBIYHGoIIudWI5hRMQrPB20j0Qw=" crossorigin="anonymous"></script>-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <!--[if lt IE 9]>      <script src="https://oss.maxcdn.com/libs/html5shiv/ ➥3.7.0/html5shiv.js"></script>      <script src="https://oss.maxcdn.com/libs/respond.js/ ➥1.4.2/respond.min.js"></script>    <![endif]-->
</head>

<body>
    <div class="container">
        
            <div class="row">
                <!--Start of header-->
                <div class="col-md-4 col-md-offset-4 col-xs-12 col-sm-8 col-sm-offset-2 topheader">
                    <h6>Tip Calculator</h6>
                </div>
                <!--End of header-->
                <!--Star of outer frame-->
                <div class="col-md-4 col-md-offset-4 col-xs-12 col-sm-8 col-sm-offset-2 outbox">
                   <!--Start of form--> 
                   <form class="form-horizontal" action="tip_calculator.php" method="POST" >
                        
                        <div class="form-group row">
                        <h3>Tip Calculator</h3>
                        
                        <label for="nameField" <?php if(  $errSubTotal == 1 ) { echo 'style="color:red"';} ?> class="col-xs-5 move">Bill subtotal:</label>
                        <div class="input-group col-xs-5" >
                            <span class="input-group-btn" >
                                <select class="btn input-sm"  name= "currency" >
                                    <option <?php if( strcmp($currency , "dollar") == 0 ) {echo "selected";} ?> value="dollar">$</option>
                                    <option <?php if( strcmp($currency , "euro") == 0 ) {echo "selected";} ?> value="euro">€</option>
                                    <option <?php if( strcmp($currency , "pound") == 0 ) {echo "selected";} ?> value="pound">£</option>
                                </select>                          
                            </span>
                            <div><input type="text" class="form-control input-sm" name="subtotalText" id="nameField" <?php if(  $errSubTotal == 1 ) { echo 'style="color:red"';} ?>
                              value="<?php if( $subTotalText > 0  ) { echo floatToFormatFloat($subTotalText, $currency ); 
                                 } else if ( $default == 1) { echo floatToFormatFloat($subTotalDefault, $currencyDefault ); } else if( $errSubTotal == 1 ){ echo $errorSubTotalText; } ?>"/>
                           </div> 
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-12 move">
                                <br/>
                                <label class="col-form-label">Tip percentage:</label>
                            </div>
                            <!--Start of radio buttons-->
                            <div class="col-xs-12 move">
                                <?php echo displayRadioButtons( $inlineRadioOptionsValue ) ?>;
                                <br/>
                                                        
                               <label class="form-inline" <?php if(  $errorCustom == 1 ) { echo 'style="color:red"';} ?> >
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio" value="100"
                                            <?php if( $inlineRadioOptionsValue == 100 ) { echo "checked"; } ?> > Custom 
                                <!--End of radio buttons-->
                                <!--Start of text input custom percentage area-->
                                    <input type="text"  style ="width:75px;" name="customText" id="nameField" 
                                        value="<?php if( $inlineRadioOptionsValue == 100 && $customText > 0  ) { echo floatToFormatFloat($customText, $currency );  
                                          } else  if(  $errorCustom == 1 ) { echo $errorCustText; } ?>"/> %
                             
                                </label><br/>
                               <!--End of text input custom percentage area-->
                                
                               
                              <label class="form-inline"  <?php if(  $errorSplit == 1 ) { echo 'style="color:red"';} ?> >Split: 
                                
                                     <input type="text"  style ="width:75px;" name="splitText" id="nameField" 
                                         value="<?php if( $splitText > 0  ) { echo numberToFormatNumber( $splitText, $currency );  
                                          } else if ( $default == 1) { echo $splitDefault; } else if(  $errorSplit == 1 ){ echo $errorSplitText; } ?>"/> person(s)
                             
                                </label><br/>

                                                             
                                <!--Start of submit button-->
                                <div class="col-xs-12 subButton">
                                     <button type="submit" style="width:75px" class="btn btn-default" name="submit" value="submit" >Submit</button>
                                </div>
                                <!--End of submit button-->                         
                            </div>
                        <!--Start of area for result content-->
                        </div>
                            <?php echo $bill; ?>    
                       </div>
                         <!--End of area for result content--> 
                 </form>
            </div>
         </div>
    </div>
</body>

</html>
