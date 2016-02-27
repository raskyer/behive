<?php
namespace AppBundle\Service;

class GameService
{
    public function createGame($categories, $editors, $response)
    {
        $categoriesArray = array();
        foreach($categories as $category) {
            $categoriesArray[] = $category->getName();
        }

        $editorsArray = array();
        foreach($editors as $editor) {
            $editorsArray[] = $editor->getName();
        }

        $game = new \AppBundle\Entity\Game();
        $game->setName($response['name']);

        $filename = "api-".$response['id']."-img.jpg";
        $dir = __DIR__."/../../../web/uploads/game/";
        //copy($response['image']['medium_url'], $dir.$filename);
        $game->setCover("uploads/game/$filename");

        $game->setDescription($response['deck']);
        $game->setRating(0);

        if($response['original_game_rating']){
            foreach($response['original_game_rating'] as $rating) {
                if(strpos($rating['name'], "PEGI:") !== false) {
                    preg_match_all('!\d+!', $rating['name'], $matches);
                    $game->setPegi($matches[0][0]);
                    break;
                }
            }
        }

        $date = $response['original_release_date'];
        $date = new \DateTime($date);
        $game->setDate($date);

        $categoriesToPersist = array();
        foreach($response['genres'] as $genre) {
            $id = array_search($genre["name"], $categoriesArray);
            if($id === false) {
                $newCategory = new \AppBundle\Entity\Category();
                $newCategory->setName($genre["name"]);

                $game->addCategory($newCategory);
                $categoriesToPersist[] = $newCategory;
            } else {
                if(!in_array($categories[$id], $game->getCategories()->getValues())) {
                    $game->addCategory($categories[$id]);
                }
            }
        }

        $publisher = $response['publishers'][0]['name'];

        $newEditor = false;
        $id = array_search($publisher, $editorsArray);
        if($id === false) {
            $newEditor = new \AppBundle\Entity\Editor();
            $newEditor->setName($publisher);

            $game->setEditor($newEditor);
        } else {
            $game->setEditor($editors[$id]);
        }

        $return = array("game" => $game, "categories" => $categoriesToPersist, "editor" => $newEditor);

        return $return;
    }
}