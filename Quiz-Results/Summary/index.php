<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="logo.png" type="image/png" sizes="16x16">
  <script src="alasql.min.js"></script>

  <title>Bảng Tổng Kết Điểm</title>
  
  <?php
    // Declare array name and data Scores of Users 
    $arrayAllScores = array();
    $arrayNameQuiz = array();

    //$strArrayAllScores = [];

    $sizeOfArrayAllScores = 0;
    $sizeOfArrayNameQuiz = 0;
    $chooseQuizName = 1;

    // Directory of file Result
    $directory = "../result/";
    $dir = opendir($directory);
    while (($file = readdir($dir)) !== false) {
      if (substr($file, 0, 11) == "quiz_result"){
        $filename = $directory . $file;      
        $type = filetype($filename);
        if ($type == 'file') {
          $contents = file_get_contents($filename);          
          $con = fopen($filename,"r");
          $arrayTmp = array();
          while(! feof($con))
          {
            $strTmp = fgets($con);
            if (strpos($strTmp,":") != null){
              array_push($arrayTmp, $strTmp);
            }
            else{
              array_push($arrayTmp, $strTmp);
            }
            //$strArrayAllScores = $strArrayAllScores.substr($strTmp, 0, -2)."#.#";
            //echo substr($strTmp, 0, -2);
          }
          array_push($arrayAllScores, $arrayTmp);
        }
      }
    }
    $arrayNumberQuizName = array_fill(0, count($arrayAllScores), 0);
    for ($i=0; $i<count($arrayAllScores); $i++){
      $numberTmp = $arrayNumberQuizName[$i];
      for($j=$i+1; $j<count($arrayAllScores); $j++){
        if ($arrayAllScores[$i][0] == $arrayAllScores[$j][0]){
          $arrayNumberQuizName[$j] = $arrayNumberQuizName[$i];
        }
        else{
          $arrayNumberQuizName[$j] = $numberTmp + 1;
          $numberTmp = $numberTmp + 1;
        }
      }
    }

    if(count($arrayAllScores) > 0){
      array_push($arrayNameQuiz, $arrayAllScores[0][0]);
    }
    
    for($i=0; $i<count($arrayNumberQuizName)-1; $i++){
      if($arrayNumberQuizName[$i] != $arrayNumberQuizName[$i+1]){        
        array_push($arrayNameQuiz, $arrayAllScores[$i+1][0]);
      }
    }    
    closedir($dir);

    $sizeOfArrayAllScores = count($arrayAllScores);
    $sizeOfArrayNameQuiz = count($arrayNameQuiz);

    for($i=0; $i<count($arrayAllScores); $i++){
      $strArrayAllScores[$i]["Quiz Name"] = substr($arrayAllScores[$i][0], 0, -2);
      $strArrayAllScores[$i]["Name"] = substr($arrayAllScores[$i][1], 0, -2);
      $strArrayAllScores[$i]["Result"] = substr($arrayAllScores[$i][2], 0, -2);
      $strArrayAllScores[$i]["User Score"] = substr($arrayAllScores[$i][3], 0, -2);
      $strArrayAllScores[$i]["Passing Score"] = substr($arrayAllScores[$i][4], 0, -2);
      $strArrayAllScores[$i]["Quiz Taking Time"] = substr($arrayAllScores[$i][5], 0, -2);
      $strArrayAllScores[$i]["Quiz Finished At"] = substr($arrayAllScores[$i][6], 0, -2);
    }
    for($i=0; $i<count($arrayNameQuiz); $i++){
      $strArrayNameQuiz[$i] = substr($arrayNameQuiz[$i], 0, -2);
    }
    
    //echo json_encode($strArrayNameQuiz);
  ?>

  <style>
    body{
      font-family: arial, sans-serif;
    }

    table {
      font-family: arial, sans-serif;
      border-collapse: collapse;
      width: 100%;
    }

    td, th {
      border: 1px solid #dddddd;
      text-align: left;
      padding: 8px;
    }

    th{      
      background-color: #5acce6;
    }

    h1{
      text-align: center;
      color: #c40ea4;
    }

    tr:nth-child(even) {
      background-color: #dddddd;
    }

    #numberQuiz{
      font-weight: bold;
      font-size: 20px;
      color: #b51f35;
      padding: 2px 10px;
    }

    #lbNumberQuiz{
      font-style: italic;
      font-size: 20px;
      color: #2c60db;
      padding-left: 20px;
    }

    #divNumberQuiz{
      margin: 1px 10px;
    }

    #divSortQuiz{
      margin: 1px 10px;
      margin-bottom: 10px;
    }

    #sortQuiz{      
      font-weight: bold;
      font-size: 20px;
      color: #b51f35;
      padding: 2px 10px;
    }

    #lbSortQuiz{
      font-style: italic;
      font-size: 20px;
      color: #2c60db;
      padding-left: 20px;
    }
    /*
    #dataRowTable, tr, th{
      color: red;
    }
    */
  </style>

  <script>
    var chooseQuizName = '<?php echo $chooseQuizName; ?>';
    var sizeOfArrayAllScores = parseInt('<?php echo $sizeOfArrayAllScores; ?>');
    var sizeOfArrayNameQuiz = parseInt('<?php echo $sizeOfArrayNameQuiz; ?>');

    var arrayAllScores = '<?php echo json_encode($strArrayAllScores); ?>';
    arrayAllScores = JSON.parse(arrayAllScores);

    var arrayNameQuiz = '<?php echo json_encode($strArrayNameQuiz); ?>';
    arrayNameQuiz = JSON.parse(arrayNameQuiz);
  </script>

</head>
<body>
  <h1>BẢNG TỔNG KẾT ĐIỂM</h1>

  <div id="divNumberQuiz">
    <label for="numberQuiz" id="lbNumberQuiz">Chọn Tên Bài Thi:</label>
    <select id="numberQuiz" onchange="updateTable()">
      <option value="-1">Tất Cả</option>
    </select>
  </div>
  <br>
  <div id="divSortQuiz">
    <label for="sortQuiz" id="lbSortQuiz">Sắp Xếp:</label>
    <select id="sortQuiz" onchange="sortTable()">
      <option value="-1">---</option>
      <option value="0">Số Thứ Tự Thí Sinh</option>
      <option value="1">Điểm Số Giảm Dần</option>
    </select>
  </div>

  <!--
  <input type="button" name="clickme" id="exportCSV" onclick="exportCSVResult()" value="Xuất File CSV"/>
      -->

  <table id="dataRowTable">
    <tr>
      <th>STT</th>
      <th>Tên Bài Thi</th>
      <th>Tên Thí Sinh</th>
      <th>Kết Quả</th>      
      <th>Điểm Thí Sinh</th>      
      <th>Điểm Đạt</th>      
      <th>Thời Gian Hoàn Thành</th>      
      <th>Thời Điểm Hoàn Thành</th>
    </tr >
  </table>

  <script>
    var arrayDataExport = []
    var elementSelect = document.getElementById("numberQuiz");
    var strValueElementSelect = "<option value=\"-1\">Tất Cả</option>";
    for(i=0; i<sizeOfArrayNameQuiz; i++){
      strValueElementSelect += "<option value=\"" + i + "\">" + arrayNameQuiz[i].substring(arrayNameQuiz[i].indexOf(":")+2, arrayNameQuiz[i].length) + "</option>";
    }
    elementSelect.innerHTML = strValueElementSelect;

    var elementTable = document.getElementById("dataRowTable");
    var strValueElementTable = "";
    strValueElementTable += "<tr><th>STT</th><th>Tên Bài Thi</th><th>Tên Thí Sinh</th><th>Kết Quả</th><th>Điểm Thí Sinh</th><th>Điểm Đạt</th><th>Thời Gian Hoàn Thành</th><th>Thời Điểm Hoàn Thành</th></tr >";

    for(i=0; i<sizeOfArrayAllScores; i++){
      column1 = arrayAllScores[i]["Quiz Name"].substring(arrayAllScores[i]["Quiz Name"].indexOf(":")+2, arrayAllScores[i]["Quiz Name"].length);
      column2 = arrayAllScores[i]["Name"].substring(arrayAllScores[i]["Name"].indexOf(":")+2, arrayAllScores[i]["Name"].length);
      column3 = arrayAllScores[i]["Result"].substring(arrayAllScores[i]["Result"].indexOf(":")+2, arrayAllScores[i]["Result"].length);
      column4 = arrayAllScores[i]["User Score"].substring(arrayAllScores[i]["User Score"].indexOf(":")+2, arrayAllScores[i]["User Score"].length);
      column5 = arrayAllScores[i]["Passing Score"].substring(arrayAllScores[i]["Passing Score"].indexOf(":")+2, arrayAllScores[i]["Passing Score"].length);
      column6 = arrayAllScores[i]["Quiz Taking Time"].substring(arrayAllScores[i]["Quiz Taking Time"].indexOf(":")+2, arrayAllScores[i]["Quiz Taking Time"].length);
      column7 =  arrayAllScores[i]["Quiz Finished At"].substring(arrayAllScores[i]["Quiz Finished At"].indexOf(":")+2, arrayAllScores[i]["Quiz Finished At"].length)

      arrayDataExport.push([column1, column2, column3, column4, column5, column6, column7]);

      strValueElementTable += "<tr>";
      strValueElementTable += "<td>" + (i+1).toString(10) + "</td>";
      strValueElementTable += "<td>" + column1 + "</td>";
      strValueElementTable += "<td>" + column2 + "</td>";
      strValueElementTable += "<td>" + column3 + "</td>";
      strValueElementTable += "<td>" + column4 + "</td>";
      strValueElementTable += "<td>" + column5 + "</td>";
      strValueElementTable += "<td>" + column6 + "</td>";
      strValueElementTable += "<td>" + column7 + "</td>";
      strValueElementTable += "</tr>";
    }

    elementTable.innerHTML = strValueElementTable;

    function updateTable(){
      var serialNumber = 1;
      var elementTable = document.getElementById("dataRowTable");
      var elementSelect = document.getElementById("numberQuiz");
      var valueSelected = elementSelect.value;
      var strValueElementTable = "";
      
      strValueElementTable += "<tr><th>STT</th><th>Tên Bài Thi</th><th>Tên Thí Sinh</th><th>Kết Quả</th><th>Điểm Thí Sinh</th><th>Điểm Đạt</th><th>Thời Gian Hoàn Thành</th><th>Thời Điểm Hoàn Thành</th></tr >";

      for(i=0; i<sizeOfArrayAllScores; i++){
        if(valueSelected == -1){
          strValueElementTable += "<tr>";
          strValueElementTable += "<td>" + (i+1).toString(10) + "</td>";
          strValueElementTable += "<td>" + arrayDataExport[i][0] + "</td>";
          strValueElementTable += "<td>" + arrayDataExport[i][1] + "</td>";
          strValueElementTable += "<td>" + arrayDataExport[i][2] + "</td>";
          strValueElementTable += "<td>" + arrayDataExport[i][3] + "</td>";
          strValueElementTable += "<td>" + arrayDataExport[i][4] + "</td>";
          strValueElementTable += "<td>" + arrayDataExport[i][5] + "</td>";
          strValueElementTable += "<td>" + arrayDataExport[i][6] + "</td>";
          strValueElementTable += "</tr>";
        }
        else if(arrayNameQuiz[valueSelected].substring(arrayNameQuiz[valueSelected].indexOf(":")+2, arrayNameQuiz[valueSelected].length) == arrayDataExport[i][0]){
          strValueElementTable += "<tr>";
          strValueElementTable += "<td>" + (serialNumber).toString(10) + "</td>";
          strValueElementTable += "<td>" + arrayDataExport[i][0] + "</td>";
          strValueElementTable += "<td>" + arrayDataExport[i][1] + "</td>";
          strValueElementTable += "<td>" + arrayDataExport[i][2] + "</td>";
          strValueElementTable += "<td>" + arrayDataExport[i][3] + "</td>";
          strValueElementTable += "<td>" + arrayDataExport[i][4] + "</td>";
          strValueElementTable += "<td>" + arrayDataExport[i][5] + "</td>";
          strValueElementTable += "<td>" + arrayDataExport[i][6] + "</td>";
          strValueElementTable += "</tr>";
          serialNumber = serialNumber + 1;
        }
      }

      elementTable.innerHTML = strValueElementTable;
    }

    function sortTable(){
      var serialNumber = 1;
      var elementTable = document.getElementById("dataRowTable");
      var elementSelect = document.getElementById("numberQuiz");
      var valueSelected = elementSelect.value;

      var sortSelect = document.getElementById("sortQuiz");
      var sortSelected = sortSelect.value;
      var strValueElementTable = "";

      var arrayDataSort = arrayDataExport;
      /*
      if (arrayDataSort[2][1].substring(0, arrayDataSort[0][1].indexOf(". ")) == 24){
        alert("hi");
      }
      */

      for(i=0; i<sizeOfArrayAllScores; i++){
        for(j=i+1; j<sizeOfArrayAllScores; j++){
          if(valueSelected == -1){
            if(sortSelected == 0){
              let valueOne = arrayDataSort[i][1].substring(0, arrayDataSort[i][1].indexOf(". "));
              let valueTwo = arrayDataSort[j][1].substring(0, arrayDataSort[j][1].indexOf(". "));
              if(parseInt(valueOne) > parseInt(valueTwo)){
                var tmp = arrayDataSort[i];
                arrayDataSort[i] = arrayDataSort[j];
                arrayDataSort[j] = tmp;
              }
            }
            else if(sortSelected == 1){
              let valueOne = arrayDataSort[i][3].substring(0, arrayDataSort[i][3].indexOf(" / "));
              let valueTwo = arrayDataSort[j][3].substring(0, arrayDataSort[j][3].indexOf(" / "));
              if(parseInt(valueOne) < parseInt(valueTwo)){
                var tmp = arrayDataSort[i];
                arrayDataSort[i] = arrayDataSort[j];
                arrayDataSort[j] = tmp;
              }
            }
          }
          else{
            nameQuizOne = arrayNameQuiz[valueSelected].substring(arrayNameQuiz[valueSelected].indexOf(":")+2, arrayNameQuiz[valueSelected].length);
            nameQuizTwo = arrayDataSort[i][0];
            if(nameQuizOne == nameQuizTwo){
              if(sortSelected == 0){
                let valueOne = arrayDataSort[i][1].substring(0, arrayDataSort[i][1].indexOf(". "));
                let valueTwo = arrayDataSort[j][1].substring(0, arrayDataSort[j][1].indexOf(". "));
                if(parseInt(valueOne) > parseInt(valueTwo)){
                  var tmp = arrayDataSort[i];
                  arrayDataSort[i] = arrayDataSort[j];
                  arrayDataSort[j] = tmp;
                }
              }
              else if(sortSelected == 1){
                let valueOne = arrayDataSort[i][3].substring(0, arrayDataSort[i][3].indexOf(" / "));
                let valueTwo = arrayDataSort[j][3].substring(0, arrayDataSort[j][3].indexOf(" / "));
                if(parseInt(valueOne) < parseInt(valueTwo)){
                  var tmp = arrayDataSort[i];
                  arrayDataSort[i] = arrayDataSort[j];
                  arrayDataSort[j] = tmp;
                }
              }
            }
          }
        }
      }

      
      strValueElementTable += "<tr><th>STT</th><th>Tên Bài Thi</th><th>Tên Thí Sinh</th><th>Kết Quả</th><th>Điểm Thí Sinh</th><th>Điểm Đạt</th><th>Thời Gian Hoàn Thành</th><th>Thời Điểm Hoàn Thành</th></tr >";

      for(i=0; i<sizeOfArrayAllScores; i++){
        if(valueSelected == -1){
          strValueElementTable += "<tr>";
          strValueElementTable += "<td>" + (i+1).toString(10) + "</td>";
          strValueElementTable += "<td>" + arrayDataSort[i][0] + "</td>";
          strValueElementTable += "<td>" + arrayDataSort[i][1] + "</td>";
          strValueElementTable += "<td>" + arrayDataSort[i][2] + "</td>";
          strValueElementTable += "<td>" + arrayDataSort[i][3] + "</td>";
          strValueElementTable += "<td>" + arrayDataSort[i][4] + "</td>";
          strValueElementTable += "<td>" + arrayDataSort[i][5] + "</td>";
          strValueElementTable += "<td>" + arrayDataSort[i][6] + "</td>";
          strValueElementTable += "</tr>";
        }
        else if(arrayNameQuiz[valueSelected].substring(arrayNameQuiz[valueSelected].indexOf(":")+2, arrayNameQuiz[valueSelected].length) == arrayDataExport[i][0]){
          strValueElementTable += "<tr>";
          strValueElementTable += "<td>" + (serialNumber).toString(10) + "</td>";
          strValueElementTable += "<td>" + arrayDataSort[i][0] + "</td>";
          strValueElementTable += "<td>" + arrayDataSort[i][1] + "</td>";
          strValueElementTable += "<td>" + arrayDataSort[i][2] + "</td>";
          strValueElementTable += "<td>" + arrayDataSort[i][3] + "</td>";
          strValueElementTable += "<td>" + arrayDataSort[i][4] + "</td>";
          strValueElementTable += "<td>" + arrayDataSort[i][5] + "</td>";
          strValueElementTable += "<td>" + arrayDataSort[i][6] + "</td>";
          strValueElementTable += "</tr>";
          serialNumber = serialNumber + 1;
        }
      }

      elementTable.innerHTML = strValueElementTable;
    }

    function exportCSVResult(){
      var elementSelect = document.getElementById("numberQuiz");
      var valueSelected = elementSelect.value;

      //var csvContent = "data:text/csv;charset=utf-8,";
      var arrayDataExportCSV = []
      //alert(valueSelected);
      //alert(arrayNameQuiz[valueSelected].substring(arrayNameQuiz[valueSelected].indexOf(":")+2, arrayNameQuiz[valueSelected].length));
      if(valueSelected != -1){
        for(i=0; i<sizeOfArrayAllScores; i++){
          if(arrayNameQuiz[valueSelected].substring(arrayNameQuiz[valueSelected].indexOf(":")+2, arrayNameQuiz[valueSelected].length) == arrayDataExport[i][0]){
            arrayDataExportCSV.push(arrayDataExport[i]);
          }
        }
      }
      else{
        arrayDataExportCSV = arrayDataExport;
      }

      console.log(arrayDataExportCSV);
      alasql("SELECT * INTO CSV('cities.csv') FROM ?",[arrayDataExportCSV]);
    }

  </script>
</body>
</html>