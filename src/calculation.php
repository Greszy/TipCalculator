<?php 
    
    // default values - Subtotal , Split , Currency
    $subTotalDefault = '100.00';
    $splitDefault = '1';
    $currencyDefault = 'dollar';
    $percentageDefault = '10';
    
    // error handling variables
    $errSubTotal = 0;
    $errPercentage = 0;
    $errorCustom = 0;
    $errorSplit = 0;
    
    // cookie expire time in seconds
    $expireTime = 180;
     
    // Set the page for display - default or other
    if(!isset($_COOKIE["default"])) {
        setcookie("default", "0");
        $default = 1; 
        $inlineRadioOptionsValue = $percentageDefault;
     } else { 
        $default = $_COOKIE["default"];
     }
     
    // set the currency
    
    if(isset($_POST["currency"])){
        $currency = $_POST["currency"];
    } else {
        $currency = $currencyDefault;
    }

   // convert formated float to float number ( remove seperator and set decimal point as "."
   function formatFloatToFloat( $curValue) {
       // dollar, pound currency notation to number
       if ( preg_match( "/^([0-9]{1,3})+(\,[0-9]{3})+(\.[0-9]{2})?$/", $curValue )) {
           return preg_replace('/,/', '', $curValue);
       } 
       // euro currency notation to number   
       if ( preg_match( "/^([0-9]{1,3})+(\.[0-9]{3})+(\,[0-9]{2})?$/", $curValue )) {
           $temp = preg_replace('/\./', '', $curValue);
           return  preg_replace('/,/', '.', $temp);
       } else if (preg_match( "/^\d{1,10}(\,\d{1,2})?$/", $curValue )){
           return  preg_replace('/,/', '.', $curValue); 
       }      
       return  $curValue;
   }
   
   // convert float number to formated float  ( set separator and decimal point based on currency)
   function floatToFormatFloat( $numValue, $curr ) {
       switch ( $curr ) {
           case "dollar":  
               return  number_format($numValue,2,'.',',');
           case "pound":  
               return  number_format($numValue,2,'.',',');
           case "euro":  
               return  number_format($numValue,2,',','.');
           default:
               return number_format($numValue,2,'.',',');
       }   
   }
   
   // remove separator and set decimal point based on currency location
   function formatNumberToNumber( $splitVal) {
       // US, UK location
       if ( preg_match("/^([1-9]{1,3})+(\,[0-9]{3})?$/", $splitVal )) {
           return preg_replace('/,/', '', $splitVal);
       } 
       // EURO location
       if ( preg_match("/^([1-9]{1,3})+(\.[0-9]{3})?$/", $splitVal )) {
           return preg_replace('/\./', '', $splitVal);
       } 
       return  $splitVal;
   }
  
   //  covert number to format based on currency
   function numberToFormatNumber( $numValue, $curr ) {
       switch ( $curr ) {
           case "dollar":  
               return  number_format($numValue,0,'.',',');
           case "pound":  
               return  number_format($numValue,0,'.',',');
           case "euro":  
               return  number_format($numValue,0,',','.');
           default:
               return number_format($numValue,0,'.',',');
       }   
   }

   // display currency sign
   function displayCurrencySign( $curr ) {
       switch ( $curr ) {
           case "dollar":  
               return  "$";
           case "pound":  
               return "£"; 
           case "euro":  
               return "€"; 
           default:
               return "$";    
      }
   }

     //  radio button display
     function displayRadioButtons( $inlineRadioOptionsValue ) {
          $choices = array('10', '15', '20');
          $length = sizeof( $choices );
          $output = '';
          for($i=0; $i<$length; $i++ ) { 
              $output = $output.'<label class="form-check-inline"  >';
              $output = $output.'<input class="form-check-input" type="radio" name="inlineRadioOptions" ';
              $output = $output.'id="inlineRadio" value="'.$choices[$i].'" ';
              if(  $inlineRadioOptionsValue == $choices[$i] )  { $output = $output.'checked';}
              $output = $output.' >'.$choices[$i].'%'; 
              $output = $output.'</label>'; 
          }
          return $output;
     }     
      
       // if default = 0 validate the values to display no-default page 
       // else default = 1 displayed the page including default values                
       if ( $default == 0) {  
                            
              // Validation the subtotal            
              if(isset($_POST["subtotalText"])){
                $subTotalValue = $_POST["subtotalText"];
                $subTotalValue = formatFloatToFloat($subTotalValue, $currency);
                if ( is_numeric($subTotalValue) && $subTotalValue > 0 ){   
                    $subTotalText = $subTotalValue;
                    $errSubTotal = 0; 
                } else {
                    $subTotalText = 0;;
                    $subTotalValue = 0;            
                    $errSubTotal = 1; 
                    $errorSubTotalText = $_POST["subtotalText"];
                }

            } else {
                $subTotalText = 0;
                $subTotalValue = 0; 
                $errSubTotal = 1;
                $errorSubTotalText = "";
                 
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
                $percentageValue = formatFloatToFloat($percentageValue);
                if ( is_numeric($percentageValue) && $percentageValue > 0 ){           
                    $customText = $percentageValue;
                    $errorCustom = 0;
                    $errPercentage = 0;
                } else {
                    $customText = 0;
                    $percentageValue = 0;
                    $errorCustom = 1;
                    $errorCustText = $_POST["customText"];
                }
            } 
             
              // Validation the split
              if(isset($_POST["splitText"])) {
                 // remove ',' separator form fomated number
                 $splitValue = $_POST["splitText"]; 
                 $splitValue = formatNumberToNumber($splitValue); 
                 if (  preg_match("/^[0-9]+$/", $splitValue ) && $splitValue > 0 ) {
                     $splitText = $splitValue;
                     $errorSplit = 0; 
                 } else {
                    $splitText = 0;
                    $splitValue = 0;
                    $errorSplit = 1;
                    $errorSplitText = $_POST["splitText"];
                 }
             } else {
                 $splitText = 0;
                 $splitValue = 0;
                 $errorSplit = 1;
                 $errorSplitText = "";
             }  
   
             // Proccesing the bill
             if( ( $subTotalValue > 0 && $percentageValue > 0 && $splitValue > 0 ) 
               || ( !isset($_POST["subtotalText"]) && !isset($_POST["inlineRadioOptions"]) && !isset($_POST["splitText"])))  {
               
                // Set cookie for last correct bill
                if ( $subTotalValue > 0 && $percentageValue > 0 && $splitValue > 0 ) {     
                    setcookie("default", "0", time() + $expireTime);
                    setcookie( "subtotalValue", $subTotalValue, time() + $expireTime ); 
                    setcookie( "inlineRadioOptionsValue", $inlineRadioOptionsValue, time() + $expireTime );       
                    setcookie( "percentageValue", $percentageValue, time() + $expireTime );
                    setcookie( "currency", $currency, time() + $expireTime );
                    setcookie( "splitValue", $splitValue, time() + $expireTime );
                // Get bill from cookie    
                } else if( !isset($_POST["subtotalText"]) && !isset($_POST["inlineRadioOptions"]) && !isset($_POST["splitText"])){
                    $subTotalValue = $_COOKIE["subtotalValue"];
                    $subTotalText =  $subTotalValue;
                    $inlineRadioOptionsValue = $_COOKIE["inlineRadioOptionsValue"];
                    $percentageValue = $_COOKIE["percentageValue"];
                    if ($inlineRadioOptionsValue == 100 ) { $customText = $percentageValue;}
                    $splitValue = $_COOKIE["splitValue"];
                    $currency = $_COOKIE["currency"];
                    $splitText = $splitValue;
                    $errSubTotal = 0;
                    $errPercentage = 0;
                    $errorCustom = 0;
                    $errorSplit = 0;
                }

               // Calculation the payment and tips
               $tip = $subTotalValue * $percentageValue / 100;
               $total = $subTotalValue + $tip; 
               $tipEach = $tip / $splitValue;
               $totalEach = $total / $splitValue;
              
              // Display receipt
               $bill= ''; 
               if ( $splitText > 1 ) {
                    $bill = $bill.'<div  id="multiple" ><div>Tip: '.displayCurrencySign( $currency ).floatToFormatFloat($tip, $currency).'</div>';
                    $bill = $bill.'<div>Total: '.displayCurrencySign( $currency ).floatToFormatFloat($total, $currency).'</div>';
                    $bill = $bill.'<div>Tip each: '.displayCurrencySign( $currency ).floatToFormatFloat($tipEach, $currency).'</div>';
                    $bill = $bill.'<div>Total each: '.displayCurrencySign( $currency ).floatToFormatFloat($totalEach, $currency).'</div><div>';
               } else if( $splitText == 1 ) {
                   $bill = $bill.'<div  id="multiple" ><div>Tip: '.displayCurrencySign( $currency ).floatToFormatFloat($tip, $currency).'</div>';
                   $bill = $bill.'<div>Total: '.displayCurrencySign( $currency ).floatToFormatFloat($total, $currency).'</div><div>';  
               }        
           } 
       } 
     
   ?>