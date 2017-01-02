<?php 
    
    // Set the display the default value 
    if(!isset($_COOKIE["default"])) {
        setcookie("default", "0");
        $default = 1; 
     } else { 
        $default = $_COOKIE["default"];
     }

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tip Calculator</title>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
    <!--<link rel="stylesheet" type="text/css" href="../../css/bootstrap.css">
    <script type="text/javascript" src="../../js/jquery-3.1.1.min.js"></script>
    <script src="../../js/bootstrap.js"></script>
    <script src="https://code.jquery.com/jquery-1.11.0.js" integrity="sha256-zgND4db0iXaO7v4CLBIYHGoIIudWI5hRMQrPB20j0Qw=" crossorigin="anonymous"></script>-->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <!--JQuery-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <!--[if lt IE 9]>      <script src="https://oss.maxcdn.com/libs/html5shiv/ ➥3.7.0/html5shiv.js"></script>      <script src="https://oss.maxcdn.com/libs/respond.js/ ➥1.4.2/respond.min.js"></script>    <![endif]-->
</head>

<body>

   <?php 
            
            $errSubTotal = 0;
            $errPercentage = 0;
            $errorCustom = 0;
            $errorSplit = 0;

            if ( $default == 0) {  
                            
              // Validation the subtotal            
              if(isset($_POST["subtotalText"])){
                $subTotalValue = $_POST["subtotalText"];

                // remove ',' separator from currency
                if ( preg_match( "/^([1-9]{1,3})+(\,[0-9]{3})+(\.[0-9]{2})?$/", $subTotalValue )) {
                    $subTotalValue = preg_replace('/,/', '', $subTotalValue);
                } 

                if ( is_numeric($subTotalValue) && $subTotalValue > 0 ){   
                    $subTotalText = $subTotalValue;
                    $errSubTotal = 0; 
                } else {
                    $subTotalText = 0;
                    $subTotalValue = 0;            
                    $errSubTotal = 1; 
                }

            } else {
                $subTotalText = 0;
                $subTotalValue = 0; 
                $errSubTotal = 1; 
            } 
            
            // Validation the percentage
            if(isset($_POST["inlineRadioOptions"]) && $_POST["inlineRadioOptions"] != 100){
                $percentageValue = $_POST["inlineRadioOptions"];
                $inlineRadioOptionsValue = $_POST["inlineRadioOptions"];
                $errPercentage = 0;
                $errorCustom = 0;
            } else if( isset($_POST["inlineRadioOptions"]) && $_POST["inlineRadioOptions"] == 100 && isset($_POST["customText"])) {
                $percentageValue = $_POST["customText"];
                $inlineRadioOptionsValue = $_POST["inlineRadioOptions"];
                if ( preg_match("/^[0-9]+(\.[0-9]{1,2})?$/", $percentageValue ) && $percentageValue <= 100  ) {
                    $customText = $percentageValue;
                    $errorCustom = 0;
                    $errPercentage = 0;
                } else {
                    $customText = 0;
                    $percentageValue = 0;
                    $errorCustom = 1;
                }
            } 
                
              // Validation the split
              if(isset($_POST["splitText"])) {
                 // remove ',' separator form fomated number
                 $splitValue = $_POST["splitText"]; 
                 if ( preg_match("/^([1-9]{1,3})+(\,[0-9]{3})?$/", $splitValue )) {
                     $splitValue = preg_replace('/,/', '', $splitValue);
                 } 
     
                 if (  preg_match("/^[0-9]+$/", $splitValue ) ) {
                     $splitText = $splitValue;
                     $errorSplit = 0; 
                 } else {
                    $splitText = 0;
                    $splitValue = 0;
                    $errorSplit = 1;
                 }
             } else {
                 $splitText = 0;
                 $splitValue = 0;
                 $errorSplit = 1;
             }  
   
             // Proccesing the bill
             if( ( $subTotalValue > 0 && $percentageValue > 0 && $splitValue > 0 ) 
               || ( !isset($_POST["subtotalText"]) && !isset($_POST["inlineRadioOptions"]) && !isset($_POST["splitText"])))  {
               
                // Cookie processing ( set cookie or  get cookie )
                if ( $subTotalValue > 0 && $percentageValue > 0 && $splitValue > 0 ) {     
                    setcookie("default", "0", time() + 180);
                    setcookie( "subtotalValue", $subTotalValue, time() + 180 ); 
                    setcookie( "inlineRadioOptionsValue", $inlineRadioOptionsValue, time() + 180 );       
                    setcookie( "percentageValue", $percentageValue, time() + 180 );
                    setcookie( "splitValue", $splitValue, time() + 180 );
                } else if( !isset($_POST["subtotalText"]) && !isset($_POST["inlineRadioOptions"]) && !isset($_POST["splitText"])){
                    $subTotalValue = $_COOKIE["subtotalValue"];
                    $subTotalText =  $subTotalValue;
                    $inlineRadioOptionsValue = $_COOKIE["inlineRadioOptionsValue"];
                    $percentageValue = $_COOKIE["percentageValue"];
                    if ($inlineRadioOptionsValue == 100 ) { $customText = $percentageValue;}
                    $splitValue = $_COOKIE["splitValue"];
                    $splitText = $splitValue;
                    $errSubTotal = 0;
                    $errPercentage = 0;
                    $errorCustom = 0;
                    $errorSplit = 0;
                }

               // Caculation the payment and tip
               $tip = $subTotalValue * $percentageValue / 100;
               $total = $subTotalValue + $tip; 
               $tipEach = $tip / $splitValue;
               $totalEach = $total / $splitValue;
               $tip = number_format($tip,2);
               $total = number_format($total,2);
               $tipEach = number_format($tipEach,2);
               $totalEach = number_format($totalEach ,2);


              // Display receipt
               $bill= ''; 
               if ( $splitText > 1 ) {
                    $bill = $bill.'<div  id="multiple" ><div>Tip: $'.$tip.'</div>';
                    $bill = $bill.'<div>Total: $'.$total.'</div>';
                    $bill = $bill.'<div>Tip each: $'.$tipEach.'</div>';
                    $bill = $bill.'<div>Total each: $'.$totalEach.'</div><div>';
               } else if( $splitText == 1 ) {
                   $bill = $bill.'<div  id="multiple" ><div>Tip: $'.$tip.'</div>';
                   $bill = $bill.'<div>Total: $'.$total.'</div><div>';  
               }        
           } 
       } 
      
    ?>

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
                            <span class="input-group-addon input-sm" >$</span>
                            <div><input type="text" class="form-control input-sm" name="subtotalText" id="nameField" 
                                   value="<?php if( $subTotalText > 0  ) { echo number_format($subTotalText,2); 
                                     } else if ( $default == 1) { echo "100"; } else { echo ""; } ?>"/>
                           </div> 
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-12 move">
                                <br/>
                                <label class="col-form-label">Tip percentage:</label>
                            </div>
                            <!--Start of radio buttons-->
                            <div class="col-xs-12 move">
                                <label class="form-check-inline" <?php if( $errPercentage == 1 ) { echo 'style="color:red"';} ?> >
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio" value="10" 
                                           <?php if( $inlineRadioOptionsValue == 10 || $default == 1) { echo "checked"; }?> > 10%
                                </label>
                                <label class="form-check-inline" <?php if( $errPercentage == 1 ) { echo 'style="color:red"';} ?> >
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio" value="15"
                                           <?php if( $inlineRadioOptionsValue == 15 ) { echo "checked"; } ?> > 15%
                                </label>
                                <label class="form-check-inline" <?php if( $errPercentage == 1 ) { echo 'style="color:red"';} ?> >
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio" value="20"
                                           <?php if( $inlineRadioOptionsValue== 20 ) { echo "checked"; } ?> > 20%
                                </label><br/>
                                                        
                               <label class="form-inline" <?php if(  $errorCustom == 1 ) { echo 'style="color:red"';} ?> >
                                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio" value="100"
                                            <?php if( $inlineRadioOptionsValue == 100 ) { echo "checked"; } ?> > Custom 
                                <!--End of radio buttons-->
                                <!--Start of text input custom percentage area-->
                                     <input type="text"  style ="width:75px;" name="customText" id="nameField" 
                                         value="<?php if( $inlineRadioOptionsValue == 100 && $customText > 0  ) { echo number_format($customText,2);  
                                          } else { echo ""; } ?>"/> %
                             
                                </label><br/>
                               <!--End of text input custom percentage area-->
                                
                               
                              <label class="form-inline"  <?php if(  $errorSplit == 1 ) { echo 'style="color:red"';} ?> >Split: 
                                
                                     <input type="text"  style ="width:75px;" name="splitText" id="nameField" 
                                         value="<?php if( $splitText > 0  ) { echo number_format($splitText,0);  
                                          } else if ( $default == 1) { echo "1"; } else { echo ""; } ?>"/> person(s)
                             
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
