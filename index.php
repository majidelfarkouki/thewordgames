<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Jeux de mots</title>
      <link href="./app/assets/styles/app.css" rel="stylesheet" type="text/css">
      <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.css"/>
      <style>
         .center_div{
         margin: 0 auto;
         width:80% /* value of your choice which suits your alignment */
         }
      </style>
      <!-- Main Angular -->
      <script src="./app/assets/js/angular/angular.js"></script>
      <!-- Angular Config -->
      <!-- Angular Config -->
      <script>
         var myApp = angular.module('myApp', []);
         
         myApp.controller('MyCtrl', function ($scope) {
                 $scope.IsVisible = false;
                 $scope.ShowHide = function(val1){
                     $scope.IsVisible = val1;
                 }
         });
      </script>
   </head>
   <body class="dashboard-4" ng-app="myApp" ng-controller="MyCtrl">
      <div class="slim-header">
         <div class="container" style="max-width: 75% !important;">
            <div class="slim-header-left">
               <h2 class="slim-logo"><a>Jeux de mots<span>.</span></a></h2>
            </div>
            <div class="slim-header-right">
            </div>
         </div>
      </div>
      <div class="slim-mainpanel">
         <div class="container pd-t-50" style="max-width: 75% !important;">
            <div class="card card-dash-chart-one ">
               <div class="row no-gutters">
                  <div class="col-lg-8">
                     <div class="left-panel" id="info">
                        <nav class="nav">
                           <a href="#" class="nav-link active">Jeux de mots</a>
                        </nav>
                        <div class="active-visitor-wrapper">
                           <div class="container center_div">
                              <form id="gotermrel"  method="post"
                                 action="index.php">
                                 <div class="form-group mb-2">
                                    Le terme
                                 </div>
                                 <div class="form-group mx-sm-3 mb-2">
                                    <input id="gotermrel" type="text" class="form-control input-sm"
                                       name="gotermrel" placeholder="Entrez le terme" require>
                                 </div>
                                 <div class="form-group mb-2">
                                    Num&eacute;ro de relation
                                 </div>
                                 <div class="form-group mx-sm-3 mb-2">
                                    <input id="rel" type="text" class="form-control input-sm" name="rel" size=10
                                    placeholder="Entrez le numéro de la relation">
                                 </div>
                                 <button id="gotermsubmit" type="submit" class="btn btn-primary btn-sm mb-2"
                                    name="gotermsubmit" value="Chercher">Chercher</button>
                              </form>
                           </div>
                        </div>
                        <!-- active-visitor-wrapper -->
                     </div>
                     <!-- left-panel -->
                  </div>
                  <!-- col-4 -->
                  <div class="col-lg-4">
                     <div class="right-panel">
                        <h6 class="slim-card-title">Options</h6>
                        <!--<div class="form-group">
                           <label>Num&eacute;ro de relation</label>
                           <input id="rel" type="text" class="form-control" name="rel" size=10
                              placeholder="Entrez le numéro de la relation">
                        </div>-->
                        <div class="form-group form-check">
                           <input type="checkbox" class="form-check-input" id="relout" name="relout" ng-model="relout">
                           <label class="form-check-label" for="relout">Pas de relations sortantes</label>
                        </div>
                        <div class="form-group form-check">
                           <input type="checkbox" class="form-check-input" id="relin" name="relin" ng-model="relin">
                           <label class="form-check-label" for="relin">Pas de relations entrantes</label>
                        </div>
                        <a href="http://www.jeuxdemots.org/jdm-about-detail-relations.php" class="card-link">Types de relations</a>   
                     </div>
                     <!-- right-panel -->
                  </div>
                  <!-- col-8 -->
               </div>
               <!-- row -->
            </div>
            <?php
               if (isset($_REQUEST['gotermsubmit'])) {
                   $gotermrel = $_POST['gotermrel'];
                   $numRel = $_POST['rel'];  
                   
                   $url = "http://www.jeuxdemots.org/rezo-dump.php?gotermsubmit=Chercher&gotermrel=" . $gotermrel . "&rel=".$numRel;
               
                   $ch = curl_init($url);
                   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
               
                   $str = curl_exec($ch);
                   curl_close($ch);
               
                   $array1 = explode("<CODE>", $str);
                   $array2 = explode("</CODE>", $array1[1]);
                   $page_content = explode("\n", $array2[0]);
               
                   $var1 = null;
                   $var2 = null;
                   $var3 = null;
                   $var4 = null;
                   $var5 = null;
                   $var6 = null;
               
                   $text1 = "// les types de noeuds (Nodes Types) : nt;ntid;'ntname'";
                   $text2 = "// les noeuds/termes (Entries) : e;eid;'name';type;w;'formated name' ";
                   $text3 = "// les types de relations (Relation Types) : rt;rtid;'trname';'trgpname';'rthelp' ";
                   $text4 = "// les relations sortantes : r;rid;node1;node2;type;w ";
                   $text5 = "// les relations entrantes : r;rid;node1;node2;type;w ";
                   $text6 = "// END";
               
                   //utf8_encode_deep($page_content);
               
                   $var1 = array_search($text1, $page_content);
                   $var2 = array_search($text2, $page_content);
                   $var3 = array_search($text3, $page_content);
                   $var4 = array_search($text4, $page_content);
               
                   $length1 = ($var2 - 2) - ($var1 + 2) + 1;
                   $output1 = array_slice($page_content, $var1 + 2, $length1);
               
                   $result1 = null;
                   foreach ($output1 as $line1) {
                       foreach (explode("\n", $line1) as $row) { // split the content by return then newline
                           $result1[] = explode(";", $row); // split each row by semi-colon then space
                       }
                   }
               
                   $length2 = ($var3 - 2) - ($var2 + 2) + 1;
                   $output2 = array_slice($page_content, $var2 + 2, $length2);
               
                   $result2 = null;
                   foreach ($output2 as $line2) {
                       foreach (explode("\n", $line2) as $row) { // split the content by return then newline
                           $result2[] = explode(";", $row); // split each row by semi-colon then space
                       }
                   }
               
                   $length3 = ($var4 - 2) - ($var3 + 2) + 1;
                   $output3 = array_slice($page_content, $var3 + 2, $length3);
               
                   $result3 = null;
                   foreach ($output3 as $line3) {
                       foreach (explode("\n", $line3) as $row) { // split the content by return then newline
                           $result3[] = explode(";", $row); // split each row by semi-colon then space
                       }
                   }
               
                   $length4 = ($var5 - 2) - ($var4 + 2) + 1;
                   $output4 = array_slice($page_content, $var4 + 2, $length4);
               
                   $result4 = null;
                   foreach ($output4 as $line4) {
                       foreach (explode("\n", $line4) as $row) { // split the content by return then newline
                           $result4[] = explode(";", $row); // split each row by semi-colon then space
                       }
                   }
               
                   $length5 = ($var6 - 2) - ($var5 + 2) + 1;
                   $output5 = array_slice($page_content, $var5 + 2, $length5);
               
                   $result5 = null;
                   foreach ($output5 as $line5) {
                       foreach (explode("\n", $line5) as $row) { // split the content by return then newline
                           $result5[] = explode(";", $row); // split each row by semi-colon then space
                       }
                   }
               
                   $relout1 = "// les relations sortantes : r;rid;node1;node2;type;w ";
                   $relout2 = "// les relations entrantes : r;rid;node1;node2;type;w ";
               
                   $reloutlength = (array_search($relout2, $page_content) - 2) - (array_search($relout1, $page_content) + 2) + 1;
                   $outputrelout= array_slice($page_content, array_search($relout1, $page_content) + 2, $reloutlength); // retourne "a", "b", et "c"
               
                   $reloutArray = null;
                   foreach ($outputrelout as $line) {
                       foreach (explode("\n", $line) as $row) { // split the content by return then newline
                           //$reloutArray[] = explode(";", $row);  // split each row by semi-colon then space
                           $test = explode(";", $row);
                           if($test[5]>0)
                              $reloutArray[]= $test;
                       }
                   }
               
               
                   $relin1 = "// les relations entrantes : r;rid;node1;node2;type;w ";
                   $relin2 = "// END";
                   $relinlength = (array_search($relin2, $page_content) - 2) - (array_search($relin1, $page_content) + 2) + 1;
                   $outputrelin= array_slice($page_content, array_search($relin1, $page_content) + 2, $length5); // retourne "a", "b", et "c"
               
                   $relinArray = null;
                   foreach ($outputrelin as $line) {
                       foreach (explode("\n", $line) as $row) { // split the content by return then newline
                           //$relinArray[] = explode(";", $row); // split each row by semi-colon then space
                           $test = explode(";", $row);
                           if($test[5]>0)
                              $relinArray[]=$test;
                       }
                   }
               
               
               
                   $array3 = explode("<def>", $str);
                   $array4 = explode("</def>", $array3[1]);
                   $term_definitions = explode("\n", $array4[0]);
               }
               
               // The function
               function utf8_encode_deep(&$input)
               {
                   if (is_string($input)) {
                       $input = utf8_encode($input);
                   } else if (is_array($input)) {
                       foreach ($input as &$value) {
                           utf8_encode_deep($value);
                       }
               
                       unset($value);
                   } else if (is_object($input)) {
                       $vars = array_keys(get_object_vars($input));
               
                       foreach ($vars as $var) {
                           utf8_encode_deep($input->$var);
                       }
                   }
               }
               
               ?>
            <!-- card -->
            <div class="card card-table mg-t-20 mg-sm-t-30">
               <!-- card-header -->
               <div class="card-body">
                  <div class="card card-dash-chart-one ">
                     <div class="row no-gutters">
                        <div class="col-lg-6">
                           <div class="left-panel" id="info">
                              <div class="active-visitor-wrapper">
                                 <p><?php 
                                    if (isset($gotermrel) && $gotermrel != null){
                                       echo $gotermrel;
                                   }
                                    else {
                                       echo '';
                                    }
                                    ?></p>
                                 <p><?php 
                                    if (isset($numRel) && $numRel != null){
                                       echo $numRel;
                                    }                        
                                    else {
                                       echo '';
                                    }
                                    ?></p>
                              </div>
                           </div>
                        </div>
                        <div class="col-lg-6">
                           <div class="left-panel" id="info">
                              <h6 class="slim-card-title">Les définitions</h6>
                              <div class="active-visitor-wrapper">
                                 <button id="showdefinitions" class="btn btn-success btn-sm mb-2"
                                    name="showdefinitions" ng-click="ShowHide(true)">Afficher les définitions</button>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-- card -->
            <div class="card card-table mg-t-20 mg-sm-t-30" ng-show ="IsVisible">
               <div class="card-header">
                  <h6 class="slim-card-title">Les définitions</h6>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="ShowHide(false)">
                  <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <!-- card-header -->
               <div class="card-body">
                  <?php 
                     utf8_encode_deep($term_definitions);
                     if (empty($term_definitions)){
                        echo '<div class="alert alert-danger" role="alert">';
                        echo "Il n'y a pas de définition pour ce terme.";
                     echo '</div>';  
                     }
                     else {
                      array_filter($term_definitions);
                      foreach($term_definitions as $def){
                                echo $def;
                      }
                     }
                     ?>
               </div>
            </div>
         </div>
         <div class="container pd-t-30" style="max-width: 75% !important;">
            <div class="row">
               <div class="col-sm-6" ng-hide="relout">
                  <div class="card">
                     <div class="card-body">
                        <h5 class="card-title">Les relations sortantes</h5>
                        <div class="table-responsive">
                           <table class="table table-striped table-bordered" id="relOut">
                              <thead>
                                 <tr>
                                    <td>noeud1</td>
                                    <td>noeud2</td>
                                    <td>typeRel</td>
                                    <td>poidsRel</td>
                                 </tr>
                              </thead>
                              <?php 
                                 utf8_encode_deep($reloutArray);
                                 utf8_encode_deep($result2);
                                 utf8_encode_deep($result3);
                                   foreach ($reloutArray as $row)
                                  {
                                     $node1 = null;
                                     $node2 = null;
                                     $relationType=null;
                                 
                                     foreach($result2 as $line){
                                        if($line[1] == $row[2]){
                                            $node1 = trim($line[2],"''");
                                        }
                                        if($line[1] == $row[3]){
                                            $node2 = trim($line[2],"''");
                                       }
                                    }

                                    foreach($result3 as $index){
                                       if($index[1] == $row[4]){
                                          $relationType=trim($index[2],"''");
                                       }
                                    }
                                      echo '<tr>';
                                    
                                          echo '<td>'.$node1.'</td>';
                                          echo '<td><a href="http://www.jeuxdemots.org/rezo-dump.php?gotermsubmit=Chercher&gotermrel='.$node2.'">'.$node2.'</a></td>';
                                          echo '<td>'.$relationType.'</td>';
                                          echo '<td>'.$row[5].'</td>';
                                              
                                      echo '</tr>';
                                  } 
                                  ?>    
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-sm-6" ng-hide="relin">
                  <div class="card">
                     <div class="card-body">
                        <h5 class="card-title">Les relations entrantes</h5>
                        <div class="table-responsive">
                           <table class="table table-striped table-bordered" id="relIn">
                              <thead>
                                 <tr>
                                    <td>noeud1</td>
                                    <td>noeud2</td>
                                    <td>typeRel</td>
                                    <td>poidsRel</td>
                                 </tr>
                              </thead>
                              <?php
                                 utf8_encode_deep($reloutArray);
                                 utf8_encode_deep($result2);
                                 utf8_encode_deep($result3);
                                foreach ($relinArray as $row)
                               {
                                  $node1 = null;
                                  $node2 = null;
                                  $relationType = null;
                                 
                                 foreach($result2 as $line){
                                    if($line[1] == $row[2]){
                                        $node1 = trim($line[2],"''");
                                    }
                                    if($line[1] == $row[3]){
                                        $node2 = trim($line[2],"''");
                                    }
                                }

                                foreach($result3 as $index){
                                   if($index[1] == $row[4]){
                                      $relationType=trim($index[2],"''");
                                   }
                                }
                                   echo '<tr>';
                                 
                                       echo '<td><a href="http://www.jeuxdemots.org/rezo-dump.php?gotermsubmit=Chercher&gotermrel='.$node1.'">'.$node1.'</a></td>';
                                       echo '<td>'.$node2.'</td>';
                                       echo '<td>'.$relationType.'</td>';
                                       echo '<td>'.$row[5].'</td>';
                                           
                                   echo '</tr>';
                               } 
                                    
                              /*
                                 foreach ($relinArray as $row)
                                 {
                                    echo '<tr>';
                                  
                                        echo '<td>'.$row[1].'</td>';
                                        echo '<td>'.$node1.'</td>';
                                        echo '<td>'.$node2.'</td>';
                                        echo '<td>'.$row[4].'</td>';
                                        echo '<td>'.$row[5].'</td>';
                                            
                                    echo '</tr>';
                                 } */
                                 ?>    
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="slim-footer">
         <div class="container">
            <p>Copyright 2019 &copy; All Rights Reserved. Universit&eacute; de Montpellier</p>
            <p>Designed by: <a href="https://www.linkedin.com/in/abdelmajid-el-farkouki/">ABDELMAJID EL FARKOUKI</a></p>
         </div>
         <!-- container -->
      </div>
      <!-- JeuxDeMots-footer -->
      <!-- Bootstrap and jQuery core JavaScript -->
      <script src="./app/assets/js/jquery/jquery-2.1.1.min.js"></script>
      <script src="./app/assets/js/bootstrap/js/bootstrap.min.js"></script>
      <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.20/datatables.min.js"></script>
      <script type="text/javascript" >
         $(document).ready(function() {
            $('#relOut').DataTable();
            $('#relIn').DataTable();
         });
      </script>
   </body>
</html>