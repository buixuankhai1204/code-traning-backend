<?php
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_RETURNTRANSFER => 1,
  CURLOPT_URL => 'https://baomoi.com',
  CURLOPT_SSL_VERIFYPEER => false,
));
$resp = curl_exec($curl);
if (!$resp) {
  echo "<p>cURL error number: " . curl_errno($c) . " on URL: " . $url . "</p>" .
    "<p>cURL error: " . curl_error($c) . "</p>";
}
curl_close($curl);
//-----------------------------------------------------------------------------------
try {
  $resp = mb_convert_encoding($resp, 'HTML-ENTITIES', "UTF-8");
  $dom = new DOMDocument();
  if (!@$dom->loadHtml($resp)) {
    throw new Exception('khong the load duoc HTML');
  }
  $xpath = new DOMXPath($dom);

  $xPahtName = $xpath->query('//div[@class="timeline loadmore"]/div[@class="story" and position()<=7]/h4[@class="story__heading"]/a');
  $xPahtThumb = $xpath->query('//div[@class="timeline loadmore"]/div[@class="story" and position()<=7]/div[@class="story__thumb"]/a/img/@data-src');

  if (count($xPahtName) != count($xPahtThumb)) {
    json_encode('co bai viet bi loi');
  }
  $title = [];
  $thumb = [];
  foreach ($xPahtName as $index => $xPahtName) {
    array_push($title, $xPahtName->textContent);
    array_push($thumb, $xPahtThumb[$index]->textContent);
  }
  $result = array_combine($title, $thumb);
} catch (Exception $e) {
  echo $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./css/style.css">
  <title>Document</title>
</head>
<style>
  ul {
    display: flex;
    justify-content: space-between;
    flex-direction: column;

  }

  li {
    list-style: none;
  }
</style>

<body>

  <div class="wrapper">
    <ul>
      <?php foreach ($result as $index => $value) { ?>
        <li>
          <a class="title" href=""><img src="<?php echo $value ?>" alt=""><?php echo $index ?></a>
        </li>
      <?php } ?>
    </ul>
    <div class="form-input">
      <label for="input4" class="icon glyphicon glyphicon-pencil"></label>
      <button id="convert" class="button glyphicon glyphicon-search"></button>
      <input id="uid" name="titlePost" type="text" class="input">
    </div>
    <h1 class="title">
    </h1>
    <div class="class">
    </div>
  </div>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
  $("#convert").click(function() {
    var result1 = "";
    var arrayTitle = [];
    var nameItem = $("#uid").val();
    var data = {
      nameItem: nameItem
    }
    $.ajax({
      type: "POST",
      url: "http://localhost:8081/searchXpath.php",
      data: data,
      dataType: "json",
      success: function(result) {
        if (result['status_code'] == 200) {
          arrayTitle = Object.keys(result['data'][0]);
          arrayThumb = Object.values(result['data'][0]);
          for (i = 0; i < arrayTitle.length; i++) {
          
            console.log(arrayTitle[i]);
            result1 += 
            '<div class="listItem listItem-' + arrayTitle[i] + ' border">' +
              '<div class="TitleToDo">' +
              '<img src="' + arrayThumb[i] + '" class="demo">' +
              '<p class="demo">' + arrayTitle[i] + '</p>' +
              '</div>' + '</div>'
            $(".class").html(result1);
            $(".title").html(result['data'][1]);

          }
        } else {
          $(".class").html(result);
          $(".title").none();
         
        }

      }
    });
  });
</script>

</html>