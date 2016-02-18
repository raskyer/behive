<?php

namespace AppBundle\Service;

use Symfony\Component\Config\Definition\Exception\Exception;

class ApiGameService
{
    protected $cache;
    protected $apiBase;
    protected $apiKey;
    protected $format;

    public function __construct($cache)
    {
        $this->cache = $cache;
        $this->apiBase = "http://www.giantbomb.com/api/";
        $this->apiKey = "api_key=838b1d8ea15ef015b443db5049548da60c4ed8d8";
        $this->format = "&format=json";
    }

    protected function callApi($id, $url)
    {
        if (!$result = $this->cache->getInCache($id)) {
            try {
                $options = array(
                    "http"=>array(
                        "header"=>"User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n" // i.e. An iPad
                    )
                );
                $context = stream_context_create($options);
                $result = file_get_contents($url, false, $context);
            } catch(Exception $e) {
                return $e->getMessage();
            }

            $this->cache->storeInCache($id, $result);
        }

        return json_decode($result, true);
    }

    protected function idConstructor($action, $query)
    {
        $id = $action . "-" . $query;
        $id = str_replace("=", "-", $id);
        $id = preg_replace('/[^a-zA-Z0-9-_\.]/','', $id);
        $id = strtolower($id);

        return $id;
    }

    protected function urlConstructor($base, $action = "", $query = "", $parameters = "", $fieldList = "")
    {
        $url = $base . $action . "?" . $this->apiKey . $this->format . $query . $parameters . $fieldList;

        return $url;
    }

    public function searchVideoGame($game = null)
    {
        $action = "search/";
        $query = "&query=\"$game\"";
        $parameter = "&resources=game";
        $fieldList = "&field_list=name,image,deck,api_detail_url";

        $url = $this->urlConstructor($this->apiBase, $action, $query, $parameter, $fieldList);
        $id = $this->idConstructor($action, $query);

        return $this->callApi($id, $url);
    }

    public function getVideoGame($url, $name)
    {
        $id = $this->idConstructor("game", $name);
        $fieldList = "&field_list=name,image,deck,original_release_date,publishers,genres,original_game_rating,id";
        $url = $this->urlConstructor($url, '', '', '', $fieldList);

        return $this->callApi($id, $url);
    }
}
