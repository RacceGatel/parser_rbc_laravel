<?php

namespace App;

use App\Models\Log;
use App\Models\News;
use Illuminate\Support\Facades\Http;

class ParserModule
{
    /**
     * Get content from xml.
     *
     *
     * @return array
     */
    public function getContent()
    {

        $url = 'http://static.feed.rbc.ru/rbc/logical/footer/news.rss';

        $request = Http::get($url);

        //logging news
        $log = new Log();
        $log->timestamps = false;
        $log->date = now();
        $log->request_method = 'get';
        $log->request_url = $url;
        $log->response_http_code = $request->status();
        $log->response_body = $request->body();
        $log->save();

        //parse xml content
        $xml = simplexml_load_string($request);

        $blocks  = $xml->xpath('//item'); //get all <item> blocks

        return $blocks;
    }

    /**
     * Get content from xml.
     *
     *
     * @return void
     */
    public function loadContent()
    {
        $content = $this->getContent();

        foreach($content as $parse_news) {
            try {
                $news = new News;
                $news->name = $parse_news->title;
                $news->url = $parse_news->link;
                $news->short = $parse_news->description;
                $news->date = $parse_news->pubDate;
                $news->guid = $parse_news->guid;
                isset($parse_news->author) ? $news->author = $parse_news->author : null;

                $img_ar = [];

                foreach ($parse_news->enclosure as $item) {
                    $type = explode('/', $item['type']->__toString())[0];
                    if($type == 'image')
                        array_push($img_ar, $item['url']->__toString());
                }

                count($img_ar) > 0 ? $news->image = $img_ar : null;
                //isset($parse_news->enclosure['url']) ? $news->image = $parse_news->enclosure['url'] : null;
                $news->timestamps = false;

                $news->save();
            } catch (\Exception $e)
            {

            }
        }
    }
}
