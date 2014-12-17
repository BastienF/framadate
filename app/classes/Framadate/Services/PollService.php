<?php
namespace Framadate\Services;

class PollService {

    private $connect;

    function __construct($connect) {
        $this->connect = $connect;
    }

    function findById($poll_id) {
        if (preg_match('/^[\w\d]{16}$/i', $poll_id)) {
            return $this->connect->findPollById($poll_id);
        }

        return null;
    }

    function allCommentsByPollId($poll_id) {
        return $this->connect->allCommentsByPollId($poll_id);
    }

    function allUserVotesByPollId($poll_id) {
        return $this->connect->allUserVotesByPollId($poll_id);
    }

    function allSlotsByPollId($poll_id) {
        return $this->connect->allSlotsByPollId($poll_id);
    }

    public function updateVote($poll_id, $vote_id, $choices) {
        $choices = implode($choices);
        return $this->connect->updateVote($poll_id, $vote_id, $choices);
    }

    function addVote($poll_id, $name, $choices) {
        $choices = implode($choices);
        return $this->connect->insertVote($poll_id, $name, $choices);
    }

    function addComment($poll_id, $name, $comment) {
        return $this->connect->insertComment($poll_id, $name, $comment);
    }

    function computeBestMoments($votes) {
        $result = [];
        foreach ($votes as $vote) {
            $choices = str_split($vote->reponses);
            foreach ($choices as $i=>$choice) {
                if (empty($result[$i])) {
                    $result[$i] = 0;
                }
                if ($choice == 2) {
                    $result[$i]++;
                }
            }
        }
        return $result;
    }

    function splitSlots($slots) {
        $splitted = array();
        foreach ($slots as $slot) {
            $ex = explode('@', $slot->sujet);
            $obj = new \stdClass();
            $obj->day = $ex[0];
            $obj->moments = explode(',', $ex[1]);

            $splitted[] = $obj;
        }
        return $splitted;
    }

    function splitVotes($votes) {
        $splitted = array();
        foreach ($votes as $vote) {
            $obj = new \stdClass();
            $obj->id = $vote->id_users;
            $obj->name = $vote->nom;
            $obj->choices = str_split($vote->reponses);

            $splitted[] = $obj;
        }
        return $splitted;
    }
}
