<?php
/**
 * Created by PhpStorm.
 * User: Hemraj Solanki
 * Date: 2/19/2019
 * Time: 4:00 PM
 */

namespace App\Imports;

use App\Model\ShareInfo;

class ShareImport
{
    private $domDocument;

    /**
     * ParseDocument constructor
     */
    public function __construct()
    {
        $this->setDomDocument();
    }

    /**
     * Get domDocument object
     *
     * @return domDocument
     */
    public function getDomDocument()
    {
        return $this->domDocument;
    }

    /**
     * Set domDocument object
     */
    public function setDomDocument()
    {
        $this->domDocument = new \domDocument();
    }

    /**
     * Initialize scrapping websites
     *
     * @param string $url
     * @return bool
     */
    public function get($url)
    {
        $file = $this->pullDataFromRemote($url);
        libxml_use_internal_errors(true);
        //dd($file, $this->domDocument->loadHTML($file));
        return ($file) ? $this->domDocument->loadHTML($file) : false;
    }

    /**
     * This function will pull data from remote URLs
     *
     * @return string
     */
    public function pullDataFromRemote($url): string
    {
        $context = stream_context_create(
            array(
                'http' => array(
                    'header' => array('User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201')
                )
            )
        );
        return @file_get_contents(
            $url,
            false,
            $context
        );
    }

    /**
     * Search dom by id attribute
     *
     * @param string $id
     * @return string
     */
    public function findId($id)
    {
        $element = $this->domDocument->getElementById($id);
        return $element;
    }

    /**
     * Search dom by class attribute
     *
     * @param string $class
     * @return DOMNodeList
     */
    public function findClass($class)
    {
        $finder = new \DomXPath($this->domDocument);
        return $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), '$class')]");
    }

    /**
     * Search dom by tag name
     *
     * @param string $class
     * @return DOMNodeList
     */
    public function findTag($tagName)
    {
        $element = $this->domDocument->getElementsByTagName($tagName);
        return $element;
    }

    /**
     * @param array $row
     *
     * @return User|null
     */
    public function contextValue()
    {
        $context = stream_context_create(
            array(
                'http' => array(
                    'header' => array('User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201'),
                    'timeout' => 10000
                ),
            )
        );
        return $context;
    }

    public function downloadZip($url)
    {
        $str = "\\extract-here";
        $path = public_path() . $str;
        $f = file_put_contents("my-zip.zip", fopen("$url", 'r', 0, $this->context), LOCK_EX, $this->context);
        if (false === $f) {
            die("Couldn't write to file.");
        }
        $zip = new \ZipArchive;
        $res = $zip->open('my-zip.zip');
        if ($res === true) {
            $zip->extractTo($path);
            $zip->close();
            return true;
        } else {
            return false;
        }
    }

    public function convertPlainTextLineByLineToArray(string $data)
    {
        $convert = explode("\n", $data); //create array separate by new line
        foreach ($convert as $value) {
            $dataArray[] = explode(",", $value);
        }
        return $dataArray;
    }

    public function jsonReturnUrl($url)
    {
        $json = json_decode(file_get_contents($url, false, $this->contextValue()), true);
        return $json;
    }

    public function getNodeValue($nodeRawData)
    {
        foreach ($nodeRawData as $tag) {
            $searchChar = ["\t\r", "\r", "\t", " ", "Chart", ","];
            $nodeProcessedData[] = str_replace($searchChar, '', $tag->nodeValue);
        }
        return $nodeProcessedData;
    }

    public function convertWholeLineToArray(array $lineData)
    {
        foreach ($lineData as $key => $value) {
            $lineDataArray[] = array_values(array_filter(explode("\n", $value)));
        }
        return $lineDataArray;
    }
}
