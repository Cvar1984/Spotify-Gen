<?php
class Spotify
{
    public $result;
    function __construct($jumlah)
    {
        if (!is_numeric($jumlah)) {
            throw new Exception('jumlah not numeric');
        }
        $jumlah = trim($jumlah);
        $result = file_get_contents("http://n1ghthax0r.000webhostapp.com/api/spotify/?jumlah={$jumlah}");
        $this->result = $result;
    }

    public function saveResult($fileName, $mode)
    {
        $tulis = fopen($fileName, $mode);
        if ($tulis) {
            fprintf($tulis, $this->result);
            fclose($tulis);
        } else {
            throw new Exception('failed to save result');
        }
    }
}
try {
    $banner = file_get_contents('banner.txt');
    fprintf(STDOUT, "%s\n", $banner);
    $jumlah = readline('jumlah >> ');
    $generate = new Spotify($jumlah);
    $generate->saveResult('result.json', 'a');

    $json = json_decode($generate->result, true);

    foreach ((array)$json as $row) {
        fprintf(STDOUT, "Type    :\t%s\n", $row['Account Type']);
        fprintf(STDOUT, "Email   :\t%s\n", $row['Email']);
        fprintf(STDOUT, "Pass    :\t%s\n", $row['Password']);
        fprintf(STDOUT, "Country :\t%s\n", $row['Country']);
        if (!empty($row['Expired'])) {
            fprintf(STDOUT, "Expired :%s\n", $row['Expired']);
        }
    }
} catch (Exception $e) {
    fprintf(STDERR, "%s\n", $e->getMessage());
    exit(1);
}
