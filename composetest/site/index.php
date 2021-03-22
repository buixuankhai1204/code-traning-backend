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
  $dom = new DOMDocument();
  if (!@$dom->loadHtml($reasp)) {
    throw new Exception('khong the load duoc HTML');
  }
  $xpath = new DOMXPath($dom);

  $xPahtName = $xpath->query('//div[@class="rightBox-3"]/div/div/h4');
  $xPahtThumb = $xpath->query('//div[@class="rightBox-3"]/div/div/div/a/img/@data-src');

  if ($xPahtName == "" || $xPahtThumb == "") {
    throw new Exception('du lieu khong ton tai');
  }
  $title = [];
  $thumb = [];
  foreach ($xPahtName as $item) {
    array_push($title, $item->textContent);
  }
  foreach ($xPahtThumb as $item) {
    array_push($thumb, $item->textContent);
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
  <title>Document</title>
</head>
<style>
  .container {
    width: 1400px;
    margin: 0px auto;
  }

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
  <div class="container">
    <ul>
      <?php foreach ($result as $index => $value) { ?>
        <li>
          <a href=""><img src="<?php echo $value ?>" alt=""><?php echo $index ?></a>
        </li>
      <?php } ?>
    </ul>
  </div>

</body>

</html>