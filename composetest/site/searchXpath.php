<?php

try {
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => 'https://baomoi.com/chu-de.epi',
    CURLOPT_SSL_VERIFYPEER => false,
  ));
  $resp = curl_exec($curl);
  if (!$resp) {
    echo "<p>cURL error number: " . curl_errno($c) . " on URL: " . $url . "</p>" .
      "<p>cURL error: " . curl_error($c) . "</p>";
  }
  curl_close($curl);
  $resp = mb_convert_encoding($resp, 'HTML-ENTITIES', "UTF-8");

  $dom = new DOMDocument();
  if (!@$dom->loadHtml($resp)) {
    throw new Exception('khong the load duoc HTML');
  }
  $xpath = new DOMXPath($dom);
  $inputTitle = $_POST['nameItem'];
  $StrContent = $dom->textContent;
  $title = [];
  $thumb = [];
  $getCategory;
  if (strpos($dom->textContent, $inputTitle)) {
    $xPathSearchParent = $xpath->query("//*[contains(text(),'$inputTitle')]/parent::*/parent::*/parent::*/parent::*/@data-zone");
    foreach ($xPathSearchParent as $item) {
      $getAid = $item->textContent;
    }
    $xPathNameCategory = $xpath->query("//div[@class='loadmore']/div[contains(@data-zone,'$getAid')]/div[1]");
    $getCategory = $xPathNameCategory[0]->textContent;
    $xPathTitle = $xpath->query("//div[@class='loadmore']/div[contains(@data-zone,'$getAid')]/div/div/h4/a");
    $xPathThumb = $xpath->query("//div[@class='loadmore']/div[contains(@data-zone,'$getAid')]/div[2]/div/div[1]/a/img/@src|//div[@class='loadmore']/div[contains(@data-zone,'$getAid')]/div[2]/div/div[1]/a/img/@data-src");
    foreach ($xPathTitle as $index => $item) {
      array_push($title, $item->textContent);
      array_push($thumb, $xPathThumb[$index]->textContent);
    }
    $result = array_combine($title, $thumb);
  } else {
    throw new Exception('khong tim thay bai viet');
  }
  $array_respone = array(
    "success" => true,
    "status_code" => 200,
    "data" => [$result, $getCategory],
    "message" => "success",
    "error" => "",
  );
  echo json_encode($array_respone);
} catch (Exception $e) {
  echo json_encode($e->getMessage());
}
