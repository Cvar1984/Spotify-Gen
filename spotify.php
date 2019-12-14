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
        if (!$result) {
            throw new Exception('Failed API limit');
        }
        $this->result = $result;
    }

    public function saveResult($fileName, $mode)
    {
        $tulis = fopen($fileName, $mode);
        if ($tulis) {
            $this->result = json_decode($this->result, true);
            $this->result = json_encode($this->result, JSON_PRETTY_PRINT);
            fprintf($tulis, $this->result . ',');
            fclose($tulis);
        } else {
            throw new Exception('failed to save result');
        }
    }
}
try {
    $banner = fopen('assets/text/banner.txt', 'r');
    $banner = fread($banner, filesize('assets/text/banner.txt'));

    fprintf(STDOUT, '%s%s', $banner, PHP_EOL);

    $jumlah = readline('jumlah >> ');
    $generate = new Spotify($jumlah);
    $generate->saveResult('result.json', 'a');

    $json = json_decode($generate->result, true);

    foreach ((array)$json as $row) {
        fprintf(STDOUT, "Type    :\t%s\n", $row['Account Type']);
        fprintf(STDOUT, "Email   :\t%s\n", $row['Email']);
        fprintf(STDOUT, "Pass    :\t%s\n", $row['Password']);
        if (!empty($row['Country'])) {
            fprintf(STDOUT, "Country :\t%s\n", $row['Country']);
        }
        if (!empty($row['Expired'])) {
            fprintf(STDOUT, "Expired :\t%s\n", $row['Expired']);
        }
    }
} catch (Exception $e) {
    fprintf(STDERR, "%s%s", $e->getMessage(), PHP_EOL);
    exit(1);
}
