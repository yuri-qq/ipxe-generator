<?php
    namespace Yurifag\IPXE;
    abstract class Item {
        /**
         * internal counter how many Item objects were created
         * @var integer
         */
        protected static $count = 0;
        protected $id;
        protected $text;

        /**
         * generic item class
         * @param string $text Text to be shown on screen
         */
        protected function __construct(string $text) {
            self::$count++;
            $this->id = "item".self::$count;

            $this->text = $text;
        }

        abstract public function get();
    }
?>
