<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE.md
 */
class Font
{
    private $link = '';

    private $folder_root = 'fonts';

    private $folder_css = 'css';

    private $folderFont = 'source';

    private $forceReplace = false;

    private $userAgent = 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36';

    private $listFileCss = [];

    private $listFileFont = [];

    public function set(array $args)
    {
        $args = array_merge(
            [
                'link'         => '',
                'folder_root'  => 'fonts',
                'folder_css'   => 'css',
                'folderFont'   => 'source',
                'forceReplace' => false,
                'userAgent'    => 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36',
            ],
            $args
        );

        foreach ($args as $key => $value) {
            $this->{$key} = $value;
        }
        return $this;
    }

    /**
     * Download fonts by css link.
     *
     * @since 1.0.0
     *
     * @param mixed $link
     */
    public function download($link)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }

    /**
     * @since 1.0.0
     */
    public function getCss()
    {
        $listLink = $this->fetchLink();
        for ($e = 0; $e < count($listLink['link']); ++$e) {
            $originCss = $this->download($listLink['link'][$e]);
            $data = $this->fetchCss($originCss);
            for ($i = 0; $i < count($data['url']); ++$i) {
                $fontName = $this->setFileName($data['fontFamily'][$i], $data['fontStyle'][$i], $data['fontWeight'][$i], $data['charType'][$i], $data['url'][$i]);
                $this->saveFont($fontName, $this->download($data['url'][$i]));
                $this->listFileFont[] = $this->folder_root . '/' . $this->folderFont . '/' . $fontName;
                $originCss = str_replace($data['url'][$i], '../' . $this->folderFont . '/' . $fontName, $originCss);
            }
            $cssName = $listLink['cssName'][$e] . '.css';
            $this->saveCss($cssName, $originCss);
            $this->listFileCss[] = $this->folder_root . '/' . $this->folder_css . '/' . $cssName;
        }
    }

    /**
     * Retrieves a list of files.
     *
     * @since 1.0.0
     */
    public function getListFile()
    {
        return array_merge($this->listFileCss, $this->listFileFont);
    }

    /**
     * Creates folders for storing fonts and css styles.
     *
     * @since 1.0.0
     */
    private function createFolder()
    {
        if ( ! is_dir($this->folder_root)) {
            mkdir($this->folder_root);
        }
        if ( ! is_dir($this->folder_root . '/' . $this->folder_css)) {
            mkdir($this->folder_root . '/' . $this->folder_css);
        }
        if ( ! is_dir($this->folder_root . '/' . $this->folderFont)) {
            mkdir($this->folder_root . '/' . $this->folderFont);
        }
    }

    /**
     * @since 1.0.0
     */
    private function fetchLink()
    {
        if (preg_match('/^http?s:\/\/fonts.googleapis.com\/css2\?family=(.*)/', $this->link, $match)) {
            $exp = explode('|', $match[1]);
            $result = [
                'cssName' => [],
                'link'    => [],
            ];
            foreach ($exp as $font) {
                $result['link'][] = 'https://fonts.googleapis.com/css2?family=' . $font;
                $result['cssName'][] = str_replace(['+'], ['-'], $font);
            }
            return $result;
        }
        return new Errors(Debug::get_backtrace(), I18n::__('You must supply valid google fonts link.'));
    }

    /**
     * Sets the font name.
     *
     * @since 1.0.0
     *
     * @param mixed $fontFamily
     * @param mixed $fontStyle
     * @param mixed $fontWeight
     * @param mixed $charType
     * @param mixed $url
     */
    private function setFileName($fontFamily, $fontStyle, $fontWeight, $charType, $url)
    {
        $ext = explode('.', $url);
        return str_replace([' ', '\''], ['-', ''], trim($fontFamily)) . '_' . trim($fontStyle) . '_' . trim($fontWeight) . '_' . trim($charType) . '.' . end($ext);
    }

    /**
     * Saves the font at the specified path.
     *
     * @since 1.0.0
     *
     * @param mixed $filename
     * @param mixed $content
     */
    private function saveFont($filename, $content)
    {
        if ( ! is_dir($this->folderFont)) {
            $this->createFolder();
        }
        if ( ! is_file($this->folderFont . '/' . $filename) || $this->forceReplace) {
            file_put_contents($this->folder_root . '/' . $this->folderFont . '/' . $filename, $content);
        } else {
            return new Errors(Debug::get_backtrace(), I18n::__('Skipped existing file: ') . $filename);
        }
    }

    /**
     * Saves the CSS at the specified path.
     *
     * @since 1.0.0
     *
     * @param mixed $filename
     * @param mixed $content
     */
    private function saveCss($filename, $content)
    {
        if ( ! is_dir($this->folder_css)) {
            $this->createFolder();
        }
        if ( ! is_file($this->folder_css . '/' . $filename) || $this->forceReplace) {
            file_put_contents($this->folder_root . '/' . $this->folder_css . '/' . $filename, $content);
        } else {
            return new Errors(Debug::get_backtrace(), I18n::__('Skipped existing file: ') . $filename);
        }
    }

    /**
     * Retrieves css styles.
     *
     * @since 1.0.0
     *
     * @param mixed $css
     */
    private function fetchCss($css)
    {
        $result = [
            'charType'     => '/\/\*(.*)\*\//',
            'fontFamily'   => '/font-family:(.*);/',
            'fontStyle'    => '/font-style:(.*);/',
            'fontWeight'   => '/font-weight:(.*);/',
            'url'          => '/url\(([^)]+)\)/',
            'unicodeRange' => '/unicode-range:(.*);/',
        ];
        foreach ($result as $style => $regex) {
            preg_match_all($regex, $css, $matches);
            if (isset($matches[1]) && count($matches[1]) > 0) {
                $result[$style] = [];
                foreach ($matches[1] as $match) {
                    $result[$style][] = $match;
                }
            }
            unset($matches);
        }
        return $result;
    }
}
