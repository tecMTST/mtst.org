<?php

$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://api.spotify.com/v1/shows/5X2nnU5w9uptyMiApbPCWj/episodes?market=br&limit=2&offset=1",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_POSTFIELDS => "",
  CURLOPT_HTTPHEADER => [
    "Accept: application/json",
    "Authorization: Bearer BQB4KL2LGIviYANQz12a9YCDbwvbNzw3gTX-EPzHTAZIb_uhCC3Nwz5BZT2tTemZoCvDB_vwbc_JFWr_is420p02z3vvTx0j3TQma5FX_bHYCm7-Ez0w1P9dvuyIeIL-BEy-QWa4dpYft_Y6r840vsKkgS0dtOaKAiwgSPHWESQyTItV1WNS_5sSHSPBxWYgLz7f6Q",
    "Content-Type: application/json"
  ],
]);

$response = (curl_exec($curl));
$err = curl_error($curl);

//curl_close($curl);

// if ($err) {
//   echo "cURL Error #:" . $err;
// } else {
// }

$dados = json_decode($response, true);

print_r($dados);

// echo '<h2>'.$dados["items"]["audio_preview_ur"].'</h2>';

// foreach ($reponse->itens as $episode) {
//   var_dump($episode);
//   //echo $episode->spotfy;
// }

?>