<?php

namespace App\Services\FeedRSS;

use Exception;
use Illuminate\Support\Collection;
use Saloon\XmlWrangler\XmlReader;

class PostParserService
{
    /**
     * @param $content
     * @return Collection
     */
    public function parsePosts($content)
    {
        try {
            $data = $this->getDataFromXML($content);
        } catch (Exception $e) {
            $data = $this->getDataFromXML($content);
        }

        foreach ($data['rss']['channel']['item'] as $post)
        {
            $this->posts[] = $this->collectPost($post);
        }

        return collect($this->posts);
    }

    private function getDataFromXML($content) {
        $reader = XmlReader::fromString($content);
        return $reader->values();
    }

    private function collectPost($itemData)
    {
        try {
            $data['title'] = $itemData['title'];
            $data['guid'] = $itemData['guid'];
            $data['date'] = $itemData['pubDate'];
            $data['description'] = $this->clearDescription($itemData['description']);
            $data['thumbnail'] = $itemData['media:thumbnail'];
            $data['content'] = $itemData['content:encoded'];
            $data['link'] = $itemData['link'];
            $data['category'] = $itemData['category'];
            $data['slug'] = str_replace(env('FEED_URL'),'', $itemData['link']);
            $data['image'] = $this->getImageFromDescription($itemData['content:encoded']);
        } catch (Exception $e) {
            dd($e->getMessage());
        }
        return $data;
    }

    /**
     * @param $xmlObj
     * @return array
     */
    private function makePostFromXml($xmlObj)
    {
        $data['title'] = $xmlObj->title->__toString();
        $data['link'] = $xmlObj->link->__toString();
        $data['description'] = $xmlObj->description->__toString();
        $data['content'] = $xmlObj->content->__toString();
        $data['date'] = $xmlObj->pubDate->__toString();
        $data['guid'] = $xmlObj->guid->__toString();
        $data['slug'] = str_replace(env('FEED_URL'),'', $xmlObj->link->__toString());
        $data['category'] = collect($xmlObj->category->__toString());
        $data['image'] = $this->getImageFromDescription($data['description']);
        $data['description'] = $this->clearDescription($data['description']);

        return $data;
    }

    private function clearDescription($description)
    {
        $description = strip_tags($description, '<p><em><br><strong><b>');
        $description = str_replace('<p>Read more...</p>', '', $description);
        $description = str_replace('...', '', $description);

        return $description;
    }

    private function getImageFromDescription($description)
    {
        $patttern = "/<img\s.*?src=(?:'|\")([^'\">]+)(?:'|\").*?\/?>/";

        if (preg_match($patttern, $description, $matches))
        {
            return $matches[1];
        }

        return '';
    }
}
