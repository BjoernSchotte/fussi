<?php

namespace Application\ViewHelper;

use Zend\View\Helper\AbstractHelper;

class Match extends AbstractHelper
{

    public function __invoke($date, $tournament, $matches, $player1, $player2)
    {

        $now = new \DateTime();
        $allow = $now->format('Ym') == $date->format('Ym');

        $out = '';

        if ($player1 != $player2) {

            $ok = false;
            foreach ($matches as $match) {

                $url = $this->getView()->url('match/edit', array(
                    'mid' => $match->getId()
                ));


                if ($match->isPlayedBy($player1, $player2)) {

                    $title = $player1->getName() . " vs. " . $player2->getName();
                    $content = sprintf("Game 1: %s / %s<br>Game 2: %s / %s",
                        $match->getGoalsGame1Player1(),
                        $match->getGoalsGame1Player2(),
                        $match->getGoalsGame2Player1(),
                        $match->getGoalsGame2Player2()
                    );

                    if ($allow) {
                        $content .= "<br><br><a href='" . $url . "' class='btn btn-small'>Edit</a>";
                    }

                    $out .= '<span title="' . $title . '" data-content="' . $content . '" class="match btn result">';
                    $out .= $match->getScore();
                    $out .= '</span>';
                    $ok = true;

                }
            }

            if (!$ok && $allow) {

                $url = $this->getView()->url('match/new/', array('tid' => $tournament->getId(), 'player1' => $player1->getId(), 'player2' => $player2->getId()));
                $out .= '<a href="' . $url . '" class="btn btn-small">Edit</a>';

            }

        }

        return $out;

    }

}
