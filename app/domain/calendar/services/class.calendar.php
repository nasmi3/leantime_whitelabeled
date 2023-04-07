<?php

namespace leantime\domain\services {

    use leantime\core;
    use leantime\domain\models\auth\roles;
    use leantime\domain\repositories;

    class calendar
    {
        private repositories\calendar $calendarRepo;
        public function __construct() {
            $this->calendarRepo = new repositories\calendar();
        }


        /**
         * Patches calendar event
         *
         * @access public
         * @params $id id of event to be updated (only events can be updated. Tickets need to be updated via ticket api
         * @params $params key value array of columns to be updated
         *
         * @return bool true on success, false on failure
         */
        public function patch($id, $params): bool {

            //Admins can always change anything.
            //Otherwise user has to own the event
            if($this->userIsAllowedToUpdate($id)) {
                return $this->calendarRepo->patch($id, $params);
            }

            return false;
        }

        /**
         * Checks if user is allowed to make changes to event
         *
         * @access public
         * @params int $eventId Id of event to be checked
         *
         * @return bool true on success, false on failure
         */
        private function userIsAllowedToUpdate($eventId) {

            if(auth::userIsAtLeast(roles::$admin)) {
                return true;
            } else {
                $event = $this->calendarRepo->getEvent($eventId);
                if($event && $event["userId"] == $_SESSION['userdata']['id']){
                    return true;
                }
            }

            return false;
        }

    }

}
