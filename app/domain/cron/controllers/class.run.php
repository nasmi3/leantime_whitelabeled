<?php

namespace leantime\domain\controllers {

    use leantime\core;
    use leantime\core\controller;
    use leantime\domain\services\cron;

    class run extends controller
    {

        private cron $cronSvc;


        /**
         * init - initialize private variables
         *
         * @access public
         */
        public function init()
        {
            $this->cronSvc = new cron();

        }

        public function run() {

            $this->cronSvc->runCron();

        }


    }
}
